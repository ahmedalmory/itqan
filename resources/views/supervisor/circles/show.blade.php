@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $circle->name }}</h5>
                    <div>
                        <a href="{{ route('supervisor.circles.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left"></i> {{ t('Back to Circles') }}
                        </a>
                        <a href="{{ route('supervisor.circles.edit', $circle) }}" class="btn btn-sm btn-success">
                            <i class="bi bi-pencil"></i> {{ t('Edit Circle') }}
                        </a>
                        <a href="{{ route('supervisor.circles.manage-students', $circle) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-people"></i> {{ t('Manage Students') }}
                        </a>
                    </div>
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

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>{{ t('Circle Information') }}</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th>{{ t('Department') }}</th>
                                    <td>{{ $circle->department->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ t('Teacher') }}</th>
                                    <td>{{ $circle->teacher ? $circle->teacher->name : t('Not Assigned') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ t('Maximum Students') }}</th>
                                    <td>{{ $circle->max_students ?: t('Unlimited') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ t('Current Students') }}</th>
                                    <td>{{ $circle->students()->count() }}</td>
                                </tr>
                                <tr>
                                    <th>{{ t('Circle Time') }}</th>
                                    <td>{{ $circle->circle_time ?: t('Not specified') }}</td>
                                </tr>
                                @if($circle->whatsapp_group)
                                <tr>
                                    <th>{{ t('WhatsApp Group') }}</th>
                                    <td>
                                        <a href="{{ $circle->whatsapp_group }}" target="_blank">{{ $circle->whatsapp_group }}</a>
                                    </td>
                                </tr>
                                @endif
                                @if($circle->telegram_group)
                                <tr>
                                    <th>{{ t('Telegram Group') }}</th>
                                    <td>
                                        <a href="{{ $circle->telegram_group }}" target="_blank">{{ $circle->telegram_group }}</a>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>{{ t('Circle Description') }}</h6>
                            <div class="card">
                                <div class="card-body">
                                    {{ $circle->description ?: t('No description provided.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6>{{ t('Students in this Circle') }}</h6>
                    @if($students->isEmpty())
                        <div class="alert alert-info">
                            {{ t('No students enrolled in this circle yet.') }}
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ t('Name') }}</th>
                                        <th>{{ t('Email') }}</th>
                                        <th>{{ t('Phone') }}</th>
                                        <th>{{ t('Points') }}</th>
                                        <th>{{ t('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->email }}</td>
                                            <td>{{ $student->phone }}</td>
                                            <td>
                                                @if($student->studentPoints->isNotEmpty())
                                                    {{ $student->studentPoints->first()->points }}
                                                @else
                                                    0
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('supervisor.circles.students.view', ['circle' => $circle, 'student' => $student]) }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i> {{ t('View') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $students->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 