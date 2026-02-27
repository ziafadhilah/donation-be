@extends('layouts.main')

@section('content')
    <div class="container">

        <h4 class="mb-3">Tambah Realisasi Dana</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="card shadow-sm p-4">
            <form method="POST" action="{{ route('fund-realizations.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Pilih Sumber</label>
                    <select name="campaign_id" class="form-select" required>
                        <option value="">-- Pilih Sumber --</option>
                        @foreach ($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                {{ $campaign->title }}
                                (Sisa: Rp {{ number_format($campaign->remaining_balance, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">
                        Pastikan dana tersedia mencukupi sebelum realisasi.
                    </small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Judul</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jumlah Dana</label>
                    <input type="text" name="amount" id="amountInput" value="{{ old('amount') }}" class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>
                            In Progress
                        </option>
                        <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>
                            Done
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>

                <button class="btn btn-success">Simpan</button>
                <a href="{{ route('fund-realizations.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>

    </div>

    <script>
        document.getElementById('amountInput').addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            this.value = new Intl.NumberFormat('id-ID').format(value);
        });
    </script>
@endsection
