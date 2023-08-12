@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Welcome</h1>
        <a id="login" class="btn btn-primary" href="/login">Go to Login</a>
        <a id="register" class="btn btn-secondary" href="/register">Go to Register</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
@endsection