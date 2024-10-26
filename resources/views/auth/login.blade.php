@extends('layouts.app')

@section('content')
    <style>
        body {
            background-color: #000;
            /* Black background */
            color: #fff;
            /* White text */
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(255, 255, 255, 0.1);
            /* Light shadow for contrast */
            background-color: #1a1a1a;
            /* Dark grey card background */
        }

        .card-header {
            background: #333;
            /* Darker header */
            border-bottom: none;
            border-radius: 15px 15px 0 0;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: #ffffff;
            /* White text */
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #444;
            /* Dark grey border */
            background-color: #222;
            /* Dark input background */
            color: #fff;
            /* White text */
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #fff;
            /* White border on focus */
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        }

        .btn-primary {
            background-color: #d30606;
            /* Medium grey button */
            border-color: #ef0101;
            /* Medium grey border */
            border-radius: 10px;
            color: #ffffff;
            /* White text */
        }

        .btn-primary:hover {
            background-color: #777;
            /* Light grey on hover */
            border-color: #777;
            /* Light grey border on hover */
        }

        .form-check-label {
            color: #bbb;
            /* Light grey for labels */
        }

        .text-center {
            margin-top: 15px;
        }

        .invalid-feedback {
            color: red;
            /* Red error messages */
        }
    </style>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf

                            <!-- Email Address -->
                            <div class="form-group mb-3">
                                <label for="email" class="form-label text-white ">Email Address</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-group mb-3">
                                <label for="password" class="form-label text-white ">{{ __('Password') }}</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}" style="color: #bbb;">
                                        Forgot Your Password?
                                    </a>
                                @endif
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
