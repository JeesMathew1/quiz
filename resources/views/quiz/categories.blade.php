@extends('layouts.app')

@section('content')
    <div class="container text-center my-5">
        <h1 class="display-4">Select a Category</h1>
        <div class="category-container my-4">
            @foreach($categories as $category)
            <div class="category-item">
                <a href="{{ route('quiz', ['category' => $category]) }}" class="btn btn-primary">{{ $category }}</a>
            </div>
            @endforeach
        </div>
    </div>
@endsection

