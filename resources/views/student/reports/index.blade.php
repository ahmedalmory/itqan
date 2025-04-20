@extends('layouts.dashboard')

@section('dashboard-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">{{ t('daily_reports') }}</h1>
    <a href="{{ route('student.reports.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> {{ t('create_report') }}
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($reports->isEmpty())
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i> {{ t('no_reports_found') }}
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ t('date') }}</th>
                            <th>{{ t('memorization') }}</th>
                            <th>{{ t('revision') }}</th>
                            <th>{{ t('grade') }}</th>
                            <th>{{ t('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->report_date->format('Y-m-d') }}</td>
                                <td>{{ number_format($report->memorization_parts, 2) }}</td>
                                <td>{{ number_format($report->revision_parts, 2) }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($report->grade >= 90)
                                            <span class="badge bg-success me-2">{{ number_format($report->grade, 1) }}</span>
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @elseif($report->grade >= 75)
                                            <span class="badge bg-info me-2">{{ number_format($report->grade, 1) }}</span>
                                        @elseif($report->grade >= 60)
                                            <span class="badge bg-primary me-2">{{ number_format($report->grade, 1) }}</span>
                                        @else
                                            <span class="badge bg-danger me-2">{{ number_format($report->grade, 1) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('student.reports.show', $report) }}" class="btn btn-sm btn-outline-primary me-2">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('student.reports.edit', $report) }}" class="btn btn-sm btn-outline-secondary me-2">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('student.reports.destroy', $report) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ t('confirm_delete_report') }}')">
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
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 