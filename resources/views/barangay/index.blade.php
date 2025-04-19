@extends('layouts.app')

@section('content')
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" id="error-alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="container mt-4">
        <h2>Available Barangays</h2>

        <!-- Display batches as clickable badges -->
        <div class="d-flex flex-wrap gap-4">
            @foreach($barangays as $brgy)
                <div class="badge-container" style="position: relative;">
                    <!-- Batch Badge -->
                    <a href="{{ route('barangay.show', ['brgy' => $brgy->barangay]) }}" class="badge p-3 text-white batch-badge" data-batch="{{ $brgy->barangay }}">
                        {{ $brgy->barangay }}
                    </a>
                    
                    <!-- Delete Button -->
                    <button class="btn btn-sm btn-danger delete-batch" data-batch="{{ $brgy->barangay }}" style="position: absolute; top: -8px; right: -8px; border-radius: 50%; padding: 0 5px; font-size: 10px;">
                       x
                    </button>
                </div>
            @endforeach
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    document.querySelectorAll('.batch-badge').forEach(function(badge) {
        badge.style.backgroundColor = getRandomColor();
        badge.style.textDecoration = 'none'; // Remove underline
    });
</script>
@endsection
