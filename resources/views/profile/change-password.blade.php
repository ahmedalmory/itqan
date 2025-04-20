@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('change_password') }}</h5>
            <a href="{{ route('profile.show') }}" class="btn btn-sm btn-secondary">
                {{ t('back_to_profile') }}
            </a>
        </div>
        <div class="card-body">
            @include('partials.alerts')
            
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="current_password" class="form-label">{{ t('current_password') }}</label>
                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                           id="current_password" name="current_password" required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">{{ t('new_password') }}</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" required minlength="8">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">{{ t('confirm_new_password') }}</label>
                    <input type="password" class="form-control" 
                           id="password_confirmation" name="password_confirmation" required minlength="8">
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">{{ t('update_password') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection 