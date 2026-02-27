@extends('layouts.main')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>List Goals & Sisa Dana</h1>
            <a href="{{ route('campaigns.create') }}" class="btn btn-primary">
                + Tambah Goals
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Target</th>
                            <th>Terkumpul</th>
                            <th>Realisasi</th>
                            <th>Sisa</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $campaign)
                            <tr>
                                <td>{{ $campaign->title }}</td>

                                <td>
                                    @if ($campaign->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($campaign->status === 'inactive')
                                        <span class="badge bg-secondary">Inactive</span>
                                    @else
                                        <span class="badge bg-primary">Active</span>
                                    @endif
                                </td>

                                <td>Rp {{ number_format($campaign->goal_amount, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($campaign->total_realized, 0, ',', '.') }}</td>

                                <td>
                                    <span
                                        class="fw-bold {{ $campaign->remaining_balance < 0 ? 'text-danger' : 'text-success' }}">
                                        Rp {{ number_format($campaign->remaining_balance, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('campaigns.destroy', $campaign) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Hapus campaign ini?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3">
                                    Belum ada campaign dana
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
