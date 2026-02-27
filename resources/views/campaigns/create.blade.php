@extends('layouts.main')

@section('content')
    <div class="container">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-0">Create Campaign</h4>
                <small class="text-muted">Tambahkan goals penggalangan dana baru</small>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('campaigns.store') }}" method="POST">
                    @csrf

                    {{-- TITLE --}}
                    <div class="mb-3">
                        <label class="form-label">Judul Campaign</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    {{-- DANA SEKARANG --}}
                    <div class="mb-3">
                        <label class="form-label">Dana Sekarang</label>
                        <input type="number" name="current_amount" value="{{ old('current_amount') }}" class="form-control"
                            min="0" step="1">
                    </div>

                    {{-- GOAL AMOUNT --}}
                    <div class="mb-3">
                        <label class="form-label">Target Dana</label>
                        <input type="number" name="goal_amount" value="{{ old('goal_amount') }}" class="form-control"
                            min="1000" step="1" required>
                    </div>

                    {{-- START DATE --}}
                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" class="form-control"
                            required>
                    </div>

                    {{-- END DATE --}}
                    <div class="mb-3">
                        <label class="form-label">Tanggal Selesai (Opsional)</label>
                        <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" class="form-control">
                    </div>

                    {{-- STATUS --}}
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-success">
                            Simpan Campaign
                        </button>

                        <a href="{{ route('campaigns.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
