<!-- resources/views/members/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Members</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-responsive {
            width: 100%;
            overflow-x: auto; /* Ensures horizontal scrolling */
        }
        .container {
            max-width: 100%; /* Make sure the container uses the full width */
        }
        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1 1 calc(12.5% - 15px); /* 8 inputs per row (100% / 8 = 12.5%) */
            min-width: 150px;
        }
       .form-box {
            display: none; /* Initially hidden */
            padding: 20px;
            border: 2px solid red;  /* Add border to easily see if it's there */
            background-color: #f9f9f9;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>IHAG SYSTEM</h1>

    @if(session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="color: red;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form to upload Excel file -->
        <form action="{{ url('import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" required>
            <button type="submit" class="btn btn-primary">Upload Excel</button>

            <!-- Export Button -->
            <a href="{{ url('export') }}" class="btn btn-success float-end ms-2">Export All Data to Excel</a>
            <button class="btn btn-success float-end " id="showFormButton">Add Data</button>
        </form>

   
        <!-- Search form -->
        <div class="d-flex mb-3 mt-3 justify-content-between">
            <form method="GET" action="{{ route('members.index') }}">
                <div class='d-flex'>
                    <input type="text" name="search" id="searchInput" placeholder="Search..." value="{{ request()->search }}" class="form-control w-auto">
                    <button type="submit" class="btn btn-primary ms-2">Search</button>
                </div>
            </form>
            <div>
                @php
                    $uniqueSlps = $slpmembers->pluck('slp')->unique();
                @endphp
                <button class="btn btn-info" id="viewD1">View Good D1</button>
                <select class="btn btn-info" id="viewgoodSLP">
                    <option value="">Select GOOD SLP</option>
                    <option value="All"> All </option>
                    @foreach($uniqueSlps as $slp)
                        <option value="{{ $slp }}">{{ $slp }}</option>
                    @endforeach
                </select>
                <select class="btn btn-info" id="viewSLP">
                    <option value="">Select BAD SLP</option>
                    <option value="All"> All </option>
                    @foreach($uniqueSlps as $slp)
                        <option value="{{ $slp }}">{{ $slp }}</option>
                    @endforeach
                </select>

                <button class="btn btn-info" id="viewListing">View Counts</button>
                <button class="btn btn-info" id="viewTable">View Table List</button>
            </div>
        </div>

        <div id="submitMessage" class="alert" style="display: none;"></div>

        <div class="form-box" id="formBox">
            <h4>Add New Member</h4>
            <form id="addMemberForm" action="{{ route('members.store') }}" method="POST">
                @csrf
                <div class="form-row mb-3">
                    <div class="form-group">
                        <label for="barangay" class="form-label">Barangay</label>
                        <input type="text" class="form-control" id="barangay" name="barangay">
                    </div>
                    <div class="form-group">
                        <label for="slp" class="form-label">SLP</label>
                        <input type="text" class="form-control" id="slp" name="slp">
                    </div>
                    <div class="form-group">
                        <label for="member" class="form-label">Member Name</label>
                        <input type="text" class="form-control" id="member" name="member">
                    </div>
                    <div class="form-group">
                        <label for="age" class="form-label">Age</label>
                        <input type="number" class="form-control" id="age" name="age">
                    </div>
                    <div class="form-group">
                        <label for="gender" class="form-label">Gender</label>
                        <input type="text" class="form-control" id="gender" name="gender">
                    </div>
                    <div class="form-group">
                        <label for="birthdate" class="form-label">Birthdate</label>
                        <input type="date" class="form-control" id="birthdate" name="birthdate">
                    </div>
                    <div class="form-group">
                        <label for="sitio_zone" class="form-label">Sitio/Zone</label>
                        <input type="text" class="form-control" id="sitio_zone" name="sitio_zone">
                    </div>
                </div>

                <div class="form-row mb-3">
                    <div class="form-group">
                        <label for="cellphone" class="form-label">Cellphone</label>
                        <input type="text" class="form-control" id="cellphone" name="cellphone">
                    </div>
                    <div class="form-group">
                        <label for="d2" class="form-label">D2</label>
                        <input type="text" class="form-control" id="d2" name="d2">
                    </div>
                    <div class="form-group">
                        <label for="brgy_d2" class="form-label">Barangay D2</label>
                        <input type="text" class="form-control" id="brgy_d2" name="brgy_d2">
                    </div>
                    <div class="form-group">
                        <label for="d1" class="form-label">D1</label>
                        <input type="text" class="form-control" id="d1" name="d1">
                    </div>
                    <div class="form-group">
                        <label for="brgy_d1" class="form-label">Barangay D1</label>
                        <input type="text" class="form-control" id="brgy_d1" name="brgy_d1">
                    </div>
                </div>

                <!-- Dependents Section -->
                <h4>Dependents</h4>
                <div id="dependentsFields">
                    <div class="form-row mb-3">
                        <div class="form-group">
                            <label for="dependents_0" class="form-label">Dependent Name</label>
                            <input type="text" class="form-control" id="dependents_0" name="dependents[0][name]">
                        </div>
                        <div class="form-group">
                            <label for="dep_age_0" class="form-label">Dependent Age</label>
                            <input type="number" class="form-control" id="dep_age_0" name="dependents[0][age]">
                        </div>
                        <div class="form-group">
                            <label for="dep_d2_0" class="form-label">Dependent D2</label>
                            <input type="text" class="form-control" id="dep_d2_0" name="dependents[0][d2]">
                        </div>
                        <div class="form-group">
                            <label for="dep_brgy_d2_0" class="form-label">Dependent Barangay D2</label>
                            <input type="text" class="form-control" id="dep_brgy_d2_0" name="dependents[0][brgy_d2]">
                        </div>
                        <div class="form-group">
                            <label for="dep_d1_0" class="form-label">Dependent D1</label>
                            <input type="text" class="form-control" id="dep_d1_0" name="dependents[0][d1]">
                        </div>
                        <div class="form-group">
                            <label for="dep_brgy_d1_0" class="form-label">Dependent Barangay D1</label>
                            <input type="text" class="form-control" id="dep_brgy_d1_0" name="dependents[0][brgy_d1]">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save Member</button>
            </form>
        </div>

        <div id="dynamicTableContainer" style="display:none;"></div>
        <div id="listingTableContainer" style="display:none;"></div>
        <!-- Table for displaying the members and dependents data -->
        <div id="membersTableContainer" style="display:none;">
            <div class='table-responsive' style='max-width 100%; overflow-x: auton;'>
                <table class="table table-bordered table-striped table-hover mb-0 w-full" >
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
         document.getElementById("showFormButton").addEventListener("click", function(event) {
            event.preventDefault();  // Prevent page reload on button click
            var formBox = document.getElementById("formBox");
            formBox.style.display = formBox.style.display === "none" ? "block" : "none";
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#viewListing').on('click', function() {
                loadListingData();
            });

            function loadListingData() {
                $.ajax({
                    url: "{{ route('members.viewListing') }}", // The URL to fetch data from
                    type: 'GET',
                    success: function(response) {
                        // Update the container with the new data
                        $('#listingTableContainer').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Failed to fetch listing data', error);
                        alert('Failed to fetch listing data');
                    }
                });
            }
        });
    </script>
    <script>
      $(document).ready(function() {
            let dependentIndex = 1;

            // Add additional dependent fields when the button is clicked
            $('#addDependentButton').click(function() {
                const dependentFields = `
                    <h4>Dependent ${dependentIndex + 1}</h4>
                    <div class="mb-3">
                        <label for="dependents_${dependentIndex}" class="form-label">Dependent Name</label>
                        <input type="text" class="form-control" id="dependents_${dependentIndex}" name="dependents[${dependentIndex}][name]" required>
                    </div>
                    <div class="mb-3">
                        <label for="dep_age_${dependentIndex}" class="form-label">Dependent Age</label>
                        <input type="number" class="form-control" id="dep_age_${dependentIndex}" name="dependents[${dependentIndex}][age]" required>
                    </div>
                    <div class="mb-3">
                        <label for="dep_d2_${dependentIndex}" class="form-label">Dependent D2</label>
                        <input type="text" class="form-control" id="dep_d2_${dependentIndex}" name="dependents[${dependentIndex}][d2]" required>
                    </div>
                    <div class="mb-3">
                        <label for="dep_brgy_d2_${dependentIndex}" class="form-label">Dependent Barangay D2</label>
                        <input type="text" class="form-control" id="dep_brgy_d2_${dependentIndex}" name="dependents[${dependentIndex}][brgy_d2]" required>
                    </div>
                    <div class="mb-3">
                        <label for="dep_d1_${dependentIndex}" class="form-label">Dependent D1</label>
                        <input type="text" class="form-control" id="dep_d1_${dependentIndex}" name="dependents[${dependentIndex}][d1]" required>
                    </div>
                    <div class="mb-3">
                        <label for="dep_brgy_d1_${dependentIndex}" class="form-label">Dependent Barangay D1</label>
                        <input type="text" class="form-control" id="dep_brgy_d1_${dependentIndex}" name="dependents[${dependentIndex}][brgy_d1]" required>
                    </div>
                `;
                $('#dependentsFields').append(dependentFields);
                dependentIndex++;
            });

            // Handle form submission via AJAX
            $('#addMemberForm').submit(function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Show loading message or spinner
                $('#submitMessage').text('Submitting...').removeClass('alert-success alert-danger').show();

                const formData = new FormData(this); // Collect form data

                // AJAX form submission
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#submitMessage').removeClass('alert-danger').addClass('alert-success').text(response.message).show();

                        // Hide the message after 4 seconds
                        setTimeout(function() {
                            $('#submitMessage').fadeOut();
                        }, 2000);
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                            if (errors) {
                                $('#submitMessage').removeClass('alert-success').addClass('alert-danger').text('There were errors with your submission.').show();

                                // Hide the message after 4 seconds
                                setTimeout(function() {
                                    $('#submitMessage').fadeOut();
                                }, 2000);

                                for (let field in errors) {
                                    // Display errors next to the corresponding fields
                                    $(`#${field}`).addClass('is-invalid');
                                    $(`#${field}`).siblings('.invalid-feedback').remove();
                                    $(`#${field}`).after(`<div class="invalid-feedback">${errors[field]}</div>`);
                                }
                            }
                    }
                });
            });
        });
    </script>

    <script>
        // Toggle visibility of the table on the "Show List" button click
        $(document).ready(function() {
            // Hide the table by default
            $("#membersTableContainer").hide();

            // Show the table when the "Show List" button is clicked
            $('#viewTable').on('click', function() {
                $('#membersTableContainer').show(); // Show the table
                $('#listingTableContainer').hide();
            });

            // Handle search and show the table when a search is performed
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase(); // Get the search value and convert to lowercase
                var table = $('#membersTableContainer');
                table.show(); // Show the table when a search is initiated
                $('#membersTableContainer tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1); // Toggle row visibility based on the search value
                });
            });

             $('#viewListing').on('click', function() {
                loadListingData();
            });

            function loadListingData() {
                $.ajax({
                    url: "{{ route('members.viewListing') }}", // Your URL to fetch new data
                    type: 'GET',
                    success: function(response) {
                        // Update the table container with new data
                        $('#listingTableContainer').html(response);
                        // Hide the other table (if any)
                        $('#membersTableContainer').hide();
                        // Show the listing container after data is loaded
                        $('#listingTableContainer').show();
                    },
                    error: function(xhr, status, error) {
                        console.error('Failed to fetch listing data', error);
                        alert('Failed to fetch listing data');
                    }
                });
            }

            // Ensure that the members table shows up properly when pagination is clicked
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];

                $.ajax({
                    url: "{{ route('members.index') }}?page=" + page,
                    type: 'GET',
                    success: function(response) {
                        // Replace the content of the table with new data
                        $('#membersTableContainer').html($(response).find('#membersTableContainer').html());
                        // Show the members table after loading new data
                        $('#membersTableContainer').show();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading page', error);
                        alert('Failed to load page');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#viewTable').on('click', function() {
                $('#listingTableContainer').hide();
            });

            $('#viewListing').on('click', function() {
                showTable('#listingTableContainer');
            });

            // $('#viewSLP').on('click', function() {
            //     showTable('#dynamicTableContainer');
            // });

            // Function to show a specific table and hide others
            function showTable(tableId) {
                // Hide all tables
                $('#listingTableContainer').hide();
                $('#membersTableContainer').hide();

                // Show the selected table
                $(tableId).show();
            }
        });
    </script>
    <script>
         function showEditForm(memberId) {
            fetch(`/members/${memberId}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();  // Parse the JSON response
                })
                .then(data => {
                    console.log(data); // Log the response data to check if it's correct

                    if (data.success) {
                        // Dynamically update the form action URL with the memberId
                        const formActionUrl = `/members/${memberId}`;
                        document.getElementById('editMemberForm').action = formActionUrl;

                        // Pre-fill the form with the fetched member data
                        const member = data.member;
                        document.getElementById('memberId').value = member.id;
                        document.getElementById('barangay').value = member.barangay;
                        document.getElementById('slp').value = member.slp;
                        document.getElementById('member').value = member.member;
                        // Add other fields if necessary

                        // Show the Edit Form
                        document.getElementById('edit-file').style.display = 'block'; // Show edit form
                        document.getElementById('membersTableContainer').style.display = 'none'; // Hide the table
                    } else {
                        alert('Member data could not be retrieved.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('There was an error with the request: ' + error.message);
                });
        }

        // Optionally define hideEditForm function
        function hideEditForm() {
            document.getElementById('edit-file').style.display = 'none';
            document.getElementById('membersTableContainer').style.display = 'block';
        }
    </script>

    <!-- SLP  -->
    <script>
        $(document).ready(function() {
            $('#viewSLP').on('change', function() {
                var selectedSlp = $(this).val();
                console.log(selectedSlp);  // Check if the value is correct

                if (selectedSlp) {
                    if (selectedSlp === "All") {
                        
                        // If "All" is selected, display all data for both tables
                        $.ajax({
                            url: '/members/slp/all/dependents', // New route for fetching all data
                            type: 'GET',
                            success: function(data) {
                                $('#dynamicTableContainer').html(data).show();
                            },
                            error: function() {
                                alert('An error occurred while fetching the data.');
                            }
                        });
                    } else {
                        // If a specific SLP is selected, fetch data for that SLP
                        $.ajax({
                            url: '/members/slp/' + selectedSlp + '/dependents', // Existing route for specific SLP
                            type: 'GET',
                            success: function(data) {
                                $('#dynamicTableContainer').html(data).show();
                            },
                            error: function() {
                                alert('An error occurred while fetching the data.');
                            }
                        });
                    }
                } else {
                    $('#dynamicTableContainer').hide();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#viewgoodSLP').on('change', function() {
                var selectedSlp = $(this).val();
                console.log(selectedSlp);  // Check if the value is correct

                if (selectedSlp) {
                    if (selectedSlp === "All") {
                        
                        // If "All" is selected, display all data for both tables
                        $.ajax({
                            url: '/members/slp/all/dependents', // New route for fetching all data
                            type: 'GET',
                            success: function(data) {
                                $('#dynamicTableContainer').html(data).show();
                            },
                            error: function() {
                                alert('An error occurred while fetching the data.');
                            }
                        });
                    } else {
                        // If a specific SLP is selected, fetch data for that SLP
                        $.ajax({
                            url: '/members/slp/' + selectedSlp + '/dependents-good', // Existing route for specific SLP
                            type: 'GET',
                            success: function(data) {
                                $('#dynamicTableContainer').html(data).show();
                            },
                            error: function() {
                                alert('An error occurred while fetching the data.');
                            }
                        });
                    }
                } else {
                    $('#dynamicTableContainer').hide();
                }
            });
        });
    </script>
     <script>
        $(document).ready(function() {
            $('#viewD1').on('click', function() {
                $.ajax({
                    url: '/members/d1', // New route for fetching all data
                    type: 'GET',
                    success: function(data) {
                        $('#dynamicTableContainer').html(data).show();
                    },
                    error: function() {
                        alert('An error occurred while fetching the data.');
                    }
                });
            });
        });
    </script>


</body>
</html>


