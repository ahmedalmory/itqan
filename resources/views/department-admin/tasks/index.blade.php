@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ t('Tasks Management') }}</h1>
        <div>
            <a href="{{ route('department-admin.tasks.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> {{ t('Create New Task') }}
            </a>
            <a href="{{ route('department-admin.tasks.my-tasks') }}" class="btn btn-info">
                <i class="bi bi-person-check me-1"></i> {{ t('My Tasks') }}
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('department-admin.tasks.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">{{ t('Search') }}</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="{{ t('Search tasks...') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">{{ t('Status') }}</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">{{ t('All Status') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ t('Active') }}</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ t('Inactive') }}</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ t('Completed') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="priority" class="form-label">{{ t('Priority') }}</label>
                        <select class="form-select" id="priority" name="priority">
                            <option value="">{{ t('All Priorities') }}</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>{{ t('Low') }}</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>{{ t('Medium') }}</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>{{ t('High') }}</option>
                            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>{{ t('Urgent') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="department_id" class="form-label">{{ t('Department') }}</label>
                        <select class="form-select" id="department_id" name="department_id">
                            <option value="">{{ t('All Departments') }}</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-secondary me-2">
                            <i class="bi bi-funnel me-1"></i> {{ t('Filter') }}
                        </button>
                        <a href="{{ route('department-admin.tasks.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if($tasks->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ t('Title') }}</th>
                                <th>{{ t('Priority') }}</th>
                                <th>{{ t('Status') }}</th>
                                <th>{{ t('Department') }}</th>
                                <th>{{ t('Due Date') }}</th>
                                <th>{{ t('Created By') }}</th>
                                <th>{{ t('Progress') }}</th>
                                <th>{{ t('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                                @php
                                    $totalAssignments = $task->assignments->count();
                                    $completedAssignments = $task->assignments->filter(function($assignment) {
                                        return $assignment->completion;
                                    })->count();
                                    $progressPercentage = $totalAssignments > 0 ? ($completedAssignments / $totalAssignments) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ route('department-admin.tasks.show', $task) }}" class="text-decoration-none">
                                            {{ $task->title }}
                                        </a>
                                        @if($task->is_recurring)
                                            <span class="badge bg-info ms-1">{{ t('Recurring') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $task->priority == 'urgent' ? 'danger' : ($task->priority == 'high' ? 'warning' : ($task->priority == 'medium' ? 'info' : 'secondary')) }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $task->status == 'active' ? 'success' : ($task->status == 'completed' ? 'primary' : 'secondary') }}">
                                            {{ ucfirst($task->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $task->department->name ?? t('All Departments') }}</td>
                                    <td>{{ $task->due_date ? $task->due_date->format('Y-m-d') : '-' }}</td>
                                    <td>{{ $task->creator->name }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $progressPercentage }}%;" 
                                                 aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ round($progressPercentage) }}%
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $completedAssignments }}/{{ $totalAssignments }} {{ t('completed') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('department-admin.tasks.show', $task) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('department-admin.tasks.edit', $task) }}" class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('department-admin.tasks.destroy', $task) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('{{ t('Are you sure you want to delete this task?') }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $tasks->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-list-task text-muted" style="font-size: 3rem;"></i>
                    <h4 class="text-muted mt-3">{{ t('No tasks found') }}</h4>
                    <p class="text-muted">{{ t('Create your first task to get started') }}</p>
                    <a href="{{ route('department-admin.tasks.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> {{ t('Create New Task') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 