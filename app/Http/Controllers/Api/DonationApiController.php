<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DonationApiController extends Controller
{

    public function index(Request $request)
    {
        $donations = Donation::with('campaign')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($donations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'amount' => 'required|numeric|min:1000',
            'phone' => 'required|string|min:10|max:15',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'is_anonymous' => 'nullable|boolean',
            'payment_method' => 'required|string'
        ]);

        try {

            return DB::transaction(function () use ($request) {

                $reference = 'DON-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);

                $donation = Donation::create([
                    'campaign_id' => $request->campaign_id,
                    'name' => $request->boolean('is_anonymous') ? 'Anonim' : $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'amount' => $request->amount,
                    'payment_method' => $request->payment_method,
                    'reference' => $reference,
                    'status' => 'pending'
                ]);

                $merchantCode = config('duitku.merchant_code');
                $apiKey = config('duitku.api_key');
                $amount = (int) $donation->amount;

                $signature = md5($merchantCode . $reference . $amount . $apiKey);

                $endpoint = config('duitku.sandbox')
                    ? 'https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry'
                    : 'https://passport.duitku.com/webapi/api/merchant/v2/inquiry';

                $response = Http::post($endpoint, [
                    'merchantCode' => $merchantCode,
                    'paymentAmount' => $amount,
                    'merchantOrderId' => $reference,
                    'paymentMethod' => $donation->payment_method,
                    'productDetails' => 'Donasi Gereja',
                    'customerEmail' => $donation->email ?? 'donasi@email.com',
                    'customerPhone' => $donation->phone,
                    'callbackUrl' => config('app.url') . '/api/duitku/callback',
                    'returnUrl' => config('app.url') . '/payment-success?reference=' . $reference,
                    'signature' => $signature
                ]);

                if (!$response->successful()) {
                    throw new \Exception('Gagal request ke Duitku');
                }

                $data = $response->json();

                return response()->json([
                    'message' => 'Redirect ke payment',
                    'reference' => $reference,
                    'payment_url' => $data['paymentUrl'] ?? null
                ]);
            });
        } catch (\Throwable $e) {

            Log::error('Donation store failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Failed to create donation'
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        Log::info('CALLBACK MASUK', $request->all());

        return DB::transaction(function () use ($request) {

            $merchantCode = config('duitku.merchant_code');
            $apiKey = config('duitku.api_key');

            $reference = $request->merchantOrderId;
            $amount = (int) $request->input('amount');
            $resultCode = $request->resultCode;
            $signature = $request->signature;
            $duitkuReference = $request->reference;

            $donation = Donation::where('reference', $reference)
                ->lockForUpdate()
                ->first();

            if (!$donation) {
                return response()->json(['message' => 'Donation not found'], 404);
            }

            if ((int)$donation->amount !== $amount) {
                return response()->json(['message' => 'Invalid amount'], 400);
            }

            $expectedSignature = md5($merchantCode . $amount . $reference . $apiKey);

            if ($signature !== $expectedSignature) {
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            $donation->update([
                'duitku_reference' => $duitkuReference,
                'callback_payload' => json_encode($request->all())
            ]);

            if ($resultCode === "00" && $donation->status !== 'paid') {

                $donation->update(['status' => 'paid']);

                $campaign = $donation->campaign()->lockForUpdate()->first();

                $campaign->increment('current_amount', $donation->amount);

                $campaign->refresh();

                if ($campaign->current_amount >= $campaign->goal_amount) {
                    $campaign->update(['status' => 'completed']);
                }
            } elseif ($resultCode !== "00" && $donation->status !== 'paid') {

                $donation->update([
                    'status' => 'failed',
                    'failure_reason' => $request->resultMsg ?? 'Payment failed'
                ]);
            }

            return response()->json(['message' => 'Callback processed']);
        });
    }

    public function checkStatus($reference)
    {
        $donation = Donation::where('reference', $reference)->first();

        if (!$donation) {
            return response()->json([
                'message' => 'Donation not found'
            ], 404);
        }

        return response()->json([
            'reference' => $donation->reference,
            'status' => $donation->status,
            'amount' => (float) $donation->amount,
            'payment_method' => $donation->payment_method,
            'duitku_reference' => $donation->duitku_reference,
            'is_paid' => $donation->status === 'paid',
            'is_failed' => $donation->status === 'failed',
            'failure_reason' => $donation->status === 'failed'
                ? $donation->failure_reason
                : null
        ]);
    }
}
