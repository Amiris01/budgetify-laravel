@extends('layouts.app')

@section('title', 'Budgetify | Login')

@section('content')
    <div class="d-flex justify-content-center pt-3">
        <h1 class="rainbow_text_animated" style="font-weight: bolder; padding: 10px">
            Login Page
        </h1>
    </div>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">Login</h4>
                        <form id="loginForm" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <x-input-label for="email" :value="__('Email')" style="font-weight: bold;" />
                                <x-text-input id="email" class="form-control" type="email" name="email"
                                    :value="old('email')" required autofocus autocomplete="username"
                                    placeholder="Enter your email" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                            </div>
                            <div class="form-group mb-3">
                                <x-input-label for="password" :value="__('Password')" style="font-weight: bold;" />

                                <x-text-input id="password" class="form-control" type="password" name="password" required
                                    autocomplete="current-password" placeholder="Enter your password" />

                                <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <!-- <a href="./forgot_password.php" class="text-decoration-none">Forgot Password?</a> -->
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .container {
            max-width: 500px;
        }

        .card {
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .card-body {
            padding: 30px;
        }

        .form-control {
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #6666ff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background-color: #5555dd;
        }
    </style>
@endpush
