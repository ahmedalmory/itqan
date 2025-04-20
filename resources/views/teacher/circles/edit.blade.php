@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ t('edit_circle') }}</h1>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ t('edit_circle_details') }}</h5>
            <a href="{{ route('teacher.circles.show', $circle->id) }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> {{ t('back_to_circle') }}
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('teacher.circles.update', $circle->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">{{ t('circle_name') }}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $circle->name) }}" readonly>
                    <div class="form-text">{{ t('circle_name_managed_by_admin') }}</div>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="meeting_days" class="form-label">{{ t('meeting_days') }}</label>
                        <input type="text" class="form-control @error('meeting_days') is-invalid @enderror" 
                               id="meeting_days" name="meeting_days" value="{{ old('meeting_days', $circle->meeting_days) }}">
                        <div class="form-text">{{ t('meeting_days_help') }}</div>
                        @error('meeting_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="meeting_time" class="form-label">{{ t('meeting_time') }}</label>
                        <input type="text" class="form-control @error('meeting_time') is-invalid @enderror" 
                               id="meeting_time" name="meeting_time" value="{{ old('meeting_time', $circle->meeting_time) }}">
                        <div class="form-text">{{ t('meeting_time_help') }}</div>
                        @error('meeting_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="meeting_link" class="form-label">{{ t('meeting_link') }}</label>
                    <input type="url" class="form-control @error('meeting_link') is-invalid @enderror" 
                           id="meeting_link" name="meeting_link" value="{{ old('meeting_link', $circle->meeting_link) }}">
                    <div class="form-text">{{ t('meeting_link_help') }}</div>
                    @error('meeting_link')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="whatsapp_group" class="form-label">{{ t('whatsapp_group_link') }}</label>
                        <input type="url" class="form-control @error('whatsapp_group') is-invalid @enderror" 
                               id="whatsapp_group" name="whatsapp_group" value="{{ old('whatsapp_group', $circle->whatsapp_group) }}">
                        @error('whatsapp_group')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="telegram_group" class="form-label">{{ t('telegram_group_link') }}</label>
                        <input type="url" class="form-control @error('telegram_group') is-invalid @enderror" 
                               id="telegram_group" name="telegram_group" value="{{ old('telegram_group', $circle->telegram_group) }}">
                        @error('telegram_group')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">{{ t('description') }}</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description', $circle->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('teacher.circles.show', $circle->id) }}" class="btn btn-secondary">
                        {{ t('cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        {{ t('update_circle') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 