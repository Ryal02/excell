@extends('layouts.app')

@section('content')
<div class="container mt-4 position-relative">
    <!-- Reset Button -->
    <a href="javascript:void(0);" id="resetButton" class="btn btn-primary position-absolute top-0 end-0" style="display: none;">Reset</a>

    <!-- Batch Section -->
    <div id="batchSection">
        <h2>Available Batches</h2>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('getslp.all') }}" class="badge p-3 text-white batch-badge">
                All
            </a>
            @foreach($batches as $batch)
                <a href="javascript:void(0);" 
                class="badge p-3 text-white batch-badge" 
                data-batch="{{ $batch->batch }}">
                    Batch {{ $batch->batch }}
                </a>
            @endforeach
        </div>
        <button id="skipBatch" class="btn btn-dark-custom mt-2" style="display: none;">Skip</button>
    </div>
    <!-- District Section -->
    <div id="districtSection" style="display: none;">
        <h5>Select District</h5>
        <div class="d-flex flex-wrap gap-2">
            <a href="javascript:void(0);" 
               class="badge p-3 text-white district-badge" 
               data-district="2">
                DISTRICT 2
            </a>
            <a href="javascript:void(0);" 
               class="badge p-3 text-white district-badge" 
               data-district="1">
                DISTRICT 1
            </a>
        </div>
        <button id="skipDistrict" class="btn btn-dark-custom mt-2" style="display: none;">Skip</button>
    </div>

    <!-- Good/Bad Section -->
    <div id="goodBadSection" style="display: none;">
        <h5>Select Data Quality</h5>
        <div class="d-flex flex-wrap gap-2">
            <a href="javascript:void(0);" 
               class="badge p-3 text-white good-bad-badge" 
               data-good-bad="Good">
                Good
            </a>
            <a href="javascript:void(0);" 
               class="badge p-3 text-white good-bad-badge" 
               data-good-bad="Bad">
                Bad
            </a>
        </div>
        <button id="skipGoodBad" class="btn btn-dark-custom mt-2" style="display: none;">Skip</button>
    </div>

    <!-- SLP Section -->
    <div id="slpSection" style="display: none;">
        <h4>Select SLP</h4>
        <div class="d-flex flex-wrap gap-2 mb-3">
            <select class="btn btn-secondary" id="viewgoodSLP">
                <option value="">Select SLP</option>
                <option value="All">All</option>
                <!-- Loop through the slpGood variable only if it's passed -->
                @if(isset($slpGood) && $slpGood->isNotEmpty())
                    @foreach($slpGood as $slp)
                        <option value="{{ $slp }}">{{ $slp }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <!-- Summary Section -->
    <div id="summarySection" class="mt-4" style="display: none;">
        <p id="summaryList" class="d-flex flex-column mb-3"></p> <!-- Paragraph for inline text -->
        <div id="dynamicTableContainer"></div>
    </div>
    <table id="slpList" class="mt-3 table table-sm table-bordered table-hover mb-10 w-full small">
        <thead class='table-dark text-nowrap'>
            <tr>
                <th>BATCH</th>
                <th>BARANGAY</th>
                <th>SLP</th>
                <th>ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($slpList as $slpItem)
                <tr data-member-id="{{ $slpItem['id'] }}">
                    <td>{{ implode(', ', $slpItem['batches']->toArray()) }}</td>
                    <td>{{ implode(', ', $slpItem['barangays']->toArray()) }}</td>
                    <td>{{ $slpItem['slp'] }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-primary btn-sm"
                                onclick="openEditModal('{{ $slpItem['id'] }}', '{{ $slpItem['slp'] }}')">
                                Update
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No SLP data found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
<!-- Edit SLP Modal -->
<div class="modal fade" id="editSlpModal" tabindex="-1" role="dialog" aria-labelledby="editSlpModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="editSlpForm" method="POST" action="{{ route('slp.update') }}">
      @csrf
      @method('PUT')
      <input type="hidden" name="id" id="slp-id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editSlpModalLabel">Edit SLP</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="slp-name">SLP Name</label>
            <input type="text" class="form-control" id="slp-name" name="slp" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function openEditModal(id, slpName) {
        document.getElementById('slp-id').value = id;
        document.getElementById('slp-name').value = slpName;
        const modal = new bootstrap.Modal(document.getElementById('editSlpModal'));
        modal.show();
    }
</script>
<script>
    $(document).ready(function () {
        let selectedBatch = null;

        // Listen for batch selection
        $('.batch-badge').on('click', function () {
            selectedBatch = $(this).data('batch'); // Get selected batch
            $('#batchSection').hide();

            // Make an AJAX request to fetch SLPs based on the selected batch
            $.ajax({
                url: '/get-slp-options', // The URL where the backend function will return SLPs based on batch
                type: 'GET',
                data: {
                    batch: selectedBatch
                },
                success: function(response) {
                    // Clear the current SLP dropdown and add the new options
                    $('#viewgoodSLP').empty().append('<option value="">Select SLP</option><option value="All">All</option>');

                    // Populate the dropdown with the received SLP options
                    response.slpGood.forEach(function(slp) {
                        $('#viewgoodSLP').append('<option value="' + slp + '">' + slp + '</option>');
                    });
                },
                error: function() {
                    alert('Error fetching SLP options.');
                }
            });
        });
    });
</script>
<script>
    let selectedBatch = null;
    let selectedDistrict = null;
    let selectedSlp = null;
    let selectedGoodBad = null;

    // Function to update the summary section
    function updateSummary() {
        $('#summaryList').empty();

        let summary = '';

        if (selectedBatch) {
            summary += `Batch: ${selectedBatch}`;
        }
        if (selectedDistrict) {
            summary += `, District: ${selectedDistrict}`;
        }
        if (selectedGoodBad) {
            summary += `, ${selectedGoodBad} Data`;
        }
        if (selectedSlp) {
            summary += `, SLP: ${selectedSlp}`;
        }

        $('#summaryList').text(summary);
        $('#summarySection').show();
    }

    // Function to reset all selections
    function resetAll() {
        selectedBatch = null;
        selectedDistrict = null;
        selectedSlp = null;
        selectedGoodBad = null;

        $('.batch-badge, .district-badge, .good-bad-badge').css({ backgroundColor: '', color: 'white' });
        $('#viewgoodSLP').val('');
        $('#dynamicTableContainer').hide().empty();
        $('#summarySection').hide();
        $('#summaryList').empty();

        $('#batchSection').show();
        $('#districtSection').hide();
        $('#goodBadSection').hide();
        $('#slpSection').hide();
        $('#resetButton').hide();
        $('#skipBatch').hide();
        $('#skipDistrict').hide();
        $('#skipGoodBad').hide();
        $('#slpList').show();
    }

    // Show Reset Button
    function showResetButton() {
        $('#resetButton').show();
    }

    $(document).ready(function () {
        // Reset button click event
        $('#resetButton').on('click', function () {
            resetAll();
        });

        // Skip buttons click events
        $('#skipBatch').on('click', function () {
            selectedBatch = null;
            $('#batchSection').hide();
            $('#districtSection').show();
            $('#skipDistrict').show();
            $('#slpList').hide();
            updateSummary();
        });

        $('#skipDistrict').on('click', function () {
            selectedDistrict = null;
            $('#districtSection').hide();
            $('#goodBadSection').show();
            $('#skipGoodBad').show();
            $('#slpList').hide();
            updateSummary();
        });

        $('#skipGoodBad').on('click', function () {
            selectedGoodBad = null;
            $('#slpList').hide();
            $('#goodBadSection').hide();
            $('#slpSection').show();
            updateSummary();
        });

        // Batch select click event
        $('.batch-badge').on('click', function () {
            $('.batch-badge').css({ backgroundColor: '#000', color: 'white' });
            $(this).css({ backgroundColor: 'gray', color: 'black' });

            selectedBatch = $(this).data('batch');
            $('#batchSection').hide();
            $('#districtSection').show();
            $('#skipDistrict').show();
            $('#slpList').hide();

            showResetButton(); // Show the reset button when a selection is made

            updateSummary();
        });

        // District select click event
        $('.district-badge').on('click', function () {
            $('.district-badge').css({ backgroundColor: '#003366', color: 'white' });
            $(this).css({ backgroundColor: 'lightblue', color: 'black' });

            selectedDistrict = $(this).data('district');
            $('#districtSection').hide();
            $('#goodBadSection').show();
            $('#skipGoodBad').show();
            $('#slpList').hide();

            showResetButton(); // Show the reset button when a selection is made

            updateSummary();
        });

        // Good/Bad select click event
        $('.good-bad-badge').on('click', function () {
            $('.good-bad-badge').css({ backgroundColor: '#000', color: 'white' });
            $(this).css({ backgroundColor: 'gray', color: 'black' });

            selectedGoodBad = $(this).data('good-bad');
            $('#goodBadSection').hide();
            $('#slpSection').show();
            $('#slpList').hide();

            showResetButton(); // Show the reset button when a selection is made

            updateSummary();
        });

        // SLP dropdown change event
        $('#viewgoodSLP').on('change', function () {
            selectedSlp = $(this).val();

            if (selectedSlp) {
                let url = selectedSlp === "All"
                    ? '/members/slp/all'
                    : `/members/slp/${selectedSlp}/dependents-good`;

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        batch: selectedBatch,
                        district: selectedDistrict,
                        good_bad: selectedGoodBad
                    },
                    success: function (data) {
                        $('#dynamicTableContainer').html(data).show();
                        updateSummary();
                    },
                    error: function () {
                        alert('An error occurred while fetching the data.');
                    }
                });
            } else {
                $('#dynamicTableContainer').hide().empty();
            }

            showResetButton(); // Show the reset button when a selection is made
        });
    });
</script>

<style>
    .batch-badge, .district-badge, .good-bad-badge {
        background-color: #6c757d;
        color: white;
        text-decoration: none;
    }

    .btn-dark-custom {
        background-color: #000;  /* Dark color */
        color: white; /* White text */
        border: none; /* Remove border */
    }

    .btn-dark-custom:hover {
        background-color: gray; /* Slightly darker on hover */
    }

    #summarySection {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    #summaryList {
        display: inline;
        font-size: 16px;
    }

    #dynamicTableContainer {
        display: flex;
        flex-direction: column;
    }
</style>

@endsection
