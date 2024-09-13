@extends('layouts.app')

@section('title', 'Budgetify | Register')

@section('content')
    <div class="d-flex justify-content-center pt-3">
        <h1 class="rainbow_text_animated" style="font-weight: bolder; padding: 10px">
            Register Page
        </h1>
    </div>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">Register</h4>
                        <form id="registerForm" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <x-input-label for="name" :value="__('Name')" style="font-weight: bold;"/>
                                <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required
                                    autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div class="form-group mb-3">
                                <x-input-label for="email" :value="__('Email')" style="font-weight: bold;"/>
                                <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')"
                                    required autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div class="form-group mb-3">
                                <x-input-label for="password" :value="__('Password')" style="font-weight: bold;"/>

                                <x-text-input id="password" class="form-control" type="password" name="password" required
                                    autocomplete="new-password" />

                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <div class="form-group mb-3">
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" style="font-weight: bold;"/>

                                <x-text-input id="password_confirmation" class="form-control" type="password"
                                    name="password_confirmation" required autocomplete="new-password" />

                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
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
