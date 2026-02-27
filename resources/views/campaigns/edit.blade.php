@extends('layouts.main')

@section('content')
    <div class="container">
        <h4>Edit Goals</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('campaigns.update', $campaign) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title', $campaign->title) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ old('description', $campaign->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label>Goal Amount</label>
                <input type="number" name="goal_amount" value="{{ old('goal_amount', (int) $campaign->goal_amount) }}"
                    class="form-control" min="1000" step="1" required>
            </div>

            <button class="btn btn-success">Update</button>
            <a href="{{ route('campaigns.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection
