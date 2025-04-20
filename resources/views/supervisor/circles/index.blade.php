@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ t('My Supervised Circles') }}</h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($circles->isEmpty())
                        <div class="alert alert-info">
                            {{ t('You are not supervising any circles at the moment.') }}
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ t('Name') }}</th>
                                        <th>{{ t('Department') }}</th>
                                        <th>{{ t('Teacher') }}</th>
                                        <th>{{ t('Students') }}</th>
                                        <th>{{ t('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($circles as $circle)
                                        <tr>
                                            <td>{{ $circle->name }}</td>
                                            <td>{{ $circle->department->name }}</td>
                                            <td>{{ $circle->teacher ? $circle->teacher->name : t('Not Assigned') }}</td>
                                            <td>{{ $circle->students()->count() }} / {{ $circle->max_students ?: t('Unlimited') }}</td>
                                            <td>
                                                <a href="{{ route('supervisor.circles.show', $circle) }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i> {{ t('View') }}
                                                </a>
                                                <a href="{{ route('supervisor.circles.edit', $circle) }}" class="btn btn-sm btn-success">
                                                    <i class="bi bi-pencil"></i> {{ t('Edit') }}
                                                </a>
                                                <a href="{{ route('supervisor.circles.manage-students', $circle) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-people"></i> {{ t('Manage Students') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 