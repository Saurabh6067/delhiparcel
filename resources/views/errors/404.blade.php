@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">404 - Page Not Found</div>

                <div class="card-body">
                    <h3>Oops! The page you're looking for doesn't exist.</h3>
                    <p>Please check the URL or go back to the homepage.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary">Go to Homepage</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection