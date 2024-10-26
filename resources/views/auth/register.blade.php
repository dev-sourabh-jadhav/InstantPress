@extends('layouts.app')

@section('content')
    <div class="container ">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h2 class="mb-0">Register</h2>
                    </div>
                    <div class="card-body">
                        <form id="userForm" action="{{ route('storeusers') }}" method="POST">
                            @csrf <!-- CSRF protection -->

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">User Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                        <input type="text" class="form-control" id="phone" name="phone" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                        <input type="text" class="form-control" id="country" name="country" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="state" class="form-label">State</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                        <input type="text" class="form-control" id="state" name="state" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-house"></i></span>
                                        <input type="text" class="form-control" id="city" name="city" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="pincode" class="form-label">Pincode</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="text" class="form-control" id="pincode" name="pincode" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                        <select class="form-select" id="gender" name="gender" required>
                                            <option value="" disabled selected>Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="dob" class="form-label">Date of Birth</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                        <input type="date" class="form-control" id="dob" name="dob" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="subscription_type" class="form-label">Subscription Type</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-card-list"></i></span>
                                        <input type="text" class="form-control" id="subscription_type" name="subscription_type" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="starter" class="form-label">Starter</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-star"></i></span>
                                        <input type="text" class="form-control" id="starter" name="starter" required>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="bi bi-save"></i> Add User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-label {
            color: #495057;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .card {
            border-radius: 1rem;
        }

        .input-group-text {
            background-color: #e9ecef; /* Light gray background for icons */
            border: 1px solid #ced4da; /* Border similar to input */
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

@endsection
