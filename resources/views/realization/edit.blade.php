@extends('layouts.main')

@section('content')
    <h4>Edit Realisasi Dana</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card shadow-sm p-4">
        <form method="POST" action="{{ route('fund-realizations.update', $fundRealization) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Pilih Sumber</label>
                <select name="campaign_id" class="form-control" required>
                    @foreach ($campaigns as $campaign)
                        <option value="{{ $campaign->id }}"
                            {{ $fundRealization->campaign_id == $campaign->id ? 'selected' : '' }}>
                            {{ $campaign->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Judul</label>
                <input type="text" name="title" value="{{ $fundRealization->title }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Jumlah Harga</label>
                <input type="text" name="amount" id="amountInput"
                    value="{{ number_format($fundRealization->amount, 0, ',', '.') }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="in_progress" {{ $fundRealization->status == 'in_progress' ? 'selected' : '' }}>
                        In Progress
                    </option>
                    <option value="done" {{ $fundRealization->status == 'done' ? 'selected' : '' }}>
                        Done
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ $fundRealization->description }}</textarea>
            </div>

            <button class="btn btn-success">Update</button>
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
