@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-8">
            <!-- Task Details -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $task->title }}</h5>
                    <span class="badge bg-light text-dark">{{ ucfirst($task->priority) }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>{{ t('Status') }}:</strong>
                            <span class="badge bg-{{ $task->status == 'active' ? 'success' : ($task->status == 'completed' ? 'primary' : 'secondary') }}">
                                {{ ucfirst($task->status) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>{{ t('Due Date') }}:</strong>
                            {{ $task->due_date ? $task->due_date->format('Y-m-d') : t('No due date') }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>{{ t('Created By') }}:</strong>
                            {{ $task->creator->name }}
                        </div>
                        <div class="col-md-6">
                            <strong>{{ t('Department') }}:</strong>
                            {{ $task->department->name ?? t('All Departments') }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>{{ t('Recurring') }}:</strong>
                            {{ $task->is_recurring ? t('Yes') : t('No') }}
                        </div>
                        <div class="col-md-6">
                            <strong>{{ t('Created At') }}:</strong>
                            {{ $task->created_at->format('Y-m-d H:i') }}
                        </div>
                    </div>
                    
                    @if($task->description)
                    <div class="mb-3">
                        <strong>{{ t('Description') }}:</strong>
                        <p class="mt-2">{{ $task->description }}</p>
                    </div>
                    @endif
                    
                    @if($task->is_recurring)
                    <div class="mb-3">
                        <strong>{{ t('Recurring Days') }}:</strong>
                        <div class="mt-2">
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                @if($task->$day)
                                    <span class="badge bg-secondary me-1">{{ t(ucfirst($day)) }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if($task->excluded_dates)
                    <div class="mb-3">
                        <strong>{{ t('Excluded Dates') }}:</strong>
                        <p class="mt-2">{{ $task->excluded_dates }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Today's Assignments -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="mb-0">{{ t('Today\'s Assignments') }}</h6>
                </div>
                <div class="card-body">
                    @if($todaysAssignments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ t('Assigned To') }}</th>
                                        <th>{{ t('Status') }}</th>
                                        <th>{{ t('Completed At') }}</th>
                                        <th>{{ t('Completed By') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todaysAssignments as $assignment)
                                    <tr>
                                        <td>{{ $assignment->getAssignedDisplayName() }}</td>
                                        <td>
                                            @if($assignment->completion)
                                                <span class="badge bg-success">{{ t('Completed') }}</span>
                                            @else
                                                <span class="badge bg-warning">{{ t('Pending') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $assignment->completion ? $assignment->completion->completed_at->format('Y-m-d H:i') : '-' }}
                                        </td>
                                        <td>
                                            {{ $assignment->completion ? $assignment->completion->completedBy->name : '-' }}
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
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0">{{ t('Recent Completions') }}</h6>
                </div>
                <div class="card-body">
                    @if($recentCompletions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ t('Assigned To') }}</th>
                                        <th>{{ t('Completed By') }}</th>
                                        <th>{{ t('Completed At') }}</th>
                                        <th>{{ t('Assignment Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentCompletions as $completion)
                                    <tr>
                                        <td>{{ $completion->assignment->getAssignedDisplayName() }}</td>
                                        <td>{{ $completion->completedBy->name }}</td>
                                        <td>{{ $completion->completed_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $completion->assignment->assigned_date->format('Y-m-d') }}</td>
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
        
        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0">{{ t('Actions') }}</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-1"></i> {{ t('Edit Task') }}
                        </a>
                        <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> {{ t('Back to Tasks') }}
                        </a>
                        <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('{{ t('Are you sure you want to delete this task?') }}')">
                                <i class="bi bi-trash me-1"></i> {{ t('Delete Task') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 