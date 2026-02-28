<?php

namespace App\Http\Controllers\Api;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class DonationApiController extends BaseApiController
{
    public function index()
    {
        $donations = Donation::with(['campaign', 'unit'])
            ->latest()
            ->paginate(10);

        return $this->success($donations, 'Donation list');
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_id'  => 'nullable|exists:units,id',
            'unit_qty' => 'nullable|integer|min:1',
            'amount'   => 'nullable|numeric|min:1000',
            'phone'        => 'required|string|min:10|max:15',
            'name'         => 'nullable|string|max:255',
            'email'        => 'nullable|email|max:255',
            'is_anonymous' => 'nullable|boolean',
        ]);

        return DB::transaction(function () use ($request) {

            $campaign = Campaign::where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->lockForUpdate()
                ->firstOrFail();

            $reference = 'DON-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);

            if ($request->unit_id) {

                $unit = Unit::where('id', $request->unit_id)
                    ->where('is_active', true)
                    ->firstOrFail();

                $unitQty = $request->unit_qty;

                $finalAmount = $unit->price * $unitQty;
            } else {

                $unit = null;
                $unitQty = null;
                $finalAmount = $request->amount;
            }

            Donation::create([
                'campaign_id'  => $campaign->id,
                'unit_id'      => $unit?->id,
                'unit_qty'     => $unitQty,
                'name'         => $request->boolean('is_anonymous') ? 'Anonim' : $request->name,
                'is_anonymous' => $request->boolean('is_anonymous'),
                'phone'        => $request->phone,
                'email'        => $request->email,
                'amount'       => $finalAmount,
                'reference'    => $reference,
                'status'       => 'pending'
            ]);

            return $this->success([
                'reference' => $reference,
                'amount'    => $finalAmount
            ], 'Donation created');
        });
    }

    public function pay(Request $request, $reference)
    {
        $methods = array_keys(config('payment.methods'));

        $request->validate([
            'payment_method' => ['required', Rule::in($methods)]
        ]);

        return DB::transaction(function () use ($request, $reference) {

            $donation = Donation::where('reference', $reference)
                ->lockForUpdate()
                ->first();

            if (!$donation) {
                return $this->error('Donation not found', null, 404);
            }

            if ($donation->status !== 'pending') {
                return $this->error('Donation already processed', null, 400);
            }

            if ($donation->expired_at && now()->greaterThan($donation->expired_at)) {
                $donation->update([
                    'status' => 'expired'
                ]);

                return $this->error('Donation expired', null, 400);
            }

            $donation->update([
                'payment_method' => $request->payment_method
            ]);

            $merchantCode = config('duitku.merchant_code');
            $apiKey       = config('duitku.api_key');
            $amount       = (int) $donation->amount;

            $signature = md5($merchantCode . $reference . $amount . $apiKey);

            $endpoint = config('duitku.sandbox')
                ? 'https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry'
                : 'https://passport.duitku.com/webapi/api/merchant/v2/inquiry';

            $response = Http::post($endpoint, [
                'merchantCode'   => $merchantCode,
                'paymentAmount'  => $amount,
                'merchantOrderId' => $reference,
                'paymentMethod'  => $donation->payment_method,
                'productDetails' => 'Donasi Gereja',
                'customerEmail'  => $donation->email ?? 'donasi@email.com',
                'customerPhone'  => $donation->phone,
                'callbackUrl'    => config('app.url') . '/api/duitku/callback',
                'returnUrl'      => env('FRONTEND_URL') . '/countdown?reference=' . $reference,
                'signature'      => $signature
            ]);

            if (!$response->successful()) {
                return $this->error('Failed request to gateway', null, 500);
            }

            $data = $response->json();

            $donation->update([
                'payment_url'     => $data['paymentUrl'] ?? null,
                'gateway_payload' => $data,
                'expired_at'      => $donation->expired_at ?? now()->addMinutes(10)
            ]);

            return $this->success([
                'payment_url' => $data['paymentUrl'] ?? null
            ], 'Redirect to payment');
        });
    }


    public function callback(Request $request)
    {
        return DB::transaction(function () use ($request) {

            $merchantCode = config('duitku.merchant_code');
            $apiKey       = config('duitku.api_key');

            $reference       = $request->merchantOrderId;
            $amount          = (int) $request->input('amount');
            $resultCode      = $request->resultCode;
            $signature       = $request->signature;
            $duitkuReference = $request->reference;

            $donation = Donation::where('reference', $reference)
                ->lockForUpdate()
                ->first();

            if (!$donation) {
                return $this->error('Donation not found', null, 404);
            }

            if ($donation->status === 'paid') {
                return $this->success(null, 'Already processed');
            }

            if ((int)$donation->amount !== $amount) {
                return $this->error('Invalid amount', null, 400);
            }

            $expectedSignature = md5($merchantCode . $amount . $reference . $apiKey);

            if ($signature !== $expectedSignature) {
                return $this->error('Invalid signature', null, 403);
            }

            if ($donation->expired_at && now()->greaterThan($donation->expired_at)) {
                $donation->update([
                    'status' => 'expired'
                ]);

                return $this->error('Donation expired', null, 400);
            }

            $donation->update([
                'duitku_reference' => $duitkuReference,
                'callback_payload' => $request->all()
            ]);

            if ($resultCode === "00") {

                $donation->update([
                    'status'  => 'paid',
                    'paid_at' => now()
                ]);

                $campaign = $donation->campaign()->lockForUpdate()->first();
                $campaign->increment('current_amount', $donation->amount);

                if ($campaign->current_amount >= $campaign->goal_amount) {
                    $campaign->update(['status' => 'completed']);
                }
            } else {

                $donation->update([
                    'status' => 'failed',
                    'failure_reason' => $request->resultMsg ?? 'Payment failed'
                ]);
            }

            return $this->success(null, 'Callback processed');
        });
    }

    public function checkStatus($reference)
    {
        $donation = Donation::where('reference', $reference)->first();

        if (!$donation) {
            return $this->error('Donation not found', null, 404);
        }

        if (
            $donation->status === 'pending' &&
            $donation->expired_at &&
            now()->greaterThan($donation->expired_at)
        ) {
            $donation->update([
                'status' => 'expired'
            ]);
        }

        return $this->success([
            'reference'      => $donation->reference,
            'status'         => $donation->status,
            'amount'         => (float) $donation->amount,
            'paid_at'        => $donation->paid_at,
            'expired_at'     => $donation->expired_at,
            'created_at'     => $donation->created_at,
            'unit'           => $donation->unit?->name,
            'unit_qty'       => $donation->unit_qty,
            'name'           => $donation->name,
            'phone'          => $donation->phone,
            'payment_method' => $donation->payment_method,
            'gateway_payload' => $donation->gateway_payload,
        ], 'Donation status');
    }
}
