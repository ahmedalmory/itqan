@extends('layouts.app')

@section('content')
<div class="register-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="register-box">
                    <div class="text-center mb-4">
                        <h1 class="register-title">{{ t('register') }}</h1>
                        <p class="register-subtitle">{{ t('register_welcome_message') }}</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="register-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">{{ t('name') }}</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required 
                                           value="{{ old('name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">{{ t('email') }}</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required 
                                           value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">{{ t('password') }}</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="password_confirmation" class="form-label">{{ t('confirm_password') }}</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">{{ t('phone') }}</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" required 
                                           value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="age" class="form-label">{{ t('age') }}</label>
                                    <input type="number" class="form-control @error('age') is-invalid @enderror" id="age" name="age" required 
                                           value="{{ old('age') }}" min="5" max="100">
                                    @error('age')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="gender" class="form-label">{{ t('gender') }}</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                        <option value="">{{ t('select_gender') }}</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ t('male') }}</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ t('female') }}</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="preferred_time" class="form-label">{{ t('preferred_time') }}</label>
                                    <select class="form-select @error('preferred_time') is-invalid @enderror" id="preferred_time" name="preferred_time">
                                        <option value="">{{ t('select_preferred_time') }}</option>
                                        <option value="after_fajr" {{ old('preferred_time') == 'after_fajr' ? 'selected' : '' }}>{{ t('after_fajr') }}</option>
                                        <option value="after_dhuhr" {{ old('preferred_time') == 'after_dhuhr' ? 'selected' : '' }}>{{ t('after_dhuhr') }}</option>
                                        <option value="after_asr" {{ old('preferred_time') == 'after_asr' ? 'selected' : '' }}>{{ t('after_asr') }}</option>
                                        <option value="after_maghrib" {{ old('preferred_time') == 'after_maghrib' ? 'selected' : '' }}>{{ t('after_maghrib') }}</option>
                                        <option value="after_isha" {{ old('preferred_time') == 'after_isha' ? 'selected' : '' }}>{{ t('after_isha') }}</option>
                                    </select>
                                    @error('preferred_time')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="country_id" class="form-label">{{ t('country') }}</label>
                            <select class="form-select @error('country_id') is-invalid @enderror" id="country_id" name="country_id">
                                <option value="">{{ t('select_country') }}</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                        {{ app()->getLocale() == 'ar' ? $country->alt_name : $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">{{ t('register_button') }}</button>
                        </div>

                        <div class="text-center mt-3">
                            <p class="mb-0">{{ t('already_have_account') }} 
                                <a href="{{ route('login') }}">{{ t('login_here') }}</a>
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
.register-page {
    min-height: 100vh;
    padding: 2rem 0;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
}

.register-box {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.register-title {
    font-size: 1.75rem;
    color: var(--primary-dark);
    margin-bottom: 0.5rem;
}

.register-subtitle {
    color: #666;
    margin-bottom: 2rem;
}

.register-form .form-control,
.register-form .form-select {
    padding: 0.75rem 1rem;
    border-radius: 8px;
}

.register-form .btn-primary {
    padding: 0.8rem;
    font-weight: 600;
}

.register-form a {
    color: var(--primary-color);
    text-decoration: none;
}

.register-form a:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}
</style>
@endpush 