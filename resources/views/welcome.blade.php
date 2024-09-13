@extends('layouts.app')

@section('title', 'Budgetify | Main Page')

@section('content')
    <div class="hero-section">
        <h1 class="hero-title rainbow_text_animated">Welcome to Budgetify</h1>
        <p class="hero-description">Your ultimate tool for tracking budgets and inventory with ease.</p>
        <a href="./login.php" class="btn btn-primary btn-primary-custom">Get Started</a>
    </div>

    <div class="container feature-section">
        <div class="row">
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fas fa-chart-line feature-icon"></i>
                    <h3 class="feature-title">Budget Management</h3>
                    <p>Track and manage your budgets effectively.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fas fa-boxes feature-icon"></i>
                    <h3 class="feature-title">Inventory Tracking</h3>
                    <p>Keep an eye on your inventory with real-time updates.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fas fa-chart-pie feature-icon"></i>
                    <h3 class="feature-title">Analytics & Reports</h3>
                    <p>Generate detailed reports and analytics for better decisions.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-section">
        <div class="container text-center">
            <p>&copy; 2024 Budgetify. All Rights Reserved.</p>
            <p>Made by Doofenshmirtz Evil Inc.</p>
        </div>
    </footer>
@endsection

@push('styles')
    <style>
        body {
            background: none;
        }

        .hero-section {
            background: url("{{ asset('images/hero-bg.webp') }}");
            background-size: cover;
            padding: 60px 0;
            color: white;
            text-align: center;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: bolder;
            padding-bottom: 10px;
        }

        .hero-description {
            font-size: 1.5rem;
            margin-top: 15px;
        }

        .btn-primary-custom {
            background-color: #6666ff;
            border-color: #6666ff;
            font-weight: bold;
            padding: 10px 30px;
            margin-top: 20px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary-custom:hover {
            background-color: #5555ff;
            border-color: #5555ff;
        }

        .feature-section {
            padding: 30px 0;
            text-align: center;
        }

        .feature-box {
            padding: 30px;
            margin-top: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .feature-box:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #6666ff;
            margin-bottom: 15px;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .footer-section {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            margin-top: 30px;
        }

        .footer-section p {
            margin: 0;
            font-size: 1rem;
        }

        .footer-section i {
            color: #ff6666;
        }
    </style>
@endpush
