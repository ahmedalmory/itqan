@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ t('Add New User') }}</h5>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> {{ t('Back to Users') }}
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">{{ t('Name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">{{ t('Email') }} <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">{{ t('Password') }} <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">{{ t('Confirm Password') }} <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">{{ t('Phone Number') }}</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label">{{ t('Gender') }}</label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                    <option value="">{{ t('Select Gender') }}</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ t('Male') }}</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ t('Female') }}</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="age" class="form-label">{{ t('Age') }} <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('age') is-invalid @enderror" id="age" name="age" value="{{ old('age') }}" required>
                                @error('age')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="role" class="form-label">{{ t('Role') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="">{{ t('Select Role') }}</option>
                                    @if(auth()->user()->isSuperAdmin())
                                        <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>{{ t('Super Admin') }}</option>
                                    @endif
                                    <option value="department_admin" {{ old('role') == 'department_admin' ? 'selected' : '' }}>{{ t('Department Admin') }}</option>
                                    <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>{{ t('Teacher') }}</option>
                                    <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>{{ t('Supervisor') }}</option>
                                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>{{ t('Student') }}</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3 circle-section d-none">
                            <div class="col-md-6">
                                <label for="circle_id" class="form-label">{{ t('Study Circle') }}</label>
                                <select class="form-select @error('circle_id') is-invalid @enderror" id="circle_id" name="circle_id">
                                    <option value="">{{ t('Select Study Circle') }}</option>
                                    @foreach($circles as $circle)
                                        <option value="{{ $circle->id }}" {{ old('circle_id') == $circle->id ? 'selected' : '' }}>
                                            {{ $circle->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('circle_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-person-plus me-1"></i> {{ t('Create User') }}
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
        const departmentSection = document.querySelector('.department-section');
        const circleSection = document.querySelector('.circle-section');
        
        // Function to toggle visibility based on role
        function toggleSections() {
            const selectedRole = roleSelect.value;
            
            // Department is required for all roles except super_admin
            if (selectedRole === 'super_admin') {
                departmentSection.classList.add('d-none');
                document.getElementById('department_id').removeAttribute('required');
            } else {
                departmentSection.classList.remove('d-none');
                document.getElementById('department_id').setAttribute('required', 'required');
            }
            
            // Show circle section only for students and teachers
            if (selectedRole === 'student' || selectedRole === 'teacher') {
                circleSection.classList.remove('d-none');
            } else {
                circleSection.classList.add('d-none');
            }
        }
        
        // Initial toggle based on the selected role
        toggleSections();
        
        // Add event listener for changes in the role select
        roleSelect.addEventListener('change', toggleSections);
    });
</script>
@endpush
@endsection 