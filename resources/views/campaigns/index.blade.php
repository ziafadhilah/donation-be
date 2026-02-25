@extends('layouts.main')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between mb-3">
            <h1>List Goals & Sisa Dana</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ url('/campaigns/create') }}" class="btn btn-outline-primary mb-3">
            + Tambah Goals
        </a>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Judul Goals</th>
                            <th>Target Dana</th>
                            <th>Dana Terkumpul</th>
                            <th>Total Realisasi</th>
                            <th>Sisa Dana</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($campaigns as $campaign)
                            <tr>
                                <td>{{ $campaign->title }}</td>

                                <td>
                                    Rp {{ number_format($campaign->goal_amount, 0, ',', '.') }}
                                </td>

                                <td>
                                    Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}
                                </td>

                                <td>
                                    Rp {{ number_format($campaign->total_realized, 0, ',', '.') }}
                                </td>

                                <td>
                                    @if ($campaign->remaining_balance < 0)
                                        <span class="text-danger fw-bold">
                                        @else
                                            <span class="text-success fw-bold">
                                    @endif
                                    Rp {{ number_format($campaign->remaining_balance, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ url('/campaigns/' . $campaign->id . '/edit') }}"
                                        class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ url('/campaigns', $campaign->id) }}" method="POST" class="d-inline">
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
                                <td colspan="6" class="text-center py-3">
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
