@extends('layouts.app')

@section('content')
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <h1 class="hero-title mb-4">{{ t('app_name') }}</h1>
                <p class="hero-text mb-5">{{ t('welcome_message') }}</p>
                <div class="hero-buttons d-flex flex-wrap gap-3">
                    @auth
                        <a href="{{ Auth::user()->isStudent() ? route('student.dashboard') : route('admin.dashboard') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-speedometer2 me-2"></i> {{ t('dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i> {{ t('login') }}
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-person-plus me-2"></i> {{ t('register') }}
                        </a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <img src="{{ asset('assets/images/quran-hero.png') }}" alt="Quran Study" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Section -->
<div class="container mb-5" style="margin-top: -75px;">
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card text-center h-100 stats-card">
                <div class="card-body">
                    <i class="bi bi-building fs-1 text-primary mb-3"></i>
                    <h3 class="card-title">{{ $stats['departments'] }}</h3>
                    <p class="card-text">{{ t('departments_count') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100 stats-card">
                <div class="card-body">
                    <i class="bi bi-person-workspace fs-1 text-success mb-3"></i>
                    <h3 class="card-title">{{ $stats['teachers'] }}</h3>
                    <p class="card-text">{{ t('teachers_count') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100 stats-card">
                <div class="card-body">
                    <i class="bi bi-people fs-1 text-info mb-3"></i>
                    <h3 class="card-title">{{ $stats['students'] }}</h3>
                    <p class="card-text">{{ t('students_count') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100 stats-card">
                <div class="card-body">
                    <i class="bi bi-book fs-1 text-warning mb-3"></i>
                    <h3 class="card-title">{{ $stats['circles'] }}</h3>
                    <p class="card-text">{{ t('circles_count') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="features-section py-6">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">{{ t('our_features') }}</h2>
            <p class="section-subtitle">{{ t('features_description') }}</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-book"></i>
                    </div>
                    <h3 class="feature-title">{{ t('feature_quran_title') }}</h3>
                    <p class="feature-text">{{ t('feature_quran_description') }}</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3 class="feature-title">{{ t('feature_teachers_title') }}</h3>
                    <p class="feature-text">{{ t('feature_teachers_description') }}</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h3 class="feature-title">{{ t('feature_tracking_title') }}</h3>
                    <p class="feature-text">{{ t('feature_tracking_description') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="cta-section py-6">
    <div class="container">
        <div class="cta-inner">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <h2 class="cta-title">{{ t('cta_title') }}</h2>
                    <p class="cta-text">{{ t('cta_description') }}</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    @auth
                        <a href="{{ Auth::user()->isStudent() ? route('student.dashboard') : route('admin.dashboard') }}" class="btn btn-light btn-lg">
                            <i class="bi bi-speedometer2 me-2"></i> {{ t('dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                            <i class="bi bi-person-plus me-2"></i> {{ t('join_now') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.hero-section {
    padding: 6rem 0;
    background: linear-gradient(135deg, var(--primary-light), #ffffff);
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='52' height='26' viewBox='0 0 52 26' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%231FA959' fill-opacity='0.05'%3E%3Cpath d='M10 10c0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6h2c0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4v2c-3.314 0-6-2.686-6-6 0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6zm25.464-1.95l8.486 8.486-1.414 1.414-8.486-8.486 1.414-1.414z' /%3E%3C/g%3E%3C/svg%3E");
    z-index: 0;
    pointer-events: none; /* Allow clicks through the background */
}

.hero-title {
    font-size: 3rem;
    font-weight: 700;
    color: var(--primary-dark);
    position: relative;
    z-index: 1;
}

.hero-text {
    font-size: 1.25rem;
    color: #555;
    position: relative;
    z-index: 1;
}

.hero-image {
    position: relative;
    z-index: 1;
    animation: float 5s ease-in-out infinite;
}

.hero-buttons {
    position: relative;
    z-index: 2; /* Ensure buttons stay above other elements */
}

.hero-buttons .btn {
    position: relative; /* Reset any inherited positioning */
    transform: translateZ(0); /* Fix for Safari click issues */
}

@keyframes float {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-15px);
    }
    100% {
        transform: translateY(0px);
    }
}

/* Stats Cards Styling */
.stats-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
    border: none;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.card-title {
    font-weight: 700;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.card-text {
    color: #6c757d;
    font-size: 1.1rem;
}

.features-section {
    background-color: #ffffff;
    padding: 5rem 0;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 1rem;
}

.section-subtitle {
    font-size: 1.1rem;
    color: #666;
    max-width: 700px;
    margin: 0 auto;
}

.feature-card {
    background-color: #fff;
    border-radius: 20px;
    padding: 2.5rem;
    height: 100%;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(31, 169, 89, 0.1);
}

.feature-icon {
    width: 80px;
    height: 80px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--primary-light);
    margin-bottom: 1.5rem;
    font-size: 2rem;
    color: var(--primary-color);
}

.feature-title {
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--primary-dark);
    margin-bottom: 1rem;
}

.feature-text {
    color: #666;
    margin-bottom: 0;
}

.cta-section {
    background-color: #f8f9fa;
}

.cta-inner {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border-radius: 20px;
    padding: 4rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.cta-inner::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: var(--header-pattern);
    opacity: 0.1;
    pointer-events: none;
}

.cta-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.cta-text {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

.py-6 {
    padding-top: 5rem;
    padding-bottom: 5rem;
}
</style>
@endpush
