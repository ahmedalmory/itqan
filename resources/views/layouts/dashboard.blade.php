@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-9">
            @if(isset($pageTitle))
                <h1 class="page-title mb-4">{{ $pageTitle }}</h1>
            @endif
            
            @yield('dashboard-content')
        </div>
    </div>
</div>
@endsection 