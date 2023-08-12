@extends('layouts.app')

@section('content')
    <div class="container">
    <h2>Registration</h2>
    <form id="registerForm">
        <div class="form-group">
            <label for="registerName">Name</label>
            <input type="name" class="form-control" id="registerName" required>
            <label for="registerEmail">Email</label>
            <input type="email" class="form-control" id="registerEmail" required>
        </div>
        <div class="form-group">
            <label for="registerPassword">Password</label>
            <input type="password" class="form-control" id="registerPassword" required>
            <label for="registerPassword">Confirm Password</label>
            <input type="password" class="form-control" id="registerConfirmPassword" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>

    <script src="register.js"></script>
@endsection