@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('department-admin.tasks.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
                <i class="bi bi-arrow-left"></i> {{ t('Back to Tasks') }}
            </a>
            <h1>{{ $task->title }}</h1>
            <div class="row">
                <div class="col-md-6">
                    <h6>{{ t('Department') }}</h6>
                    <p>{{ $task->department->name ?? t('All Departments') }}</p>
                </div>
                <div class="col-md-6">
                    <h6>{{ t('Priority') }}</h6>
                    <p>
                        <span class="badge bg-{{ $task->priority == 'urgent' ? 'danger' : ($task->priority == 'high' ? 'warning' : ($task->priority == 'medium' ? 'info' : 'secondary')) }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <div>
            <span class="badge bg-{{ $task->status == 'active' ? 'success' : ($task->status == 'completed' ? 'primary' : 'secondary') }} fs-6">
                {{ ucfirst($task->status) }}
            </span>
        </div>
    </div>

    <!-- Task Details -->
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ t('Task Details') }}</h5>
        </div>
        <div class="card-body">
            @if($task->description)
                <div class="mb-3">
                    <h6>{{ t('Description') }}</h6>
                    <p>{{ $task->description }}</p>
                </div>
            @endif

            <div class="row">
                <div class="col-md-4">
                    <h6>{{ t('Created At') }}</h6>
                    <p>{{ $task->created_at->format('Y-m-d H:i') }}</p>
                </div>
                <div class="col-md-4">
                    <h6>{{ t('Due Date') }}</h6>
                    <p>{{ $task->due_date ? $task->due_date->format('Y-m-d') : t('No due date') }}</p>
                </div>
                <div class="col-md-4">
                    <h6>{{ t('Created By') }}</h6>
                    <p>{{ $task->creator->name }}</p>
                </div>
            </div>

            @if($task->is_recurring)
                <div class="mt-3">
                    <h6>{{ t('Recurring Schedule') }}</h6>
                    <p>
                        @php
                            $days = json_decode($task->recurring_days, true);
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

            @if($task->instructions)
                <div class="mt-3">
                    <h6>{{ t('Instructions') }}</h6>
                    <div class="alert alert-info">
                        {{ $task->instructions }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Today's Assignments -->
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ t('Today\'s Assignments') }}</h5>
        </div>
        <div class="card-body">
            @if($todaysAssignments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>{{ t('Assigned To') }}</th>
                                <th>{{ t('Assignment Type') }}</th>
                                <th>{{ t('Status') }}</th>
                                <th>{{ t('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todaysAssignments as $assignment)
                                <tr>
                                    <td>
                                        <i class="bi bi-{{ $assignment->assignment_type == 'user' ? 'person' : 'people' }}"></i> {{ $assignment->getAssignedDisplayName() }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $assignment->assignment_type == 'user' ? 'primary' : 'info' }}">
                                            {{ ucfirst($assignment->assignment_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($assignment->completion)
                                            <span class="badge bg-success">{{ t('Completed') }}</span>
                                            <br>
                                            <small class="text-muted">{{ $assignment->completion->completed_at->format('H:i') }}</small>
                                        @else
                                            <span class="badge bg-warning">{{ t('Pending') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$assignment->completion)
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#completeModal{{ $assignment->id }}">
                                                <i class="bi bi-check-lg"></i> {{ t('Complete') }}
                                            </button>
                                        @else
                                            <form action="{{ route('department-admin.tasks.uncomplete', $assignment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-x-lg"></i> {{ t('Uncomplete') }}
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">{{ t('No assignments for today') }}</p>
            @endif
        </div>
    </div>
    
    <!-- Recent Completions -->
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ t('Recent Completions') }}</h5>
        </div>
        <div class="card-body">
            @if($recentCompletions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>{{ t('Completed By') }}</th>
                                <th>{{ t('Completed At') }}</th>
                                <th>{{ t('Assignment Date') }}</th>
                                @if($recentCompletions->where('notes', '!=', null)->count() > 0)
                                    <th>{{ t('Notes') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentCompletions as $completion)
                                <tr>
                                    <td>{{ $completion->completedBy->name }}</td>
                                    <td>{{ $completion->completed_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $completion->assignment->assigned_date->format('Y-m-d') }}</td>
                                    @if($recentCompletions->where('notes', '!=', null)->count() > 0)
                                        <td>
                                            @if($completion->notes)
                                                <span class="text-muted">{{ Str::limit($completion->notes, 50) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">{{ t('No recent completions') }}</p>
            @endif
        </div>
    </div>
</div>

<!-- Complete Task Modals -->
@foreach($todaysAssignments as $assignment)
    @if(!$assignment->completion)
        <div class="modal fade" id="completeModal{{ $assignment->id }}" tabindex="-1" aria-labelledby="completeModalLabel{{ $assignment->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="completeModalLabel{{ $assignment->id }}">{{ t('Complete Task') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('department-admin.tasks.complete', $assignment) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <h6>{{ t('Task') }}: {{ $task->title }}</h6>
                                <p class="text-muted">
                                                                            {{ t('Assigned to') }}: {{ $assignment->getAssignedDisplayName() }}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes{{ $assignment->id }}" class="form-label">{{ t('Completion Notes') }} <span class="text-muted">({{ t('Optional') }})</span></label>
                                <textarea class="form-control" id="notes{{ $assignment->id }}" name="notes" rows="3" placeholder="{{ t('Add any comments or notes about completing this task...') }}"></textarea>
                                <div class="form-text">{{ t('Maximum 1000 characters') }}</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ t('Cancel') }}</button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-lg me-1"></i> {{ t('Mark as Complete') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection 