@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ t('Edit User') }}</h5>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> {{ t('Back to Users') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ t('Name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="national_id" class="form-label">{{ t('national_id') }}</label>
                            <input type="text" class="form-control @error('national_id') is-invalid @enderror" id="national_id" name="national_id" value="{{ old('national_id', $user->national_id) }}">
                            @error('national_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ t('Email') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">{{ t('Phone Number') }}</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="age" class="form-label">{{ t('Age') }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('age') is-invalid @enderror" id="age" name="age" value="{{ old('age', $user->age) }}" required>
                            @error('age')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="gender" class="form-label">{{ t('Gender') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                <option value="" disabled>{{ t('Select Gender') }}</option>
                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ t('Male') }}</option>
                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ t('Female') }}</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ t('Password') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            <small class="form-text text-muted">{{ t('Leave blank to keep current password') }}</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ t('Confirm Password') }}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">{{ t('Role') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="" disabled>{{ t('Select Role') }}</option>
                                @if(auth()->user()->role === 'admin')
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>{{ t('Admin') }}</option>
                                @endif
                                <option value="supervisor" {{ old('role', $user->role) == 'supervisor' ? 'selected' : '' }}>{{ t('Supervisor') }}</option>
                                <option value="teacher" {{ old('role', $user->role) == 'teacher' ? 'selected' : '' }}>{{ t('Teacher') }}</option>
                                <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>{{ t('Student') }}</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div id="studyCircleSection" class="mb-3" style="display: none;">
                            <label for="study_circle_id" class="form-label">{{ t('Study Circle') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('study_circle_id') is-invalid @enderror" id="study_circle_id" name="study_circle_id">
                                <option value="" disabled>{{ t('Select Study Circle') }}</option>
                                @foreach($studyCircles as $circle)
                                    <option value="{{ $circle->id }}" {{ old('study_circle_id', $user->study_circle_id) == $circle->id ? 'selected' : '' }}>
                                        {{ $circle->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('study_circle_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    {{ t('Active Account') }}
                                </label>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> {{ t('Update User') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const departmentSection = document.getElementById('departmentSection');
        const studyCircleSection = document.getElementById('studyCircleSection');
        const departmentSelect = document.getElementById('department_id');
        const studyCircleSelect = document.getElementById('study_circle_id');
        
        function updateVisibility() {
            const selectedRole = roleSelect.value;
            
            if (selectedRole === 'supervisor' || selectedRole === 'teacher') {
                departmentSection.style.display = 'block';
                departmentSelect.required = true;
                studyCircleSection.style.display = 'none';
                studyCircleSelect.required = false;
            } else if (selectedRole === 'student') {
                departmentSection.style.display = 'block';
                departmentSelect.required = true;
                studyCircleSection.style.display = 'block';
                studyCircleSelect.required = true;
            } else {
                departmentSection.style.display = 'none';
                departmentSelect.required = false;
                studyCircleSection.style.display = 'none';
                studyCircleSelect.required = false;
            }
        }
        
        roleSelect.addEventListener('change', updateVisibility);
        
        // Initialize visibility based on current role
        updateVisibility();
    });
</script>
@endpush
@endsection 