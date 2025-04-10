<style>
    /* Flex container for displaying the tables side by side */
    .flex-container {
        display: flex;
        flex-wrap: wrap;  /* Allow wrapping of content */
        gap: 20px;  /* Space between tables */
        margin: 20px;
    }

    /* Style for each table container */
    .table-container {
        width: 48%;  /* Set the width of each table container to 48% */
        margin-bottom: 20px;  /* Space between the tables */
    }

    /* Table styling */
    table {
        width: 100%;  /* Ensure tables take up the full width of their container */
        border-collapse: collapse;
    }

    /* Table header and data cell styling */
    th, td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    /* Title styling */
    h3 {
        text-align: center;
        margin-bottom: 10px;
    }

    /* Info container styling */
    .info-container {
        text-align: center;
        margin-bottom: 20px;
    }

    .info-container p {
        font-weight: normal;
    }

    .info-container .bold-text {
        font-weight: bold;
    }

    /* Media Query for small screens */
    @media (max-width: 768px) {
        .table-container {
            width: 100%;  /* Make each table container take up full width on smaller screens */
        }
    }
</style>

<!-- Print Button and Export to Excel -->
<div class="print-button-container">
    <button class="btn btn-primary" id="printBtn">Print</button>
    <a href="#" class="btn btn-success" id="exportBtn">Export to Excel</a>
</div>

<!-- Flex Container for Tables -->
<div class="flex-container">
    @foreach($slpData as $data)
        <!-- Members Table -->
        <div class="table-container">
            <h3>SLP: {{ $data['slp'] }}</h3> <!-- Assuming 'name' is the SLP name -->
            <h4>Members</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Member Name</th>
                        <th>Birthdate</th>
                        <th>Zone/Sitio</th>
                        <th>Cellphone</th>
                        <th>Precint</th>
                        <th>Barangay D1</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['members'] as $member)
                        <tr>
                            <td>{{ $member->member }}</td>
                            <td>{{ $member->birthdate }}</td>
                            <td>{{ $member->sitio_zone }}</td>
                            <td>{{ $member->cellphone }}</td>
                            <td>{{ $member->d1 }}</td>
                            <td>{{ $member->brgy_d1 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="total-count">
                <p>Total Members: {{ $data['members']->count() }}</p>
            </div>
        </div>

        <!-- Dependents Table -->
        <div class="table-container">
            <h3>BARANGAY: {{ $data['barangay'] }}</h3>
            <h4>Dependents</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Dependent Name</th>
                        <th>Age</th>
                        <th>Precint</th>
                        <th>Barangay D1</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['dependents'] as $dependent)
                        <tr>
                            <td>{{ $dependent->dependents }}</td>
                            <td>{{ $dependent->dep_age }}</td>
                            <td>{{ $dependent->dep_d1 }}</td>
                            <td>{{ $dependent->dep_brgy_d1 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="total-count">
                <p>Total Dependents: {{ $data['dependents']->count() }}</p>
            </div>
        </div>
    @endforeach
</div>

<hr>

<!-- Print Script -->
<script>
    document.getElementById('printBtn').addEventListener('click', function() {
        var printWindow = window.open('', '', 'height=800,width=1600');
        var printContent = `
            <html>
            <head>
                <title>Print</title>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 8px; }
                    .flex-container {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 10px;
                    }
                    .table-container {
                        width: 48%;
                        padding: 1%;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        table-layout: auto;
                    }
                    th, td {
                        padding: 4px;
                        text-align: left;
                        border: 1px solid #ddd;
                        font-size: 8px;
                    }
                    h3 {
                        text-align: center;
                        font-size: 10px;
                    }
                    .total-count {
                        text-align: center;
                        font-size: 8px;
                        margin-top: 5px;
                    }
                    @media print {
                        .flex-container {
                            flex-direction: row;
                        }
                        .table-container {
                            width: 50%;
                            padding: 0;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="flex-container">
                    @foreach($slpData as $data)
                        <div class="table-container">
                            <h3>SLP: {{ $data['slp'] }}</h3>
                            <h4>Members</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Member Name</th>
                                        <th>Birthdate</th>
                                        <th>Zone/Sitio</th>
                                        <th>Cellphone</th>
                                        <th>Precint</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['members'] as $member)
                                        <tr>
                                            <td>{{ $member->member }}</td>
                                            <td>{{ $member->birthdate }}</td>
                                            <td>{{ $member->sitio_zone }}</td>
                                            <td>{{ $member->cellphone }}</td>
                                            <td>{{ $member->d1 }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="total-count">
                                <p>Total Members: {{ $data['members']->count() }}</p>
                            </div>
                        </div>

                        <div class="table-container">
                            <h3>BARANGAY: {{ $data['barangay'] }}</h3>
                            <h4>Dependents</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Dependent Name</th>
                                        <th>Age</th>
                                        <th>Precint</th>
                                        <th>Barangay D1</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['dependents'] as $dependent)
                                        <tr>
                                            <td>{{ $dependent->dependents }}</td>
                                            <td>{{ $dependent->dep_age }}</td>
                                            <td>{{ $dependent->dep_d1 }}</td>
                                            <td>{{ $dependent->dep_brgy_d1 }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="total-count">
                                <p>Total Dependents: {{ $data['dependents']->count() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </body>
            </html>`;
        printWindow.document.write(printContent);
        printWindow.document.close();
        printWindow.print();
    });
</script>
