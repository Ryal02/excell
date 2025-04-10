@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Available Batches</h2>

        <!-- Display batches as clickable badges -->
        <div class="d-flex flex-wrap gap-2">
            @foreach($batches as $batch)
                <a href="{{ route('members.batch', ['batch' => $batch->batch]) }}" class="badge p-3 text-white batch-badge" data-batch="{{ $batch->batch }}">
                    Batch {{ $batch->batch }}
                </a>
            @endforeach
        </div>
    </div>

    <script>
        // Function to generate random color in HEX format
        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        // Apply random background color to each batch badge
        document.querySelectorAll('.batch-badge').forEach(function(badge) {
            badge.style.backgroundColor = getRandomColor();
            badge.style.textDecoration = 'none'; // Remove underline
        });
    </script>
@endsection
