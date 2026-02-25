@extends('layouts.main')

@section('content')
    <div class="container-fluid">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h4 mb-0">Dashboard</h1>
                <small class="text-muted">Overview of donations</small>
            </div>
        </div>

        {{-- SUMMARY CARDS --}}
        <div class="row g-3 mb-4">

            {{-- Total Campaign --}}
            <div class="col-6 col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Campaign</small>
                        <div class="h5 mb-0">
                            {{ $totalCampaigns }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Dana Masuk --}}
            <div class="col-6 col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Dana Masuk</small>
                        <div class="h5 mb-0 text-success">
                            Rp {{ number_format($totalAmount, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Realisasi --}}
            <div class="col-6 col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Realisasi</small>
                        <div class="h5 mb-0 text-warning">
                            Rp {{ number_format($totalRealized, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Sisa Dana --}}
            <div class="col-6 col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Sisa Dana</small>
                        <div class="h5 mb-0 text-primary">
                            Rp {{ number_format($totalRemaining, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- RECENT DONATIONS --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Recent Donations</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Donor</th>
                            <th>Campaign</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($recentDonations as $donation)
                            <tr>
                                <td>{{ $donation->name ?? 'Anonymous' }}</td>

                                <td>
                                    {{ $donation->campaign->title ?? '-' }}
                                </td>

                                <td>
                                    Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                </td>

                                <td>
                                    @if ($donation->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($donation->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $donation->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3">
                                    No donations yet
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
