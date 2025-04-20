@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">{{ t('Translations Management') }}</h1>
        <div>
            <a href="{{ route('admin.translations.create') }}" class="btn btn-primary me-2">
                <i class="bi bi-plus-lg me-1"></i> {{ t('Add Translation') }}
            </a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importExportModal">
                <i class="bi bi-gear me-1"></i> {{ t('Import/Export') }}
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.translations.index') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" placeholder="{{ t('Search translations...') }}" value="{{ $searchQuery ?? '' }}">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <select name="language" class="form-select" onchange="this.form.submit()">
                        @foreach($languages as $lang)
                            <option value="{{ $lang->code }}" {{ $selectedLanguage == $lang->code ? 'selected' : '' }}>
                                {{ $lang->name }} ({{ $lang->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-filter me-1"></i> {{ t('Filter') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>{{ t('Key') }}</th>
                            <th>{{ t('Value') }}</th>
                            <th class="text-end">{{ t('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($translations as $translation)
                            <tr>
                                <td class="text-wrap" style="max-width: 300px;">
                                    <code>{{ $translation->translation_key }}</code>
                                </td>
                                <td class="text-wrap" style="max-width: 400px;">
                                    {{ $translation->translation_value }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('admin.translations.edit', $translation) }}" class="btn btn-sm btn-info me-2" title="{{ t('Edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.translations.destroy', $translation) }}" method="POST" onsubmit="return confirm('{{ t('Are you sure you want to delete this translation?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="{{ t('Delete') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">{{ t('No translations found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $translations->appends(request()->except('page'))->links() }}
    </div>
</div>

<!-- Import/Export Modal -->
<div class="modal fade" id="importExportModal" tabindex="-1" aria-labelledby="importExportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="importExportModalLabel">{{ t('Import/Export Translations') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs mb-4" id="importExportTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="import-tab" data-bs-toggle="tab" data-bs-target="#import" type="button" role="tab" aria-controls="import" aria-selected="true">{{ t('Import') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="export-tab" data-bs-toggle="tab" data-bs-target="#export" type="button" role="tab" aria-controls="export" aria-selected="false">{{ t('Export') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="copy-tab" data-bs-toggle="tab" data-bs-target="#copy" type="button" role="tab" aria-controls="copy" aria-selected="false">{{ t('Copy Keys') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="generate-tab" data-bs-toggle="tab" data-bs-target="#generate" type="button" role="tab" aria-controls="generate" aria-selected="false">{{ t('Generate Files') }}</button>
                    </li>
                </ul>
                
                <div class="tab-content" id="importExportTabsContent">
                    <!-- Import Tab -->
                    <div class="tab-pane fade show active" id="import" role="tabpanel" aria-labelledby="import-tab">
                        <form action="{{ route('admin.translations.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="language_id" class="form-label">{{ t('Target Language') }}</label>
                                <select class="form-select" id="language_id" name="language_id" required>
                                    @foreach($languages as $lang)
                                        <option value="{{ $lang->id }}">{{ $lang->name }} ({{ $lang->code }})</option>
                                    @endforeach
                                </select>
                                <div class="form-text">{{ t('Select the language to import translations into') }}</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="import_file" class="form-label">{{ t('JSON File') }}</label>
                                <input class="form-control" type="file" id="import_file" name="import_file" accept=".json" required>
                                <div class="form-text">{{ t('Select a JSON file containing translations') }}</div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload me-1"></i> {{ t('Import Translations') }}
                            </button>
                        </form>
                    </div>
                    
                    <!-- Export Tab -->
                    <div class="tab-pane fade" id="export" role="tabpanel" aria-labelledby="export-tab">
                        <form action="{{ route('admin.translations.export') }}" method="GET">
                            <div class="mb-3">
                                <label for="language" class="form-label">{{ t('Language to Export') }}</label>
                                <select class="form-select" id="language" name="language" required>
                                    @foreach($languages as $lang)
                                        <option value="{{ $lang->code }}">{{ $lang->name }} ({{ $lang->code }})</option>
                                    @endforeach
                                </select>
                                <div class="form-text">{{ t('Select the language to export translations from') }}</div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-download me-1"></i> {{ t('Export Translations') }}
                            </button>
                        </form>
                    </div>
                    
                    <!-- Copy Keys Tab -->
                    <div class="tab-pane fade" id="copy" role="tabpanel" aria-labelledby="copy-tab">
                        <form action="{{ route('admin.translations.copy') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="source_language" class="form-label">{{ t('Source Language') }}</label>
                                <select class="form-select" id="source_language" name="source_language" required>
                                    @foreach($languages as $lang)
                                        <option value="{{ $lang->code }}">{{ $lang->name }} ({{ $lang->code }})</option>
                                    @endforeach
                                </select>
                                <div class="form-text">{{ t('Select the language to copy keys from') }}</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="target_language" class="form-label">{{ t('Target Language') }}</label>
                                <select class="form-select" id="target_language" name="target_language" required>
                                    @foreach($languages as $lang)
                                        <option value="{{ $lang->code }}">{{ $lang->name }} ({{ $lang->code }})</option>
                                    @endforeach
                                </select>
                                <div class="form-text">{{ t('Select the language to copy keys to') }}</div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-clipboard me-1"></i> {{ t('Copy Translation Keys') }}
                            </button>
                            
                            <div class="alert alert-info mt-3">
                                <i class="bi bi-info-circle me-2"></i>
                                {{ t('This will copy all translation keys from the source language to the target language. Only keys that do not exist in the target language will be created with empty values.') }}
                            </div>
                        </form>
                    </div>
                    
                    <!-- Generate Files Tab -->
                    <div class="tab-pane fade" id="generate" role="tabpanel" aria-labelledby="generate-tab">
                        <form action="{{ route('admin.translations.generate') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    {{ t('This will generate JSON language files for all active languages.') }}
                                </div>
                                
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    {{ t('This will overwrite any existing language files with the same names.') }}
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-file-earmark-code me-1"></i> {{ t('Generate Language Files') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 