@extends('layouts.main')

@section('content')
    <div class="container">
        <h4>Create Campaign</h4>

        <form action="{{ url('/campaigns') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>Goal Amount</label>
                <input type="number" name="goal_amount" class="form-control" required>
            </div>

            <button class="btn btn-success">Save</button>
            <a href="{{ url('/campaigns/index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection
