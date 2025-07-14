@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">{{ t('Tasks Management') }}</h1>
        <div>
            <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary me-2">
                <i class="bi bi-plus-lg me-1"></i> {{ t('Create Task') }}
            </a>

        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fs-2 fw-bold" id="totalTasks">-</div>
                            <div>{{ t('Total Tasks') }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-list-task fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fs-2 fw-bold" id="activeTasks">-</div>
                            <div>{{ t('Active Tasks') }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fs-2 fw-bold" id="todaysAssignments">-</div>
                            <div>{{ t('Today\'s Assignments') }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-calendar-day fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fs-2 fw-bold" id="completionRate">-</div>
                            <div>{{ t('Completion Rate') }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-graph-up fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ t('Filters') }}</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tasks.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="status" class="form-label">{{ t('Status') }}</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">{{ t('All Statuses') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ t('Active') }}</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ t('Inactive') }}</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ t('Completed') }}</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ t('Cancelled') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="priority" class="form-label">{{ t('Priority') }}</label>
                        <select name="priority" id="priority" class="form-select">
                            <option value="">{{ t('All Priorities') }}</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>{{ t('Low') }}</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>{{ t('Medium') }}</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>{{ t('High') }}</option>
                            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>{{ t('Urgent') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="department_id" class="form-label">{{ t('Department') }}</label>
                        <select name="department_id" id="department_id" class="form-select">
                            <option value="">{{ t('All Departments') }}</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="form-label">{{ t('Search') }}</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               value="{{ request('search') }}" placeholder="{{ t('Search tasks...') }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">{{ t('Filter') }}</button>
                        <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">{{ t('Clear') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ t('Tasks') }}</h5>
        </div>
        <div class="card-body">
            @if($tasks->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ t('Title') }}</th>
                                <th>{{ t('Priority') }}</th>
                                <th>{{ t('Status') }}</th>
                                <th>{{ t('Department') }}</th>
                                <th>{{ t('Due Date') }}</th>
                                <th>{{ t('Recurring') }}</th>
                                <th>{{ t('Created By') }}</th>
                                <th>{{ t('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $task->title }}</div>
                                        @if($task->description)
                                            <small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $task->getPriorityColor() }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $task->getStatusColor() }}">
                                            {{ ucfirst($task->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $task->department->name ?? t('No Department') }}
                                    </td>
                                    <td>
                                        {{ $task->due_date ? $task->due_date->format('Y-m-d') : t('No Due Date') }}
                                    </td>
                                    <td>
                                        @if($task->is_recurring)
                                            <span class="badge bg-success">{{ t('Yes') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ t('No') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $task->creator->name }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.tasks.show', $task) }}" 
                                               class="btn btn-sm btn-outline-primary">{{ t('View') }}</a>
                                            <a href="{{ route('admin.tasks.edit', $task) }}" 
                                               class="btn btn-sm btn-outline-warning">{{ t('Edit') }}</a>
                                            <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}" 
                                                  style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('{{ t('Are you sure you want to delete this task?') }}')">
                                                    {{ t('Delete') }}
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
                    {{ $tasks->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-list-task fs-1 text-muted"></i>
                    <h4 class="mt-3">{{ t('No tasks found') }}</h4>
                    <p class="text-muted">{{ t('Create your first task to get started') }}</p>
                    <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> {{ t('Create Task') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>



@endsection

@push('scripts')
<script>
    // Load statistics
    function loadStatistics() {
        fetch('{{ route('admin.tasks.statistics') }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('totalTasks').textContent = data.total_tasks;
                document.getElementById('activeTasks').textContent = data.active_tasks;
                document.getElementById('todaysAssignments').textContent = data.todays_assignments;
                document.getElementById('completionRate').textContent = data.completion_rate + '%';
            })
            .catch(error => console.error('Error loading statistics:', error));
    }



    // Load statistics on page load
    document.addEventListener('DOMContentLoaded', loadStatistics);
</script>
@endpush 