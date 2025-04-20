@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <h1 class="display-4 mb-4">{{ t('coming_soon') }}</h1>
            <p class="lead">{{ t('feature_under_development') }}</p>
            <div class="mt-4">
                <a href="{{ url()->previous() }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i> {{ t('go_back') }}
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary ms-2">
                    <i class="bi bi-house"></i> {{ t('home') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection