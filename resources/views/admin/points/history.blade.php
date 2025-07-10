@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('Points History for') }} {{ $student->name }}</h1>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-info btn-sm" onclick="generatePointsSummary()">
                <i class="bi bi-image"></i> {{ t('generate_students_summary') }}
            </button>
            <a href="{{ route('admin.points.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> {{ t('Back to Points') }}
            </a>
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

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.points.history', $student->id) }}" class="row g-3 align-items-center" id="filterForm">
                <div class="col-md-4">
                    <select name="circle_id" class="form-select" onchange="this.form.submit()">
                        <option value="">{{ t('All Circles') }}</option>
                        @foreach($circles as $circle)
                            <option value="{{ $circle->id }}" {{ request('circle_id') == $circle->id ? 'selected' : '' }}>
                                {{ $circle->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}" placeholder="{{ t('from_date') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}" placeholder="{{ t('to_date') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">{{ t('Filter') }}</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if($history->isEmpty())
                <div class="alert alert-info">
                    {{ t('No points history found') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ t('Date') }}</th>
                                <th>{{ t('Circle') }}</th>
                                <th>{{ t('Points') }}</th>
                                <th>{{ t('Action') }}</th>
                                <th>{{ t('Notes') }}</th>
                                <th>{{ t('Added By') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $record)
                                <tr>
                                    <td>{{ $record->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $record->circle->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $record->points >= 0 ? 'success' : 'danger' }}">
                                            {{ $record->points }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $record->action_type === 'add' ? 'success' : 'danger' }}">
                                            {{ t($record->action_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $record->notes }}</td>
                                    <td>{{ $record->creator->name ?? t('N/A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $history->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Points Summary Modal -->
<div class="modal fade" id="pointsSummaryModal" tabindex="-1" aria-labelledby="pointsSummaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pointsSummaryModalLabel">{{ t('points_summary') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="pointsSummaryContent" class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">{{ t('loading') }}</span>
                    </div>
                    <p class="mt-2">{{ t('generating_summary') }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ t('close') }}</button>
                <button type="button" class="btn btn-primary" id="downloadPointsSummaryBtn" style="display: none;">
                    <i class="bi bi-download"></i> {{ t('download') }}
                </button>
                <button type="button" class="btn btn-success" id="sharePointsSummaryBtn" style="display: none;">
                    <i class="bi bi-share"></i> {{ t('share') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function generatePointsSummary() {
    const modal = new bootstrap.Modal(document.getElementById('pointsSummaryModal'));
    modal.show();
    
    // Get current filters
    const formData = new FormData(document.getElementById('filterForm'));
    const params = new URLSearchParams(formData);
    
    // Add export parameter
    params.append('export', 'summary');
    
    // Get the current student ID from the URL
    const studentId = {{ $student->id }};
    
    fetch(`{{ route('admin.points.history', $student->id) }}?${params.toString()}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const content = document.getElementById('pointsSummaryContent');
        const downloadBtn = document.getElementById('downloadPointsSummaryBtn');
        const shareBtn = document.getElementById('sharePointsSummaryBtn');
        
        if (data.success) {
            content.innerHTML = `
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> {{ t('summary_generated_successfully') }}
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">${data.stats.total_points}</h5>
                                <p class="card-text">{{ t('total_points') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">${data.stats.total_history_records}</h5>
                                <p class="card-text">{{ t('total_history_records') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <h6>{{ t('points_breakdown') }}</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ t('circle') }}</th>
                                    <th>{{ t('total_points') }}</th>
                                    <th>{{ t('records_count') }}</th>
                                    <th>{{ t('last_activity') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.circles_breakdown.map(circle => `
                                    <tr>
                                        <td>${circle.circle_name}</td>
                                        <td><span class="badge bg-${circle.total_points >= 0 ? 'success' : 'danger'}">${circle.total_points}</span></td>
                                        <td>${circle.records_count}</td>
                                        <td>${circle.last_activity}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
            
            downloadBtn.style.display = 'inline-block';
            shareBtn.style.display = 'inline-block';
            
            // Store data for download/share
            window.pointsSummaryData = data;
        } else {
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> {{ t('error_generating_summary') }}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('pointsSummaryContent').innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> {{ t('error_generating_summary') }}
            </div>
        `;
    });
}

// Download functionality
document.getElementById('downloadPointsSummaryBtn').addEventListener('click', function() {
    if (window.pointsSummaryData) {
        const dataStr = JSON.stringify(window.pointsSummaryData, null, 2);
        const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
        
        const exportFileDefaultName = `points_summary_${new Date().toISOString().split('T')[0]}.json`;
        
        const linkElement = document.createElement('a');
        linkElement.setAttribute('href', dataUri);
        linkElement.setAttribute('download', exportFileDefaultName);
        linkElement.click();
    }
});

// Share functionality
document.getElementById('sharePointsSummaryBtn').addEventListener('click', function() {
    if (window.pointsSummaryData && navigator.share) {
        navigator.share({
            title: '{{ t('points_summary') }}',
            text: `{{ t('points_summary') }} - {{ $student->name }}: ${window.pointsSummaryData.stats.total_points} {{ t('points') }}`,
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        const summaryText = `{{ t('points_summary') }} - {{ $student->name }}\n{{ t('total_points') }}: ${window.pointsSummaryData.stats.total_points}\n{{ t('total_history_records') }}: ${window.pointsSummaryData.stats.total_history_records}`;
        
        navigator.clipboard.writeText(summaryText).then(() => {
            alert('{{ t('summary_copied_to_clipboard') }}');
        });
    }
});
</script>
@endpush
@endsection 