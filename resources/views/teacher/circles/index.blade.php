@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ t('my_circles') }}</h1>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ t('my_circles') }}</h5>
            <div>
                <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> {{ t('back_to_dashboard') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($circles->isEmpty())
                <div class="alert alert-info">
                    {{ t('no_circles_found') }}
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($circles as $circle)
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">{{ $circle->name }}</h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        <strong>{{ t('department') }}:</strong> {{ $circle->department->name }}
                                    </p>
                                    <p class="card-text">
                                        <strong>{{ t('circle_time') }}:</strong> {{ str_replace('_', ' ', ucfirst($circle->circle_time)) }}
                                    </p>
                                    <p class="card-text">
                                        <strong>{{ t('students') }}:</strong> 
                                        <span class="badge bg-info">
                                            {{ $circle->students_count ?? $circle->students->count() }}/{{ $circle->max_students }}
                                        </span>
                                    </p>
                                    @if($circle->description)
                                        <p class="card-text">
                                            <strong>{{ t('description') }}:</strong> {{ $circle->description }}
                                        </p>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('teacher.circles.show', $circle->id) }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-eye"></i> {{ t('view') }}
                                        </a>
                                        <a href="{{ route('teacher.circles.students', $circle->id) }}" class="btn btn-info btn-sm">
                                            <i class="bi bi-people"></i> {{ t('students') }}
                                        </a>
                                        <a href="{{ route('teacher.circles.edit', $circle->id) }}" class="btn btn-secondary btn-sm">
                                            <i class="bi bi-pencil"></i> {{ t('edit') }}
                                        </a>
                                    </div>
                                    
                                    <div class="mt-2">
                                        @if($circle->whatsapp_group)
                                            <a href="{{ $circle->whatsapp_group }}" target="_blank" class="btn btn-success btn-sm w-100 mb-1">
                                                <i class="bi bi-whatsapp"></i> {{ t('whatsapp_group') }}
                                            </a>
                                        @endif
                                        
                                        @if($circle->telegram_group)
                                            <a href="{{ $circle->telegram_group }}" target="_blank" class="btn btn-primary btn-sm w-100">
                                                <i class="bi bi-telegram"></i> {{ t('telegram_group') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
