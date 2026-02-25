@extends('layouts.main')

@section('content')
    <div class="container">
        <h4>Edit Goals</h4>

        <form action="{{ url('/campaigns/' . $campaigns->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" value="{{ $campaigns->title }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ $campaigns->description }}</textarea>
            </div>

            <div class="mb-3">
                <label>Goal Amount</label>
                <input type="number" name="goal_amount" value="{{ (int) $campaigns->goal_amount }}" class="form-control"
                    min="0" step="1" required>
            </div>

            <button class="btn btn-success">Update</button>
            <a href="{{ url('/campaigns') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection
