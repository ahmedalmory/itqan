@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ $circle->name }}</h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ t('circle_details') }}</h5>
                    <div>
                        <a href="{{ route('teacher.circles.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> {{ t('back_to_circles') }}
                        </a>
                        <a href="{{ route('teacher.circles.edit', $circle->id) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil"></i> {{ t('edit') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>{{ t('department') }}:</strong> {{ $circle->department->name }}</p>
                            <p><strong>{{ t('circle_time') }}:</strong> {{ str_replace('_', ' ', ucfirst($circle->circle_time)) }}</p>
                            <p><strong>{{ t('max_students') }}:</strong> {{ $circle->max_students }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>{{ t('meeting_days') }}:</strong> {{ $circle->meeting_days ?? t('not_specified') }}</p>
                            <p><strong>{{ t('meeting_time') }}:</strong> {{ $circle->meeting_time ?? t('not_specified') }}</p>
                            <p><strong>{{ t('meeting_link') }}:</strong> 
                                @if($circle->meeting_link)
                                    <a href="{{ $circle->meeting_link }}" target="_blank">{{ t('join_meeting') }}</a>
                                @else
                                    {{ t('not_available') }}
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($circle->description)
                        <div class="mt-3">
                            <h6>{{ t('description') }}</h6>
                            <p>{{ $circle->description }}</p>
                        </div>
                    @endif

                    <div class="mt-3">
                        <h6>{{ t('communication_channels') }}</h6>
                        <div class="d-flex gap-2">
                            @if($circle->whatsapp_group)
                                <a href="{{ $circle->whatsapp_group }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="bi bi-whatsapp"></i> {{ t('whatsapp_group') }}
                                </a>
                            @endif
                            
                            @if($circle->telegram_group)
                                <a href="{{ $circle->telegram_group }}" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="bi bi-telegram"></i> {{ t('telegram_group') }}
                                </a>
                            @endif

                            @if(!$circle->whatsapp_group && !$circle->telegram_group)
                                <p class="text-muted">{{ t('no_communication_channels') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ t('circle_statistics') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light mb-3">
                                <div class="card-body text-center">
                                    <h3 class="card-title">{{ $students->count() }}</h3>
                                    <p class="card-text">{{ t('enrolled_students') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light mb-3">
                                <div class="card-body text-center">
                                    <h3 class="card-title">{{ $circle->max_students - $students->count() }}</h3>
                                    <p class="card-text">{{ t('available_slots') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <h6>{{ t('enrollment_status') }}</h6>
                        <div class="progress">
                            @php
                                $percentage = $circle->max_students > 0 ? round(($students->count() / $circle->max_students) * 100) : 0;
                            @endphp
                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"
                                 aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $percentage }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ t('quick_actions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher.circles.students', $circle->id) }}" class="btn btn-primary">
                            <i class="bi bi-people"></i> {{ t('manage_students') }}
                        </a>
                        <a href="{{ route('teacher.daily-reports.index', ['circle_id' => $circle->id]) }}" class="btn btn-success">
                            <i class="bi bi-journal-check"></i> {{ t('manage_daily_reports') }}
                        </a>
                        <a href="{{ route('teacher.points.index', ['circle_id' => $circle->id]) }}" class="btn btn-info">
                            <i class="bi bi-trophy"></i> {{ t('manage_points') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ t('circle_supervisor') }}</h5>
                </div>
                <div class="card-body">
                    @if($circle->supervisor)
                        <div class="d-flex align-items-center">
                            @if($circle->supervisor->profile_photo)
                                <img src="{{ asset('storage/' . $circle->supervisor->profile_photo) }}" 
                                     class="rounded-circle me-3" style="width: 50px; height: 50px;" 
                                     alt="{{ $circle->supervisor->name }}">
                            @else
                                <div class="bg-secondary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px; color: white;">
                                    {{ substr($circle->supervisor->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-0">{{ $circle->supervisor->name }}</h6>
                                <p class="text-muted mb-0">{{ $circle->supervisor->email }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">{{ t('no_supervisor_assigned') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 