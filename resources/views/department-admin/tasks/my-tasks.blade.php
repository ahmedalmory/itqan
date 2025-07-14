@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ t('My Tasks') }}</h1>
        <a href="{{ route('department-admin.tasks.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-list-ul me-1"></i> {{ t('All Tasks') }}
        </a>
    </div>

    <!-- Tasks List -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if($assignments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ t('Task') }}</th>
                                <th>{{ t('Priority') }}</th>
                                <th>{{ t('Assignment Date') }}</th>
                                <th>{{ t('Due Date') }}</th>
                                <th>{{ t('Status') }}</th>
                                <th>{{ t('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignments as $assignment)
                                <tr>
                                    <td>
                                        <strong>{{ $assignment->task->title }}</strong>
                                        @if($assignment->task->is_recurring)
                                            <span class="badge bg-info ms-1">{{ t('Recurring') }}</span>
                                        @endif
                                        @if($assignment->task->description)
                                            <br>
                                            <small class="text-muted">{{ Str::limit($assignment->task->description, 100) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $assignment->task->priority == 'urgent' ? 'danger' : ($assignment->task->priority == 'high' ? 'warning' : ($assignment->task->priority == 'medium' ? 'info' : 'secondary')) }}">
                                            {{ ucfirst($assignment->task->priority) }}
                                        </span>
                                    </td>
                                    <td>{{ $assignment->assigned_date->format('Y-m-d') }}</td>
                                    <td>{{ $assignment->task->due_date ? $assignment->task->due_date->format('Y-m-d') : '-' }}</td>
                                    <td>
                                        @if($assignment->completion)
                                            <span class="badge bg-success">{{ t('Completed') }}</span>
                                            <br>
                                            <small class="text-muted">{{ $assignment->completion->completed_at->format('Y-m-d H:i') }}</small>
                                        @else
                                            <span class="badge bg-warning">{{ t('Pending') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$assignment->completion)
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#completeModal{{ $assignment->id }}">
                                                <i class="bi bi-check-lg"></i> {{ t('Mark Complete') }}
                                            </button>
                                        @else
                                            <form action="{{ route('department-admin.tasks.uncomplete', $assignment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-x-lg"></i> {{ t('Mark Incomplete') }}
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $assignments->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-list-task text-muted" style="font-size: 3rem;"></i>
                    <h4 class="text-muted mt-3">{{ t('No tasks assigned') }}</h4>
                    <p class="text-muted">{{ t('You have no tasks assigned to you at the moment') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Complete Task Modals -->
@foreach($assignments as $assignment)
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
                                <h6>{{ t('Task') }}: {{ $assignment->task->title }}</h6>
                                @if($assignment->task->description)
                                    <p class="text-muted">{{ $assignment->task->description }}</p>
                                @endif
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