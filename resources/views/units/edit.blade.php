@extends('layouts.main')

@section('content')
    <div class="container">

        <div class="mb-4">
            <h4>Edit Unit</h4>
            <small class="text-muted">Ubah detail unit</small>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('units.update', $unit) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Kode Unit</label>
                        <input type="text" name="code" value="{{ old('code', $unit->code) }}" class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Unit</label>
                        <input type="text" name="name" value="{{ old('name', $unit->name) }}" class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="price" id="priceInput"
                            value="{{ old('price', number_format($unit->price, 0, ',', '.')) }}" class="form-control"
                            required>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input"
                            {{ old('is_active', $unit->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label">
                            Active
                        </label>
                    </div>
                    <button class="btn btn-success">Update</button>
                    <a href="{{ route('units.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>

    </div>

    <script>
        document.getElementById('priceInput').addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            this.value = new Intl.NumberFormat('id-ID').format(value);
        });
    </script>
@endsection
