@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ t('Student Details') }}: {{ $student->name }}</h5>
                    <div>
                        <a href="{{ route('supervisor.circles.show', $circle) }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left"></i> {{ t('Back to Circle') }}
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
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">{{ t('Student Information') }}</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>{{ t('Name') }}</th>
                                            <td>{{ $student->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ t('Email') }}</th>
                                            <td>{{ $student->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ t('Phone') }}</th>
                                            <td>{{ $student->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ t('Age') }}</th>
                                            <td>{{ $student->age }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ t('Gender') }}</th>
                                            <td>{{ $student->gender }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ t('Country') }}</th>
                                            <td>{{ $student->country ? $student->country->name : t('Not specified') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">{{ t('Performance Points') }}</h6>
                                </div>
                                <div class="card-body">
                                    @if($points)
                                        <div class="row text-center">
                                            <div class="col">
                                                <div class="card bg-light mb-3">
                                                    <div class="card-body">
                                                        <h3 class="text-primary mb-0">{{ $points->points }}</h3>
                                                        <p class="mb-0">{{ t('Total Points') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card bg-light mb-3">
                                                    <div class="card-body">
                                                        <h3 class="text-success mb-0">{{ $points->attendance }}</h3>
                                                        <p class="mb-0">{{ t('Attendance') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card bg-light mb-3">
                                                    <div class="card-body">
                                                        <h3 class="text-info mb-0">{{ $points->memorization }}</h3>
                                                        <p class="mb-0">{{ t('Memorization') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row text-center">
                                            <div class="col">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h3 class="text-warning mb-0">{{ $points->recitation }}</h3>
                                                        <p class="mb-0">{{ t('Recitation') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h3 class="text-danger mb-0">{{ $points->tajweed }}</h3>
                                                        <p class="mb-0">{{ t('Tajweed') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h3 class="text-secondary mb-0">{{ $points->behavior }}</h3>
                                                        <p class="mb-0">{{ t('Behavior') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            {{ t('No points recorded for this student yet.') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">{{ t('Daily Reports') }}</h6>
                        </div>
                        <div class="card-body">
                            @if($reports->isEmpty())
                                <div class="alert alert-info">
                                    {{ t('No daily reports available for this student.') }}
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>{{ t('Date') }}</th>
                                                <th>{{ t('Memorization Parts') }}</th>
                                                <th>{{ t('Revision Parts') }}</th>
                                                <th>{{ t('Grade') }}</th>
                                                <th>{{ t('From Surah') }}</th>
                                                <th>{{ t('From Verse') }}</th>
                                                <th>{{ t('To Surah') }}</th>
                                                <th>{{ t('To Verse') }}</th>
                                                <th>{{ t('Notes') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reports as $report)
                                                <tr>
                                                    <td>{{ $report->report_date }}</td>
                                                    <td>{{ $report->memorization_parts }}</td>
                                                    <td>{{ $report->revision_parts }}</td>
                                                    <td>{{ $report->grade }}</td>
                                                    <td>{{ optional($report->fromSurah)->name ?? 'N/A' }}</td>
                                                    <td>{{ $report->memorization_from_verse }}</td>
                                                    <td>{{ optional($report->toSurah)->name ?? 'N/A' }}</td>
                                                    <td>{{ $report->memorization_to_verse }}</td>
                                                    <td>{{ $report->notes }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $reports->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 