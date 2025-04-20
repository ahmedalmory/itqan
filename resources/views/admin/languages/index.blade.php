@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">{{ t('Languages Management') }}</h1>
        <a href="{{ route('admin.languages.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> {{ t('Add Language') }}
        </a>
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

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>{{ t('Name') }}</th>
                            <th>{{ t('Code') }}</th>
                            <th>{{ t('Direction') }}</th>
                            <th>{{ t('Status') }}</th>
                            <th class="text-end">{{ t('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($languages as $language)
                            <tr>
                                <td>{{ $language->name }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $language->code }}</span>
                                </td>
                                <td>
                                    @if($language->direction == 'ltr')
                                        <span class="badge bg-info">LTR</span>
                                    @else
                                        <span class="badge bg-info">RTL</span>
                                    @endif
                                </td>
                                <td>
                                    @if($language->is_active)
                                        <span class="badge bg-success">{{ t('Active') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ t('Inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                        <form action="{{ route('admin.languages.toggle', $language) }}" method="POST" class="me-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $language->is_active ? 'btn-warning' : 'btn-success' }}" title="{{ $language->is_active ? t('Deactivate') : t('Activate') }}">
                                                <i class="bi {{ $language->is_active ? 'bi-x-circle' : 'bi-check-circle' }}"></i>
                                            </button>
                                        </form>
                                        
                                        <a href="{{ route('admin.languages.edit', $language) }}" class="btn btn-sm btn-info me-2" title="{{ t('Edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        @if(!in_array($language->code, ['en', 'ar']))
                                            <form action="{{ route('admin.languages.destroy', $language) }}" method="POST" onsubmit="return confirm('{{ t('Are you sure you want to delete this language?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="{{ t('Delete') }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">{{ t('No languages found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 