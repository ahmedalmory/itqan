@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ t('Edit Task') }}</h5>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.tasks.update', $task) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Basic Information -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="title" class="form-label">{{ t('Task Title') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $task->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="priority" class="form-label">{{ t('Priority') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('priority') is-invalid @enderror" 
                                        id="priority" name="priority" required>
                                    <option value="">{{ t('Select Priority') }}</option>
                                    <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>{{ t('Low') }}</option>
                                    <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>{{ t('Medium') }}</option>
                                    <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>{{ t('High') }}</option>
                                    <option value="urgent" {{ old('priority', $task->priority) == 'urgent' ? 'selected' : '' }}>{{ t('Urgent') }}</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ t('Description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="department_id" class="form-label">{{ t('Department') }}</label>
                                <select class="form-select @error('department_id') is-invalid @enderror" 
                                        id="department_id" name="department_id">
                                    <option value="">{{ t('Select Department') }}</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id', $task->department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="due_date" class="form-label">{{ t('Due Date') }}</label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                       id="due_date" name="due_date" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">{{ t('Status') }}</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status">
                                    <option value="active" {{ old('status', $task->status) == 'active' ? 'selected' : '' }}>{{ t('Active') }}</option>
                                    <option value="inactive" {{ old('status', $task->status) == 'inactive' ? 'selected' : '' }}>{{ t('Inactive') }}</option>
                                    <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>{{ t('Completed') }}</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Recurring Task Section -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_recurring" 
                                           id="isRecurring" value="1" {{ old('is_recurring', $task->is_recurring) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isRecurring">
                                        <h6 class="mb-0">{{ t('Recurring Task') }}</h6>
                                    </label>
                                </div>
                            </div>
                            <div class="card-body" id="recurringSection" style="display: {{ old('is_recurring', $task->is_recurring) ? 'block' : 'none' }};">
                                <div class="mb-3">
                                    <label class="form-label">{{ t('Recurring Days') }}</label>
                                    <div class="row">
                                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                            <div class="col-md-3 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="recurring_days[]" 
                                                           value="{{ $day }}" id="day_{{ $day }}" 
                                                           {{ old('recurring_days') ? (in_array($day, old('recurring_days', [])) ? 'checked' : '') : ($task->$day ? 'checked' : '') }}>
                                                    <label class="form-check-label" for="day_{{ $day }}">
                                                        {{ t(ucfirst($day)) }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="excluded_dates" class="form-label">{{ t('Excluded Dates') }}</label>
                                    <textarea class="form-control @error('excluded_dates') is-invalid @enderror" 
                                              id="excluded_dates" name="excluded_dates" rows="3"
                                              placeholder="{{ t('Enter dates to exclude (one per line, format: YYYY-MM-DD)') }}">{{ old('excluded_dates', $task->excluded_dates) }}</textarea>
                                    @error('excluded_dates')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        {{ t('Enter dates to exclude from recurring assignments, one per line (format: YYYY-MM-DD)') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> {{ t('Back to Tasks') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i> {{ t('Update Task') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle recurring section
        const isRecurring = document.getElementById('isRecurring');
        const recurringSection = document.getElementById('recurringSection');

        isRecurring.addEventListener('change', function() {
            recurringSection.style.display = this.checked ? 'block' : 'none';
        });

        // Initialize recurring section visibility
        if (isRecurring.checked) {
            recurringSection.style.display = 'block';
        }
    });
</script>
@endpush 