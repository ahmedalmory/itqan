@extends('layouts.app')

@section('content')
<div class="login-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-box">
                    <div class="text-center mb-4">
                        <h1 class="login-title">{{ t('login') }}</h1>
                        <p class="login-subtitle">{{ t('login_welcome_message') }}</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="login-form">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">{{ t('email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required 
                                       value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="password" class="form-label">{{ t('password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">{{ t('login_button') }}</button>
                        </div>

                        <div class="text-center">
                            <p class="mb-0">{{ t('no_account') }} 
                                <a href="{{ route('register') }}">{{ t('register_here') }}</a>
                            </p>
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
.login-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    padding: 2rem 0;
}

.login-box {
    background: white;
    border-radius: 15px;
    padding: 2.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.login-title {
    font-size: 1.75rem;
    color: var(--primary-dark);
    margin-bottom: 0.5rem;
}

.login-subtitle {
    color: #666;
    margin-bottom: 2rem;
}

.login-form .input-group-text {
    background-color: transparent;
    border-right: none;
    color: var(--primary-color);
}

.login-form .form-control {
    border-left: none;
}

.login-form .form-control:focus {
    box-shadow: none;
    border-color: #ced4da;
}

.login-form .btn-primary {
    padding: 0.8rem;
    font-weight: 600;
}

.login-form a {
    color: var(--primary-color);
    text-decoration: none;
}

.login-form a:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}
</style>
@endpush 