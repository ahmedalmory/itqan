@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('Study Circles') }}</h1>
        <a href="{{ route('admin.circles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> {{ t('Create Circle') }}
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

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.circles.index') }}" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="{{ t('Search circles...') }}" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="department_id" class="form-select">
                        <option value="">{{ t('All Departments') }}</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">{{ t('Filter') }}</button>
                    <a href="{{ route('admin.circles.index') }}" class="btn btn-outline-secondary">{{ t('Clear') }}</a>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if($circles->isEmpty())
                <div class="alert alert-info">
                    {{ t('No study circles found') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ t('Name') }}</th>
                                <th>{{ t('Department') }}</th>
                                <th>{{ t('Teacher') }}</th>
                                <th>{{ t('Students') }}</th>
                                <th>{{ t('Status') }}</th>
                                <th>{{ t('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($circles as $circle)
                                <tr>
                                    <td>{{ $circle->name }}</td>
                                    <td>
                                        @if($circle->department)
                                            <a href="{{ route('admin.departments.show', $circle->department) }}">
                                                {{ $circle->department->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">{{ t('Not assigned') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($circle->teacher)
                                            {{ $circle->teacher->name }}
                                        @else
                                            <span class="text-danger">{{ t('No teacher assigned') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $circle->students->count() }}
                                    </td>
                                    <td>
                                        @if($circle->is_active)
                                            <span class="badge bg-success">{{ t('Active') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ t('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.circles.show', $circle) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.circles.edit', $circle) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('admin.circles.add-student', $circle) }}" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-person-plus"></i>
                                            </a>
                                            <form action="{{ route('admin.circles.destroy', $circle) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ t('Are you sure you want to delete this circle?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $circles->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 