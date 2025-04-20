@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('my_profile') }}</h5>
            <a href="{{ route('password.change') }}" class="btn btn-sm btn-primary">
                {{ t('change_password') }}
            </a>
        </div>
        <div class="card-body">
            @include('partials.alerts')
            
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">{{ t('name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label">{{ t('email') }}</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="phone" class="form-label">{{ t('phone') }}</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="country_id" class="form-label">{{ t('country') }}</label>
                        <select class="form-select @error('country_id') is-invalid @enderror" 
                                id="country_id" name="country_id" required>
                            <option value="">{{ t('select_country') }}</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" 
                                    {{ old('country_id', $user->country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="age" class="form-label">{{ t('age') }}</label>
                        <input type="number" class="form-control @error('age') is-invalid @enderror" 
                               id="age" name="age" value="{{ old('age', $user->age) }}" min="5" max="100" required>
                        @error('age')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">{{ t('gender') }}</label>
                        <div class="d-flex">
                            <div class="form-check me-4">
                                <input class="form-check-input" type="radio" name="gender" id="gender_male" 
                                       value="male" {{ old('gender', $user->gender) == 'male' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="gender_male">
                                    {{ t('male') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="gender_female" 
                                       value="female" {{ old('gender', $user->gender) == 'female' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="gender_female">
                                    {{ t('female') }}
                                </label>
                            </div>
                        </div>
                        @error('gender')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">{{ t('save_changes') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection