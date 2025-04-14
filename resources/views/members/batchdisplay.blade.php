@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Available Batches</h2>

        <!-- Display batches as clickable badges -->
        <div class="d-flex flex-wrap gap-4">
            @foreach($batches as $batch)
                <div class="badge-container" style="position: relative;">
                    <!-- Batch Badge -->
                    <a href="{{ route('members.batch', ['batch' => $batch->batch]) }}" class="badge p-3 text-white batch-badge" data-batch="{{ $batch->batch }}">
                        Batch {{ $batch->batch }}
                    </a>
                    
                    <!-- Delete Button -->
                    <button class="btn btn-sm btn-danger delete-batch" data-batch="{{ $batch->batch }}" style="position: absolute; top: -8px; right: -8px; border-radius: 50%; padding: 0 5px; font-size: 10px;">
                       x
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        <script>
        // Handle delete button click
        document.querySelectorAll('.delete-batch').forEach(function(button) {
            button.addEventListener('click', function(e) {
                const batch = e.target.getAttribute('data-batch');

                // SweetAlert2 confirmation
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete Batch ${batch} and all associated data.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with deleting the batch data
                        deleteBatch(batch);
                    }
                });
            });
        });

        function deleteBatch(batch) {
    fetch(`/batch/${batch}/delete`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Debug output
        if (data.success) {
            Swal.fire(
                'Deleted!',
                `Batch ${batch} and its associated data have been deleted.`,
                'success'
            );

            const badgeContainer = document.querySelector(`[data-batch="${batch}"]`).parentElement;
            badgeContainer.remove();
        } else {
            Swal.fire(
                'Error!',
                'There was an issue deleting the batch. Please try again later.',
                'error'
            );
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        Swal.fire(
            'Error!',
            'There was an issue with the request.',
            'error'
        );
    });
}

    </script>
@endsection
