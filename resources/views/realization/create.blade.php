@extends('layouts.main')

@section('content')
    <h4>Tambah Realisasi Dana</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card shadow-sm p-4">
        <form method="POST" action="{{ route('fund-realizations.store') }}">
            @csrf

            <div class="mb-3">
                <label>Pilih Sumber</label>
                <select name="campaign_id" class="form-control" required>
                    <option value="">-- Pilih Sumber --</option>
                    @foreach ($campaigns as $campaign)
                        <option value="{{ $campaign->id }}">
                            {{ $campaign->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Judul</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Jumlah Harga</label>
                <input type="text" name="amount" id="amountInput" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="in_progress">In Progress</option>
                    <option value="done">Done</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <button class="btn btn-success">Simpan</button>
            <a href="{{ route('fund-realizations.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script>
        document.getElementById('amountInput').addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            this.value = new Intl.NumberFormat('id-ID').format(value);
        });
    </script>
@endsection
