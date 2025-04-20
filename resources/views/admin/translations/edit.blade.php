@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ t('Edit Translation') }}</h5>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.translations.update', $translation) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="language_id" class="form-label">{{ t('Language') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('language_id') is-invalid @enderror" 
                                    id="language_id" name="language_id" required>
                                @foreach($languages as $language)
                                    <option value="{{ $language->id }}" {{ old('language_id', $translation->language_id) == $language->id ? 'selected' : '' }}>
                                        {{ $language->name }} ({{ $language->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('language_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="translation_key" class="form-label">{{ t('Translation Key') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('translation_key') is-invalid @enderror" 
                                   id="translation_key" name="translation_key" value="{{ old('translation_key', $translation->translation_key) }}" required>
                            @error('translation_key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ t('Translation keys should be lowercase with underscores (e.g., welcome_message)') }}</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="translation_value" class="form-label">{{ t('Translation Text') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('translation_value') is-invalid @enderror" 
                                      id="translation_value" name="translation_value" rows="4" required>{{ old('translation_value', $translation->translation_value) }}</textarea>
                            @error('translation_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ t('Use :placeholders for dynamic content (e.g., Welcome, :name!)') }}</div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.translations.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> {{ t('Back') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> {{ t('Update Translation') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 