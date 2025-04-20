@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ t('Edit Language') }}: {{ $language->name }}</h5>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.languages.update', $language) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ t('Language Name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $language->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="code" class="form-label">{{ t('Language Code') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code', $language->code) }}" 
                                   required maxlength="5" {{ in_array($language->code, ['en', 'ar']) ? 'readonly' : '' }}>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(in_array($language->code, ['en', 'ar']))
                                <div class="form-text text-warning">{{ t('Default language codes cannot be changed') }}</div>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="direction" class="form-label">{{ t('Text Direction') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('direction') is-invalid @enderror" 
                                    id="direction" name="direction" required>
                                <option value="ltr" {{ old('direction', $language->direction) == 'ltr' ? 'selected' : '' }}>{{ t('Left to Right (LTR)') }}</option>
                                <option value="rtl" {{ old('direction', $language->direction) == 'rtl' ? 'selected' : '' }}>{{ t('Right to Left (RTL)') }}</option>
                            </select>
                            @error('direction')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input @error('is_active') is-invalid @enderror" 
                                       type="checkbox" value="1" id="is_active" name="is_active" 
                                       {{ old('is_active', $language->is_active) ? 'checked' : '' }}
                                       {{ in_array($language->code, ['en', 'ar']) ? 'disabled' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    {{ t('Active') }}
                                </label>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if(in_array($language->code, ['en', 'ar']))
                                    <div class="form-text text-warning">{{ t('Default languages must remain active') }}</div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.languages.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> {{ t('Back') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> {{ t('Update Language') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 