@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ t('Create New Task') }}</h5>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.tasks.store') }}" method="POST">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="title" class="form-label">{{ t('Task Title') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="priority" class="form-label">{{ t('Priority') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('priority') is-invalid @enderror" 
                                        id="priority" name="priority" required>
                                    <option value="">{{ t('Select Priority') }}</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>{{ t('Low') }}</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>{{ t('Medium') }}</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>{{ t('High') }}</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>{{ t('Urgent') }}</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ t('Description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description') }}</textarea>
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
                                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
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
                                       id="due_date" name="due_date" value="{{ old('due_date') }}" min="{{ today()->format('Y-m-d') }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Assignment Section -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">{{ t('Assignment') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">{{ t('Assign To') }} <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="assignment_type" 
                                               id="assignToRole" value="role" {{ old('assignment_type') == 'role' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="assignToRole">
                                            {{ t('Assign to Role') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="assignment_type" 
                                               id="assignToUser" value="user" {{ old('assignment_type') == 'user' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="assignToUser">
                                            {{ t('Assign to Specific User') }}
                                        </label>
                                    </div>
                                </div>

                                <div id="roleAssignment" class="mb-3" style="display: none;">
                                    <label for="assigned_to_role" class="form-label">{{ t('Select Role') }}</label>
                                    <select class="form-select @error('assigned_to_role') is-invalid @enderror" 
                                            id="assigned_to_role" name="assigned_to_role">
                                        <option value="">{{ t('Select Role') }}</option>
                                        @if(auth()->user()->isSuperAdmin())
                                            <option value="department_admin" {{ old('assigned_to_role') == 'department_admin' ? 'selected' : '' }}>
                                                {{ t('Department Admin') }}
                                            </option>
                                        @endif
                                        <option value="teacher" {{ old('assigned_to_role') == 'teacher' ? 'selected' : '' }}>
                                            {{ t('Teacher') }}
                                        </option>
                                        <option value="supervisor" {{ old('assigned_to_role') == 'supervisor' ? 'selected' : '' }}>
                                            {{ t('Supervisor') }}
                                        </option>
                                    </select>
                                    @error('assigned_to_role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="userAssignment" class="mb-3" style="display: none;">
                                    <label for="assigned_to_user_id" class="form-label">{{ t('Select User') }}</label>
                                    <select class="form-select @error('assigned_to_user_id') is-invalid @enderror" 
                                            id="assigned_to_user_id" name="assigned_to_user_id">
                                        <option value="">{{ t('Select User') }}</option>
                                        <!-- Users will be loaded via JavaScript based on department selection -->
                                    </select>
                                    @error('assigned_to_user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Recurring Task Section -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_recurring" 
                                           id="isRecurring" value="1" {{ old('is_recurring') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isRecurring">
                                        <h6 class="mb-0">{{ t('Recurring Task') }}</h6>
                                    </label>
                                </div>
                            </div>
                            <div class="card-body" id="recurringSection" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label">{{ t('Recurring Days') }}</label>
                                    <div class="row">
                                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                            <div class="col-md-3 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="recurring_days[]" 
                                                           value="{{ $day }}" id="day_{{ $day }}" 
                                                           {{ in_array($day, old('recurring_days', [])) ? 'checked' : '' }}>
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
                                              placeholder="{{ t('Enter dates to exclude (one per line, format: YYYY-MM-DD)') }}">{{ old('excluded_dates') }}</textarea>
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
                                <i class="bi bi-check-lg me-1"></i> {{ t('Create Task') }}
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
        // Toggle assignment type
        const assignToRole = document.getElementById('assignToRole');
        const assignToUser = document.getElementById('assignToUser');
        const roleAssignment = document.getElementById('roleAssignment');
        const userAssignment = document.getElementById('userAssignment');

        function toggleAssignmentType() {
            if (assignToRole.checked) {
                roleAssignment.style.display = 'block';
                userAssignment.style.display = 'none';
            } else if (assignToUser.checked) {
                roleAssignment.style.display = 'none';
                userAssignment.style.display = 'block';
            } else {
                roleAssignment.style.display = 'none';
                userAssignment.style.display = 'none';
            }
        }

        assignToRole.addEventListener('change', toggleAssignmentType);
        assignToUser.addEventListener('change', toggleAssignmentType);

        // Initialize on page load
        toggleAssignmentType();

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

        // Load users based on department selection
        const departmentSelect = document.getElementById('department_id');
        const userSelect = document.getElementById('assigned_to_user_id');

        // Load all users initially since no department is selected
        function loadUsers(departmentId = null) {
            userSelect.innerHTML = '<option value="">{{ t('Loading...') }}</option>';

            // Choose endpoint based on whether a department is selected
            const endpoint = departmentId ? `/api/departments/${departmentId}/users` : '/api/all-users';
            
            fetch(endpoint, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(users => {
                    userSelect.innerHTML = '<option value="">{{ t('Select User') }}</option>';
                    if (users.length === 0) {
                        const noUsersMessage = departmentId ? 
                            '{{ t('No users found in this department') }}' : 
                            '{{ t('No users found') }}';
                        userSelect.innerHTML = `<option value="">${noUsersMessage}</option>`;
                    } else {
                        users.forEach(user => {
                            const option = document.createElement('option');
                            option.value = user.id;
                            option.textContent = `${user.name} (${user.role})`;
                            userSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading users:', error);
                    userSelect.innerHTML = '<option value="">{{ t('Error loading users') }}</option>';
                });
        }

        // Load all users initially
        loadUsers();

        departmentSelect.addEventListener('change', function() {
            const departmentId = this.value;
            loadUsers(departmentId);
        });
    });
</script>
@endpush 