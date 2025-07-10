@extends('layouts.dashboard')

@section('dashboard-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">{{ t('create_daily_report') }}</h1>
    <a href="{{ route('student.reports.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> {{ t('back_to_reports') }}
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger mb-4">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('student.reports.store') }}" method="POST" id="reportForm" class="needs-validation" novalidate>
            @csrf
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="report_date" class="form-label required">{{ t('report_date') }}</label>
                    <input type="date" class="form-control form-control-lg @error('report_date') is-invalid @enderror" 
                           id="report_date" name="report_date" 
                           value="{{ old('report_date', now()->format('Y-m-d')) }}" required>
                    @error('report_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="card mb-4 bg-light">
                <div class="card-header">
                    <h5 class="mb-0">{{ t('memorization') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="memorization_parts" class="form-label required">{{ t('memorization_parts') }}</label>
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control text-center fw-bold @error('memorization_parts') is-invalid @enderror" 
                                       id="memorization_parts" name="memorization_parts" required readonly
                                       value="{{ old('memorization_parts', '0') }}">
                                <button type="button" class="btn btn-outline-primary" onclick="adjustParts('memorization', 0.25)">+ ربع</button>
                                <button type="button" class="btn btn-outline-primary" onclick="adjustParts('memorization', 0.5)">+ نصف</button>
                                <button type="button" class="btn btn-outline-primary" onclick="adjustParts('memorization', 1)">+ 1</button>
                                <button type="button" class="btn btn-outline-danger" onclick="resetParts('memorization')">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            @error('memorization_parts')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label for="memorization_from_surah_id" class="form-label">{{ t('memorization_from_surah') }}</label>
                            <select class="form-select form-select-lg @error('memorization_from_surah_id') is-invalid @enderror" 
                                    id="memorization_from_surah_id" name="memorization_from_surah_id">
                                <option value="">{{ t('select_surah') }}</option>
                                @foreach($surahs as $surah)
                                <option value="{{ $surah->id }}" {{ old('memorization_from_surah_id') == $surah->id ? 'selected' : '' }}>
                                    {{ $surah->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('memorization_from_surah_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="memorization_from_verse" class="form-label">{{ t('from_verse') }}</label>
                            <input type="number" class="form-control form-control-lg @error('memorization_from_verse') is-invalid @enderror" 
                                   id="memorization_from_verse" name="memorization_from_verse" min="1" 
                                   value="{{ old('memorization_from_verse') }}">
                            @error('memorization_from_verse')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="memorization_to_surah_id" class="form-label">{{ t('memorization_to_surah') }}</label>
                            <select class="form-select form-select-lg @error('memorization_to_surah_id') is-invalid @enderror" 
                                    id="memorization_to_surah_id" name="memorization_to_surah_id">
                                <option value="">{{ t('select_surah') }}</option>
                                @foreach($surahs as $surah)
                                <option value="{{ $surah->id }}" {{ old('memorization_to_surah_id') == $surah->id ? 'selected' : '' }}>
                                    {{ $surah->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('memorization_to_surah_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="memorization_to_verse" class="form-label">{{ t('to_verse') }}</label>
                            <input type="number" class="form-control form-control-lg @error('memorization_to_verse') is-invalid @enderror" 
                                   id="memorization_to_verse" name="memorization_to_verse" min="1" 
                                   value="{{ old('memorization_to_verse') }}">
                            @error('memorization_to_verse')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 bg-light">
                <div class="card-header">
                    <h5 class="mb-0">{{ t('revision') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="revision_parts" class="form-label required">{{ t('revision_parts') }}</label>
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control text-center fw-bold @error('revision_parts') is-invalid @enderror" 
                                       id="revision_parts" name="revision_parts" required readonly
                                       value="{{ old('revision_parts', '0') }}">
                                <button type="button" class="btn btn-outline-primary" onclick="adjustParts('revision', 0.25)">+ ربع</button>
                                <button type="button" class="btn btn-outline-primary" onclick="adjustParts('revision', 0.5)">+ نصف</button>
                                <button type="button" class="btn btn-outline-primary" onclick="adjustParts('revision', 1)">+ 1</button>
                                <button type="button" class="btn btn-outline-danger" onclick="resetParts('revision')">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            @error('revision_parts')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label for="revision_from_surah" class="form-label">{{ t('revision_from_surah') }}</label>
                            <select class="form-select form-select-lg @error('revision_from_surah') is-invalid @enderror" 
                                    id="revision_from_surah" name="revision_from_surah">
                                <option value="">{{ t('select_surah') }}</option>
                                @foreach($surahs as $surah)
                                <option value="{{ $surah->id }}" {{ old('revision_from_surah') == $surah->id ? 'selected' : '' }}>
                                    {{ $surah->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('revision_from_surah')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="revision_from_verse" class="form-label">{{ t('from_verse') }}</label>
                            <input type="number" class="form-control form-control-lg @error('revision_from_verse') is-invalid @enderror" 
                                   id="revision_from_verse" name="revision_from_verse" min="1" 
                                   value="{{ old('revision_from_verse') }}">
                            @error('revision_from_verse')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="revision_to_surah" class="form-label">{{ t('revision_to_surah') }}</label>
                            <select class="form-select form-select-lg @error('revision_to_surah') is-invalid @enderror" 
                                    id="revision_to_surah" name="revision_to_surah">
                                <option value="">{{ t('select_surah') }}</option>
                                @foreach($surahs as $surah)
                                <option value="{{ $surah->id }}" {{ old('revision_to_surah') == $surah->id ? 'selected' : '' }}>
                                    {{ $surah->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('revision_to_surah')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="revision_to_verse" class="form-label">{{ t('to_verse') }}</label>
                            <input type="number" class="form-control form-control-lg @error('revision_to_verse') is-invalid @enderror" 
                                   id="revision_to_verse" name="revision_to_verse" min="1" 
                                   value="{{ old('revision_to_verse') }}">
                            @error('revision_to_verse')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="grade" class="form-label">{{ t('grade') }}</label>
                    <div class="input-group input-group-lg">
                        <input type="number" class="form-control form-control-lg @error('grade') is-invalid @enderror" 
                               id="grade" name="grade" min="0" max="100" step="1" 
                               value="{{ old('grade') }}">
                        <span class="input-group-text">/ 100</span>
                    </div>
                    @error('grade')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-4">
                <label for="notes" class="form-label">{{ t('notes') }}</label>
                <textarea class="form-control form-control-lg @error('notes') is-invalid @enderror" 
                          id="notes" name="notes" rows="3" placeholder="{{ t('enter_notes_here') }}">{{ old('notes') }}</textarea>
                @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('student.reports.index') }}" class="btn btn-light btn-lg">
                    {{ t('cancel') }}
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save me-1"></i> {{ t('create_report') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Store surahs data
const surahs = @json($surahs);
const surahsMap = new Map(surahs.map(surah => [surah.id, surah]));

// Store parts values
let memorizationParts = {{ old('memorization_parts', 0) }};
let revisionParts = {{ old('revision_parts', 0) }};

// Function to format parts number
function formatParts(number) {
    if (number === Math.floor(number)) {
        return number.toString();
    } else if (number === Math.floor(number) + 0.5) {
        return number.toString();
    } else if (number === Math.floor(number) + 0.25) {
        return number.toString();
    }
    return number.toFixed(2);
}

// Function to adjust parts
function adjustParts(type, amount) {
    if (type === 'memorization') {
        memorizationParts += amount;
        if (memorizationParts > 20) memorizationParts = 20;
        document.getElementById('memorization_parts').value = formatParts(memorizationParts);
    } else {
        revisionParts += amount;
        if (revisionParts > 20) revisionParts = 20;
        document.getElementById('revision_parts').value = formatParts(revisionParts);
    }
}

// Function to reset parts
function resetParts(type) {
    if (type === 'memorization') {
        memorizationParts = 0;
        document.getElementById('memorization_parts').value = '0';
    } else {
        revisionParts = 0;
        document.getElementById('revision_parts').value = '0';
    }
}

// Update verse limits based on selected surah
function updateVerseLimits(selectElement, verseInput) {
    const surahId = selectElement.value;
    if (!surahId) return;
    
    const surah = surahsMap.get(parseInt(surahId));
    verseInput.max = surah.total_verses;
    verseInput.placeholder = `1 - ${surah.total_verses}`;
    
    // If current value is greater than max, reset it
    if (parseInt(verseInput.value) > surah.total_verses) {
        verseInput.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const fromSurahSelect = document.getElementById('memorization_from_surah_id');
    const toSurahSelect = document.getElementById('memorization_to_surah_id');
    const fromVerseInput = document.getElementById('memorization_from_verse');
    const toVerseInput = document.getElementById('memorization_to_verse');
    
    const revisionFromSurahSelect = document.getElementById('revision_from_surah');
    const revisionToSurahSelect = document.getElementById('revision_to_surah');
    const revisionFromVerseInput = document.getElementById('revision_from_verse');
    const revisionToVerseInput = document.getElementById('revision_to_verse');
    
    document.getElementById('memorization_parts').value = formatParts(memorizationParts);
    document.getElementById('revision_parts').value = formatParts(revisionParts);

    // Set initial verse limits if surahs are selected
    if (fromSurahSelect.value) {
        updateVerseLimits(fromSurahSelect, fromVerseInput);
    }
    if (toSurahSelect.value) {
        updateVerseLimits(toSurahSelect, toVerseInput);
    }
    if (revisionFromSurahSelect.value) {
        updateVerseLimits(revisionFromSurahSelect, revisionFromVerseInput);
    }
    if (revisionToSurahSelect.value) {
        updateVerseLimits(revisionToSurahSelect, revisionToVerseInput);
    }

    // Update verse limits when surah selection changes
    fromSurahSelect.addEventListener('change', () => updateVerseLimits(fromSurahSelect, fromVerseInput));
    toSurahSelect.addEventListener('change', () => updateVerseLimits(toSurahSelect, toVerseInput));
    revisionFromSurahSelect.addEventListener('change', () => updateVerseLimits(revisionFromSurahSelect, revisionFromVerseInput));
    revisionToSurahSelect.addEventListener('change', () => updateVerseLimits(revisionToSurahSelect, revisionToVerseInput));

    // Form validation
    document.getElementById('reportForm').addEventListener('submit', function(e) {
        const fromSurahId = parseInt(fromSurahSelect.value);
        const toSurahId = parseInt(toSurahSelect.value);
        const fromVerse = parseInt(fromVerseInput.value);
        const toVerse = parseInt(toVerseInput.value);
        
        const revisionFromSurahId = parseInt(revisionFromSurahSelect.value);
        const revisionToSurahId = parseInt(revisionToSurahSelect.value);
        const revisionFromVerse = parseInt(revisionFromVerseInput.value);
        const revisionToVerse = parseInt(revisionToVerseInput.value);
        
        // Validate that at least one of memorization or revision is greater than 0
        if (memorizationParts === 0 && revisionParts === 0) {
            e.preventDefault();
            alert('{{ t("must_enter_memorization_or_revision") }}');
            return false;
        }

        // Only validate memorization verses if both surah and verse are provided
        if (fromSurahId && toSurahId && fromVerse && toVerse) {
            const fromSurah = surahsMap.get(fromSurahId);
            const toSurah = surahsMap.get(toSurahId);
            
            if (fromVerse < 1 || fromVerse > fromSurah.total_verses) {
                e.preventDefault();
                alert('{{ t("invalid_from_verse") }}');
                return false;
            }
            
            if (toVerse < 1 || toVerse > toSurah.total_verses) {
                e.preventDefault();
                alert('{{ t("invalid_to_verse") }}');
                return false;
            }
            
            if (fromSurahId === toSurahId && fromVerse > toVerse) {
                e.preventDefault();
                alert('{{ t("from_verse_must_be_less_than_to_verse") }}');
                return false;
            }
            
            if (fromSurahId > toSurahId) {
                e.preventDefault();
                alert('{{ t("from_surah_must_be_before_to_surah") }}');
                return false;
            }
        }

        // Only validate revision verses if both surah and verse are provided
        if (revisionFromSurahId && revisionToSurahId && revisionFromVerse && revisionToVerse) {
            const revisionFromSurah = surahsMap.get(revisionFromSurahId);
            const revisionToSurah = surahsMap.get(revisionToSurahId);
            
            if (revisionFromVerse < 1 || revisionFromVerse > revisionFromSurah.total_verses) {
                e.preventDefault();
                alert('{{ t("invalid_revision_from_verse") }}');
                return false;
            }
            
            if (revisionToVerse < 1 || revisionToVerse > revisionToSurah.total_verses) {
                e.preventDefault();
                alert('{{ t("invalid_revision_to_verse") }}');
                return false;
            }
            
            if (revisionFromSurahId === revisionToSurahId && revisionFromVerse > revisionToVerse) {
                e.preventDefault();
                alert('{{ t("revision_from_verse_must_be_less_than_to_verse") }}');
                return false;
            }
            
            if (revisionFromSurahId > revisionToSurahId) {
                e.preventDefault();
                alert('{{ t("revision_from_surah_must_be_before_to_surah") }}');
                return false;
            }
        }
    });
});
</script>
@endsection