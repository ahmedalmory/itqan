@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('Departments') }}</h1>
        <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> {{ t('Create Department') }}
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
            <form method="GET" action="{{ route('admin.departments.index') }}" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="{{ t('Search departments...') }}" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="gender_filter" class="form-select">
                        <option value="">{{ t('All Genders') }}</option>
                        <option value="male" {{ request('gender_filter') == 'male' ? 'selected' : '' }}>{{ t('Male') }}</option>
                        <option value="female" {{ request('gender_filter') == 'female' ? 'selected' : '' }}>{{ t('Female') }}</option>
                        <option value="mixed" {{ request('gender_filter') == 'mixed' ? 'selected' : '' }}>{{ t('Mixed') }}</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">{{ t('Filter') }}</button>
                    <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">{{ t('Clear') }}</a>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if($departments->isEmpty())
                <div class="alert alert-info">
                    {{ t('No departments found') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ t('Name') }}</th>
                                <th>{{ t('Student Gender') }}</th>
                                <th>{{ t('Circles') }}</th>
                                <th>{{ t('Students') }}</th>
                                <th>{{ t('Registration') }}</th>
                                <th>{{ t('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departments as $department)
                                <tr>
                                    <td>{{ $department->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $department->student_gender == 'male' ? 'primary' : ($department->student_gender == 'female' ? 'info' : 'secondary') }}">
                                            {{ ucfirst($department->student_gender) }}
                                        </span>
                                    </td>
                                    <td>{{ $department->circles_count }}</td>
                                    <td>{{ $department->students_count }}</td>
                                    <td>
                                        @if($department->registration_open)
                                            <span class="badge bg-success">{{ t('Open') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ t('Closed') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.departments.show', $department) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.departments.edit', $department) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ t('Are you sure you want to delete this department?') }}')">
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
                    {{ $departments->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 