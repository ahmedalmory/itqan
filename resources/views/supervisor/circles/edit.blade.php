@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ t('Edit Circle') }}: {{ $circle->name }}</h5>
                    <a href="{{ route('supervisor.circles.show', $circle) }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> {{ t('Back to Circle') }}
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('supervisor.circles.update', $circle) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ t('Circle Name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $circle->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ t('Description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $circle->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="teacher_id" class="form-label">{{ t('Teacher') }}</label>
                            <select class="form-select @error('teacher_id') is-invalid @enderror" id="teacher_id" name="teacher_id">
                                <option value="">{{ t('Select Teacher') }}</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $circle->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }} ({{ $teacher->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="whatsapp_group" class="form-label">{{ t('WhatsApp Group Link') }}</label>
                                    <input type="text" class="form-control @error('whatsapp_group') is-invalid @enderror" id="whatsapp_group" name="whatsapp_group" value="{{ old('whatsapp_group', $circle->whatsapp_group) }}">
                                    @error('whatsapp_group')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telegram_group" class="form-label">{{ t('Telegram Group Link') }}</label>
                                    <input type="text" class="form-control @error('telegram_group') is-invalid @enderror" id="telegram_group" name="telegram_group" value="{{ old('telegram_group', $circle->telegram_group) }}">
                                    @error('telegram_group')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="circle_time" class="form-label">{{ t('Circle Time') }}</label>
                            <input type="text" class="form-control @error('circle_time') is-invalid @enderror" id="circle_time" name="circle_time" value="{{ old('circle_time', $circle->circle_time) }}">
                            <div class="form-text">{{ t('Example: Every Sunday and Wednesday, 8:00 PM - 9:30 PM') }}</div>
                            @error('circle_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> {{ t('Update Circle') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 