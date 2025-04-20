@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('Create Department') }}</h1>
        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> {{ t('Back to Departments') }}
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

            <form action="{{ route('admin.departments.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">{{ t('Department Name') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">{{ t('Description') }}</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="student_gender" class="form-label">{{ t('Student Gender') }} <span class="text-danger">*</span></label>
                    <select class="form-select @error('student_gender') is-invalid @enderror" id="student_gender" name="student_gender" required>
                        <option value="">{{ t('Select Gender') }}</option>
                        <option value="male" {{ old('student_gender') == 'male' ? 'selected' : '' }}>{{ t('Male') }}</option>
                        <option value="female" {{ old('student_gender') == 'female' ? 'selected' : '' }}>{{ t('Female') }}</option>
                        <option value="mixed" {{ old('student_gender') == 'mixed' ? 'selected' : '' }}>{{ t('Mixed') }}</option>
                    </select>
                    @error('student_gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="card mb-3">
                    <div class="card-header">
                        {{ t('Subscription Fees') }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="monthly_fees" class="form-label">{{ t('Monthly') }}</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" class="form-control @error('monthly_fees') is-invalid @enderror" id="monthly_fees" name="monthly_fees" value="{{ old('monthly_fees') }}">
                                        <span class="input-group-text">{{ t('EGP') }}</span>
                                    </div>
                                    @error('monthly_fees')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="quarterly_fees" class="form-label">{{ t('Quarterly') }}</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" class="form-control @error('quarterly_fees') is-invalid @enderror" id="quarterly_fees" name="quarterly_fees" value="{{ old('quarterly_fees') }}">
                                        <span class="input-group-text">{{ t('EGP') }}</span>
                                    </div>
                                    @error('quarterly_fees')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="biannual_fees" class="form-label">{{ t('Biannual') }}</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" class="form-control @error('biannual_fees') is-invalid @enderror" id="biannual_fees" name="biannual_fees" value="{{ old('biannual_fees') }}">
                                        <span class="input-group-text">{{ t('EGP') }}</span>
                                    </div>
                                    @error('biannual_fees')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="annual_fees" class="form-label">{{ t('Annual') }}</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" class="form-control @error('annual_fees') is-invalid @enderror" id="annual_fees" name="annual_fees" value="{{ old('annual_fees') }}">
                                        <span class="input-group-text">{{ t('EGP') }}</span>
                                    </div>
                                    @error('annual_fees')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-3">
                    <div class="card-header">
                        {{ t('Working Days') }} <span class="text-danger">*</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="work_days[]" value="monday" id="monday" {{ in_array('monday', old('work_days', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="monday">
                                            {{ t('Monday') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="work_days[]" value="tuesday" id="tuesday" {{ in_array('tuesday', old('work_days', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tuesday">
                                            {{ t('Tuesday') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="work_days[]" value="wednesday" id="wednesday" {{ in_array('wednesday', old('work_days', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="wednesday">
                                            {{ t('Wednesday') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="work_days[]" value="thursday" id="thursday" {{ in_array('thursday', old('work_days', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="thursday">
                                            {{ t('Thursday') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="work_days[]" value="friday" id="friday" {{ in_array('friday', old('work_days', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="friday">
                                            {{ t('Friday') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="work_days[]" value="saturday" id="saturday" {{ in_array('saturday', old('work_days', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="saturday">
                                            {{ t('Saturday') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="work_days[]" value="sunday" id="sunday" {{ in_array('sunday', old('work_days', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sunday">
                                            {{ t('Sunday') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('work_days')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="registration_open" name="registration_open" value="1" {{ old('registration_open', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="registration_open">{{ t('Open for Registration') }}</label>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> {{ t('Create Department') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 