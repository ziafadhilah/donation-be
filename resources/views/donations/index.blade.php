@extends('layouts.main')

@section('content')
    <h1>Daftar Donasi</h1>
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('donations.index') }}">
                <div class="row g-2">

                    <div class="col-md-2">
                        <select name="status" class="form-control">
                            <option value="">-- Status --</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="campaign_id" class="form-control">
                            <option value="">-- Goals --</option>
                            @foreach ($campaigns as $campaign)
                                <option value="{{ $campaign->id }}"
                                    {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                    {{ $campaign->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="col-md-2">
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                    </div> --}}

                    <div class="col-md-2">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Search...">
                    </div>

                    <div class="col-md-1 d-grid">
                        <button class="btn btn-primary">
                            Filter
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Reference</th>
                        <th>Goals</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Visibility</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($donations as $donation)
                        <tr>
                            <td>{{ $donation->reference }}</td>
                            <td>{{ $donation->campaign->title }}</td>
                            <td>{{ $donation->name ?? 'Hamba Tuhan' }}</td>
                            <td>{{ $donation->masked_phone }}</td>
                            <td>{{ $donation->masked_email }}</td>
                            <td>{{ $donation->payment_method }}</td>
                            <td>Rp {{ number_format($donation->amount, 0, ',', '.') }}</td>
                            <td>
                                @if ($donation->status == 'paid')
                                    <span style="color:green;">PAID</span>
                                @elseif($donation->status == 'pending')
                                    <span style="color:orange;">PENDING</span>
                                @else
                                    <span style="color:red;">FAILED</span>
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
                                @if ($donation->status !== 'paid')
                                    <form method="POST" action="{{ route('donations.forceSuccess', $donation) }}"
                                        style="display:inline;">
                                        @csrf
                                        @method('PATCH')

                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Force success donation ini?')">
                                            Force Success
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $donations->links() }}
    </div>
@endsection
