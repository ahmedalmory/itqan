@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('Edit Report') }}</h1>
        <div>
            <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> {{ t('Back to Report') }}
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">{{ t('Report Information') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reports.update', $report->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="student_id" class="form-label">{{ t('Student') }} <span class="text-danger">*</span></label>
                        <select id="student_id" name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                            <option value="">{{ t('Select Student') }}</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id', $report->student_id) == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="report_date" class="form-label">{{ t('Report Date') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('report_date') is-invalid @enderror" 
                               id="report_date" name="report_date" 
                               value="{{ old('report_date', $report->report_date->format('Y-m-d')) }}" required>
                        @error('report_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <h5 class="mt-4 mb-3">{{ t('Memorization Details') }}</h5>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="memorization_parts" class="form-label">{{ t('Memorization Parts') }} <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('memorization_parts') is-invalid @enderror" 
                               id="memorization_parts" name="memorization_parts" 
                               value="{{ old('memorization_parts', $report->memorization_parts) }}"
                               min="0" max="8" step="0.5" required>
                        @error('memorization_parts')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="memorization_from_surah_id" class="form-label">{{ t('From Surah') }}</label>
                        <select id="memorization_from_surah_id" name="memorization_from_surah_id" 
                                class="form-select @error('memorization_from_surah_id') is-invalid @enderror">
                            <option value="">{{ t('Select Surah') }}</option>
                            @foreach($surahs as $surah)
                                <option value="{{ $surah->id }}" {{ old('memorization_from_surah_id', $report->memorization_from_surah_id) == $surah->id ? 'selected' : '' }}>
                                    {{ $surah->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('memorization_from_surah_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label for="memorization_from_verse" class="form-label">{{ t('From Verse') }}</label>
                        <input type="number" class="form-control @error('memorization_from_verse') is-invalid @enderror" 
                               id="memorization_from_verse" name="memorization_from_verse" 
                               value="{{ old('memorization_from_verse', $report->memorization_from_verse) }}" min="1">
                        @error('memorization_from_verse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label for="memorization_to_surah_id" class="form-label">{{ t('To Surah') }}</label>
                        <select id="memorization_to_surah_id" name="memorization_to_surah_id" 
                                class="form-select @error('memorization_to_surah_id') is-invalid @enderror">
                            <option value="">{{ t('Select Surah') }}</option>
                            @foreach($surahs as $surah)
                                <option value="{{ $surah->id }}" {{ old('memorization_to_surah_id', $report->memorization_to_surah_id) == $surah->id ? 'selected' : '' }}>
                                    {{ $surah->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('memorization_to_surah_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label for="memorization_to_verse" class="form-label">{{ t('To Verse') }}</label>
                        <input type="number" class="form-control @error('memorization_to_verse') is-invalid @enderror" 
                               id="memorization_to_verse" name="memorization_to_verse" 
                               value="{{ old('memorization_to_verse', $report->memorization_to_verse) }}" min="1">
                        @error('memorization_to_verse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <h5 class="mt-4 mb-3">{{ t('Revision Details') }}</h5>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="revision_parts" class="form-label">{{ t('Revision Parts') }} <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('revision_parts') is-invalid @enderror" 
                               id="revision_parts" name="revision_parts" 
                               value="{{ old('revision_parts', $report->revision_parts) }}"
                               min="0" max="20" step="0.5" required>
                        @error('revision_parts')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="revision_from_surah_id" class="form-label">{{ t('From Surah') }}</label>
                        <select id="revision_from_surah_id" name="revision_from_surah_id" 
                                class="form-select @error('revision_from_surah_id') is-invalid @enderror">
                            <option value="">{{ t('Select Surah') }}</option>
                            @foreach($surahs as $surah)
                                <option value="{{ $surah->id }}" {{ old('revision_from_surah_id', $report->revision_from_surah_id) == $surah->id ? 'selected' : '' }}>
                                    {{ $surah->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('revision_from_surah_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label for="revision_from_verse" class="form-label">{{ t('From Verse') }}</label>
                        <input type="number" class="form-control @error('revision_from_verse') is-invalid @enderror" 
                               id="revision_from_verse" name="revision_from_verse" 
                               value="{{ old('revision_from_verse', $report->revision_from_verse) }}" min="1">
                        @error('revision_from_verse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label for="revision_to_surah_id" class="form-label">{{ t('To Surah') }}</label>
                        <select id="revision_to_surah_id" name="revision_to_surah_id" 
                                class="form-select @error('revision_to_surah_id') is-invalid @enderror">
                            <option value="">{{ t('Select Surah') }}</option>
                            @foreach($surahs as $surah)
                                <option value="{{ $surah->id }}" {{ old('revision_to_surah_id', $report->revision_to_surah_id) == $surah->id ? 'selected' : '' }}>
                                    {{ $surah->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('revision_to_surah_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label for="revision_to_verse" class="form-label">{{ t('To Verse') }}</label>
                        <input type="number" class="form-control @error('revision_to_verse') is-invalid @enderror" 
                               id="revision_to_verse" name="revision_to_verse" 
                               value="{{ old('revision_to_verse', $report->revision_to_verse) }}" min="1">
                        @error('revision_to_verse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <h5 class="mt-4 mb-3">{{ t('Evaluation') }}</h5>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="grade" class="form-label">{{ t('Grade') }} <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('grade') is-invalid @enderror" 
                               id="grade" name="grade" 
                               value="{{ old('grade', $report->grade) }}"
                               min="0" max="100" required>
                        @error('grade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            {{ t('A score from 0 to 100.') }}
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="notes" class="form-label">{{ t('Notes') }}</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="4">{{ old('notes', $report->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-outline-secondary me-md-2">
                        {{ t('Cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> {{ t('Update Report') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 