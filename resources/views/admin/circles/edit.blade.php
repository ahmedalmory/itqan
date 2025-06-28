@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('Edit Study Circle') }}</h1>
        <a href="{{ route('admin.circles.show', $circle) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> {{ t('Back to Circle') }}
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.circles.update', $circle) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">{{ t('Circle Name') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $circle->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="department_id" class="form-label">{{ t('Department') }} <span class="text-danger">*</span></label>
                    <select class="form-select @error('department_id') is-invalid @enderror" id="department_id" name="department_id" required>
                        <option value="">{{ t('Select Department') }}</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id', $circle->department_id) == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">{{ t('Description') }}</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $circle->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="teacher_id" class="form-label">{{ t('Teacher') }}</label>
                            <select class="form-select @error('teacher_id') is-invalid @enderror" id="teacher_id" name="teacher_id">
                                <option value="">{{ t('Select Teacher') }}</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $circle->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }} ({{ $teacher->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="supervisor_id" class="form-label">{{ t('Supervisor') }}</label>
                            <select class="form-select @error('supervisor_id') is-invalid @enderror" id="supervisor_id" name="supervisor_id">
                                <option value="">{{ t('Select Supervisor') }}</option>
                                @foreach($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}" {{ old('supervisor_id', $circle->supervisor_id) == $supervisor->id ? 'selected' : '' }}>
                                        {{ $supervisor->name }} ({{ $supervisor->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('supervisor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="age_from" class="form-label">{{ t('Minimum Age') }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('age_from') is-invalid @enderror" id="age_from" name="age_from" value="{{ old('age_from', $circle->age_from) }}" min="3" max="100" required>
                            @error('age_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="age_to" class="form-label">{{ t('Maximum Age') }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('age_to') is-invalid @enderror" id="age_to" name="age_to" value="{{ old('age_to', $circle->age_to) }}" min="3" max="100" required>
                            @error('age_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="max_students" class="form-label">{{ t('Maximum Students') }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('max_students') is-invalid @enderror" id="max_students" name="max_students" value="{{ old('max_students', $circle->max_students) }}" min="1" max="100" required>
                            @error('max_students')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="location" class="form-label">{{ t('Location') }}</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $circle->location) }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="meeting_time" class="form-label">{{ t('Meeting Time') }}</label>
                    <input type="text" class="form-control @error('meeting_time') is-invalid @enderror" id="meeting_time" name="meeting_time" value="{{ old('meeting_time', $circle->meeting_time) }}">
                    @error('meeting_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">{{ t('Example: Monday and Wednesday 5:00 PM - 7:00 PM') }}</div>
                </div>
                
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $circle->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">{{ t('Active') }}</label>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> {{ t('Update Circle') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 