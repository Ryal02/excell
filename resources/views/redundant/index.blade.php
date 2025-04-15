@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Redundant Batches</h4>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('redundant.all') }}"  class="badge p-3 text-white batch-badge">
            All
        </a>
        @foreach($batches as $batch)
            <a href="{{ route('redundant.batch', $batch) }}" class="badge p-3 text-white batch-badge" data-batch="{{ $batch }}">
                Batch {{ $batch }}
            </a>
            <button class="btn btn-sm btn-danger delete-batch" data-batch="{{ $batch }}" style="position: absolute; top: -8px; right: -8px; border-radius: 50%; padding: 0 5px; font-size: 10px;">
                x
            </button>
        @endforeach
    </div>
</div>
<style>
    .batch-badge, .district-badge, .good-bad-badge {
        background-color: #6c757d;
        color: white;
        text-decoration: none;
    }
</style>
@endsection
