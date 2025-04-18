@extends('layouts.app')

@section('content')

<style>
    
    .flex-container {
        display: flex;
        justify-content: space-between;
        gap: 20px;  /* Optional, adds space between tables */
        margin: 20px;
    }

    .table-container {
        width: 48%;  /* Set the width of each table container to 50%, leaving some space */
    }

    table {
        width: 100%;  /* Ensure tables take up the full width of their container */
        border-collapse: collapse;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    h3 {
        text-align: center;
        margin-bottom: 10px;
    }
    .info-container {
        text-align: center;  /* Center the content */
        margin-bottom: 20px;  /* Add space below the info */
    }
    
    .info-container p {
        font-weight: normal;  /* Make text normal (non-bold) */
    }
    
    .info-container .bold-text {
        font-weight: bold;  /* Make the values bold */
    }
</style>
<div class="print-button-container d">
    <button class="btn btn-primary" id="printBtn">🖨️ Print</button>
    <a href="#" class="btn btn-success" id="exportBtn"><i class="fas fa-download me-1"></i>  Export to Excel</a>
</div>

<div class="info-container">
    <p>SLP: <span class="bold-text">{{ $slp ?? 'All' }}</span></p>
</div>

<div class="flex-container">
    <!-- Members Table -->
    <div class="table-container">
        <h3>Members</h3>
        <table class="table table-sm table-bordered table-striped table-hover mb-0 w-full small">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Batch</th>
                    <th>Member Name</th>
                    <th>Birthdate</th>
                    <th>D2</th>
                    <th>BARANGAY</th>
                </tr>
            </thead>
            <tbody>
                @php $counter = 1; @endphp
                @foreach($members as $member)
                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td>{{ $member->batch }}</td>
                        <td>{{ $member->member }}</td>
                        <td>{{ $member->birthdate }}</td>
                        <td>{{ $district == 1 ? $member->d1 : $member->d2 }}</td>
                        <td>{{ $district == 1 ? $member->brgy_d1 : $member->brgy_d2 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="total-count">
            <p>Total Members: {{ $members->count() }}</p>
        </div>
    </div>

    <!-- Dependents Table -->
    <div class="table-container">
        <h3>Dependents</h3>
        <table class="table table-sm table-bordered table-striped table-hover mb-0 w-full small">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Batch</th>
                    <th>Dependent Name</th>
                    <th>Age</th>
                    <th>d2</th>
                    <th>BARANGAY</th>
                </tr>
            </thead>
            <tbody>
                @php $counter = 1; @endphp
                @foreach($dependents as $dependent)
                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td>{{ $dependent->member->batch }}</td>
                        <td>{{ $dependent->dependents }}</td>
                        <td>{{ $dependent->dep_age }}</td>
                        <td>{{ $district == 1 ? $dependent->dep_d1 : $dependent->dep_d2 }}</td>
                        <td>{{ $district == 1 ? $dependent->dep_brgy_d1 : $dependent->dep_brgy_d2 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="total-count">
            <p>Total Dependents: {{ $dependents->count() }}</p>
        </div>
    </div>
</div>

<script>
// Print Button Script
document.getElementById('printBtn').addEventListener('click', function() {
    var printWindow = window.open('', '', 'height=800,width=1600');
    var printContent = `
        <html>
        <head>
            <title>Print</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 8px; }  /* Reduced font size */
                .flex-container { 
                    display: flex;
                    justify-content: space-between;  /* Ensure tables are side by side */
                    padding-top: 0px;
                    gap: 10px;  /* Reduced space between the tables */
                }
                .table-container, .table-container2 {
                    display: block;
                    flex-grow: 1;  /* Allow tables to grow and fill available space */
                    width: 48%;  /* Make each table take 48% of the width, leaving space between */
                    padding: 1%;
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    table-layout: auto;  /* Let the table adjust column widths automatically */
                    word-wrap: break-word; /* Break long words in table cells */
                }
                th, td { 
                    padding: 4px; /* Reduced padding */
                    text-align: left; 
                    border: 1px solid #ddd;
                    white-space: normal;  /* Prevent text overflow and allow wrapping */
                    word-wrap: break-word;  /* Break long text in cells */
                    font-size: 8px; /* Reduced font size in table */
                }
                h3 { 
                    text-align: center; 
                    font-size: 10px; /* Reduced font size for headers */
                    margin: 0; /* Reduced margin for h3 */
                }
                .total-count { 
                    text-align: center; 
                    font-size: 8px; /* Reduced font size for the total count */
                    margin-top: 5px;
                }
                @media print {
                    .flex-container {
                        flex-direction: row; /* Keep the tables in a row when printing */
                    }
                    .table-container, .table-container2 {
                        padding: 0;
                        width: 50%;
                    }
                }
            </style>
        </head>
        <body>
            <div class="info-container">
                <p style="font-size: 8px;">SLP: <span class="bold-text">{{ $slp ?? 'All' }}</span></p>
            </div>
            <div class="flex-container">
                <div class="table-container">
                    <h3>Members</h3>
                    <table class="table table-sm table-bordered table-striped table-hover mb-0 w-full small">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Batch</th>
                                <th>Member Name</th>
                                <th>Birthdate</th>
                                <th>Precint</th>
                                <th>BARANGAY</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($members as $member)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                     <td>{{ $member->batch }}</td>
                                    <td>{{ $member->member }}</td>
                                    <td>{{ $member->birthdate }}</td>
                                    <td>{{ $district == 1 ? $member->d1 : $member->d2 }}</td>
                                    <td>{{ $district == 1 ? $member->brgy_d1 : $member->brgy_d2 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-container2">
                    <h3>Dependents</h3>
                    <table class="table table-sm table-bordered table-striped table-hover mb-0 w-full small">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Dependent Name</th>
                                <th>Age</th>
                                <th>Precint</th>
                                <th>BARANGAY</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($dependents as $dependent)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $dependent->dependents }}</td>
                                    <td>{{ $dependent->dep_age }}</td>
                                    <td>{{ $district == 1 ? $dependent->dep_d1 : $dependent->dep_d2 }}</td>
                                    <td>{{ $district == 1 ? $dependent->dep_brgy_d1 : $dependent->dep_brgy_d2 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </body>
        </html>`;
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.print();
});

</script>

@endsection
