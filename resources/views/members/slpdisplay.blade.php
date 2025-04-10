@extends('layouts.app')

@section('content')
<div class="container mt-4 position-relative">
    <!-- Reset Button -->
    <a href="javascript:void(0);" id="resetButton" class="btn btn-primary position-absolute top-0 end-0" style="display: none;">Reset</a>

    <!-- Batch Section -->
    <div id="batchSection">
        <h2>Available Batches</h2>
        <div class="d-flex flex-wrap gap-2">
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
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            updateSummary();
        });

        $('#skipDistrict').on('click', function () {
            selectedDistrict = null;
            $('#districtSection').hide();
            $('#goodBadSection').show();
            $('#skipGoodBad').show();
            updateSummary();
        });

        $('#skipGoodBad').on('click', function () {
            selectedGoodBad = null;
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
