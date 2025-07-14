@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('supervisor.tasks.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
                <i class="bi bi-arrow-left"></i> {{ t('Back to Tasks') }}
            </a>
            <h1>
                {{ $assignment->task->title }}
                @if($assignment->task->is_recurring)
                    <span class="badge bg-info ms-1">{{ t('Recurring') }}</span>
                @endif
                @if($assignment->assigned_date->isToday())
                    <span class="badge bg-primary ms-1">{{ t('Today') }}</span>
                @endif
            </h1>
            <div class="row">
                <div class="col-md-6">
                    <h6>{{ t('Assignment Date') }}</h6>
                    <p>{{ $assignment->assigned_date->format('Y-m-d') }}</p>
                </div>
                <div class="col-md-6">
                    <h6>{{ t('Priority') }}</h6>
                    <p>
                        <span class="badge bg-{{ $assignment->task->priority == 'urgent' ? 'danger' : ($assignment->task->priority == 'high' ? 'warning' : ($assignment->task->priority == 'medium' ? 'info' : 'secondary')) }}">
                            {{ ucfirst($assignment->task->priority) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Details -->
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ t('Task Details') }}</h5>
        </div>
        <div class="card-body">
            @if($assignment->task->description)
                <div class="mb-3">
                    <h6>{{ t('Description') }}</h6>
                    <p>{{ $assignment->task->description }}</p>
                </div>
            @endif

            <div class="row">
                <div class="col-md-4">
                    <h6>{{ t('Due Date') }}</h6>
                    <p>{{ $assignment->task->due_date ? $assignment->task->due_date->format('Y-m-d') : t('No due date') }}</p>
                </div>
                <div class="col-md-4">
                    <h6>{{ t('Status') }}</h6>
                    <p>
                        @if($assignment->completion)
                            <span class="badge bg-success">{{ t('Completed') }}</span>
                            <br>
                            <small class="text-muted">{{ $assignment->completion->completed_at->format('Y-m-d H:i') }}</small>
                        @else
                            <span class="badge bg-warning">{{ t('Pending') }}</span>
                        @endif
                    </p>
                </div>
                @if($assignment->task->is_recurring)
                    <div class="col-md-4">
                        <h6>{{ t('Recurring Days') }}</h6>
                        <p>
                            @php
                                $days = json_decode($assignment->task->recurring_days, true);
                                $dayNames = ['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'];
                            @endphp
                            @if($days)
                                @foreach($days as $day)
                                    <span class="badge bg-secondary me-1">{{ $dayNames[$day] ?? $day }}</span>
                                @endforeach
                            @endif
                        </p>
                    </div>
                @endif
            </div>

            @if($assignment->task->instructions)
                <div class="mt-3">
                    <h6>{{ t('Instructions') }}</h6>
                    <div class="alert alert-info">
                        {{ $assignment->task->instructions }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Completion Section -->
    @if(!$assignment->completion)
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ t('Complete Task') }}</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ t('Mark this task as complete when you have finished it.') }}</p>
                
                @if($assignment->assigned_date->isToday())
                    <form action="{{ route('supervisor.tasks.complete', $assignment) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="notes" class="form-label">{{ t('Completion Notes') }} <span class="text-muted">({{ t('Optional') }})</span></label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="{{ t('Add any comments or notes about completing this task...') }}"></textarea>
                            <div class="form-text">{{ t('Maximum 1000 characters') }}</div>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i> {{ t('Mark as Complete') }}
                        </button>
                    </form>
                @else
                    <p class="text-muted">{{ t('This task can only be completed on its assigned date.') }}</p>
                @endif
            </div>
        </div>
    @else
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">{{ t('Task Completed') }}</h5>
            </div>
            <div class="card-body">
                <p><strong>{{ t('Completed at') }}:</strong> {{ $assignment->completion->completed_at->format('Y-m-d H:i') }}</p>
                <p><strong>{{ t('Completed by') }}:</strong> {{ $assignment->completion->completedBy->name }}</p>
                
                @if($assignment->completion->notes)
                    <div class="mt-3">
                        <h6>{{ t('Completion Notes') }}</h6>
                        <div class="alert alert-light">
                            {{ $assignment->completion->notes }}
                        </div>
                    </div>
                @endif

                <form action="{{ route('supervisor.tasks.uncomplete', $assignment) }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-x-lg me-1"></i> {{ t('Mark as Incomplete') }}
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection 