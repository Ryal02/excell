@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Upload & Manage Members</h2>

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

    <div class="d-flex flex-wrap align-items-center justify-content-start mb-3 gap-2">
        <!-- Action Buttons -->
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
            Upload Excel
        </a>
        <a href="{{ url('export') }}" class="btn btn-success">Export to Excel</a>
        <a href="#" id="showFormButton" class="btn btn-success">Add Data</a>

        <!-- Search Form aligned to the end -->
        <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2 ms-auto">
            <input type="text" name="search" placeholder="Search..." class="form-control w-auto" value="{{ request()->search }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

    </div>


    <!-- Table -->
    <div id="addFormPage" style="display:none;">
        @include('dashboard.form')
    </div>
    <div id="tableDisplay" class='table-responsive' style='max-width 100%; overflow-x: auton;'>
        <table class="table table-sm table-bordered table-striped table-hover mb-0 w-full small">
            <thead class='table-dark text-nowrap'>
                <tr>
                    <th>BARANGAY</th>
                    <th>SLP</th>
                    <th>MEMBER</th>
                    <th>AGE</th>
                    <th>GENDER</th>
                    <th>BIRTHDATE</th>
                    <th>SITIO/ZONE</th>
                    <th>CELLPHONE</th>
                    <th>D2</th>
                    <th>BRGY D2</th>
                    <th>D1</th>
                    <th>BRGY D1</th>
                    <th>DEPENDENT</th>
                    <th>DEP_AGE</th>
                    <th>DEP_D2</th>
                    <th>DEP_BRGY_D2</th>
                    <th>DEP_D1</th>
                    <th>DEP_BRGY_D1</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                    <tr data-member-id="{{ $member->id }}">
                        <td>{{ $member->barangay }}</td>
                        <td>{{ $member->slp }}</td>
                        <td>{{ $member->member }}</td>
                        <td>{{ $member->age }}</td>
                        <td>{{ $member->gender }}</td>
                        <td>{{ $member->birthdate }}</td>
                        <td>{{ $member->sitio_zone }}</td>
                        <td>{{ $member->cellphone }}</td>
                        <td>{{ $member->d2 }}</td>
                        <td>{{ $member->brgy_d2 }}</td>
                        <td>{{ $member->d1 }}</td>
                        <td>{{ $member->brgy_d1 }}</td>
                        <td>{{ $member->dependents->pluck('dependents')->implode(', ') }}</td>
                        <td>{{ $member->dependents->pluck('dep_age')->implode(', ') }}</td>
                        <td>{{ $member->dependents->pluck('dep_d2')->implode(', ') }}</td>
                        <td>{{ $member->dependents->pluck('dep_brgy_d2')->implode(', ') }}</td>
                        <td>{{ $member->dependents->pluck('dep_d1')->implode(', ') }}</td>
                        <td>{{ $member->dependents->pluck('dep_brgy_d1')->implode(', ') }}</td>
                        <td colspan='2' > 
                            <div class='d-flex justify-content-center gap-2'>
                                <button type='button' class='btn btn-primary' onclick="showEditForm({{ $member->id }})">
                                    Update
                                </button>
                                <button type='button' class='btn btn-danger'>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="17">No members found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $members->links('pagination::bootstrap-4') }}
            <div class='mt-2 ms-5'>
                <strong>Total Members: </strong>{{ $members->total() }} <!-- Total count -->
            </div>
        </div>
    </div>
    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ url('import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Upload Excel File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- File input -->
                        <div class="mb-3">
                            <label for="file" class="form-label">Excel File</label>
                            <input type="file" name="file" id="file" class="form-control" required>
                        </div>

                        <!-- Batch Name -->
                        <div class="mb-3">
                            <label for="batch" class="form-label">Batch Name</label>
                            <input type="number" name="batch" id="batch" class="form-control" placeholder="Enter batch Number" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById("showFormButton").addEventListener("click", function(event) {
        event.preventDefault();
        $('#addFormPage').slideToggle(); // Toggle the form visibility
        $('#tableDisplay').hide();
    });
    $(document).ready(function () {
        setTimeout(function () {
            $('#success-alert').fadeOut('slow');
            $('#error-alert').fadeOut('slow');
        }, 2000);
    });
</script>
@endsection
