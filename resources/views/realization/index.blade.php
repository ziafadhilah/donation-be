@extends('layouts.main')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Realisasi Dana</h4>
            <a href="{{ route('fund-realizations.create') }}" class="btn btn-primary">
                + Tambah Realisasi
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Sumber Dana</th>
                            <th>Judul</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($realizations as $item)
                            <tr>
                                <td>
                                    {{ $item->created_at->format('d M Y') }}
                                </td>

                                <td>
                                    <strong>{{ $item->campaign->title }}</strong><br>
                                    <small class="text-muted">
                                        Sisa: Rp {{ number_format($item->campaign->remaining_balance, 0, ',', '.') }}
                                    </small>
                                </td>

                                <td>{{ $item->title }}</td>

                                <td>
                                    <strong>
                                        Rp {{ number_format($item->amount, 0, ',', '.') }}
                                    </strong>
                                </td>

                                <td>
                                    @if ($item->status == 'done')
                                        <span class="badge bg-success">Done</span>
                                    @else
                                        <span class="badge bg-warning text-dark">In Progress</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('fund-realizations.edit', $item) }}" class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('fund-realizations.destroy', $item) }}"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Hapus realisasi ini?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    Belum ada realisasi dana
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

    </div>
@endsection
