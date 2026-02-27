@extends('layouts.main')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Daftar Donasi</h1>
        </div>

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- FILTER CARD --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('donations.index') }}">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">-- Status --</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select name="campaign_id" class="form-select">
                                <option value="">-- Goals --</option>
                                @foreach ($campaigns as $campaign)
                                    <option value="{{ $campaign->id }}"
                                        {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                        {{ $campaign->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-5">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Reference / Nama / HP">
                        </div>

                        <div class="col-md-1 d-grid">
                            <button class="btn btn-outline-primary">
                                Filter
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Reference</th>
                            <th>Tanggal</th>
                            <th>Goals</th>
                            <th>Donatur</th>
                            <th>HP</th>
                            <th>Unit</th>
                            <th>Metode</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Visibility</th>
                            <th width="140">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($donations as $donation)
                            <tr>
                                <td>
                                    <strong>{{ $donation->reference }}</strong>
                                </td>

                                <td>
                                    {{ $donation->created_at->format('d M Y H:i') }}
                                </td>

                                <td>
                                    {{ $donation->campaign->title }}
                                </td>

                                <td>
                                    <div>
                                        <strong>{{ $donation->name ?? 'Hamba Tuhan' }}</strong><br>
                                        <small class="text-muted">
                                            {{ $donation->masked_phone }}
                                            @if ($donation->email)
                                                • {{ $donation->masked_email }}
                                            @endif
                                        </small>
                                    </div>
                                </td>

                                <td>
                                    {{ $donation->masked_phone }}
                                </td>

                                <td>
                                    @if ($donation->unit)
                                        <span class="badge bg-info">
                                            {{ $donation->unit->name }}
                                            ({{ $donation->unit_qty }}x)
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $donation->payment_method ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <strong>
                                        Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                    </strong>
                                </td>

                                <td>
                                    @if ($donation->status == 'paid')
                                        <span class="badge bg-success">PAID</span>
                                    @elseif($donation->status == 'pending')
                                        <span class="badge bg-warning text-dark">PENDING</span>
                                    @elseif($donation->status == 'expired')
                                        <span class="badge bg-dark">EXPIRED</span>
                                    @else
                                        <span class="badge bg-danger">FAILED</span>
                                    @endif
                                </td>

                                <td>
                                    <form method="POST" action="{{ route('donations.toggleVisibility', $donation) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            class="btn btn-sm {{ $donation->is_visible ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                            {{ $donation->is_visible ? 'Hide' : 'Show' }}
                                        </button>
                                    </form>
                                </td>

                                <td>
                                    @if ($donation->status !== 'paid' && $donation->status !== 'failed')
                                        <form method="POST" action="{{ route('donations.forceSuccess', $donation) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Force success donation ini?')">
                                                Force
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    Belum ada data donasi
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $donations->links() }}
        </div>

    </div>
@endsection
