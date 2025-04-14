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
<div class="print-button-container">
    <button class="btn btn-primary" id="printBtn">🖨️ Print</button>
    <a href="#" class="btn btn-success" id="exportBtn"><i class="fas fa-download me-1"></i>  Export to Excel</a>
</div>

<div class="info-container">
    <p>BARANGAY: <span class="bold-text">{{ $barangay }}</span></p>
    <p>SLP: <span class="bold-text">{{ $slp ?? 'All' }}</span></p>
</div>

<div class="flex-container">
    <!-- Members Table -->
    <div class="table-container">
        <h3>Members</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Member Name</th>
                    <th>Birthdate</th>
                    <th>Zone/Sitio</th>
                    <th>Cellphone</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                    <tr>
                        <td>{{ $member->member }}</td>
                        <td>{{ $member->birthdate }}</td>
                        <td>{{ $member->sitio_zone }}</td>
                        <td>{{ $member->cellphone }}</td>
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
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Dependent Name</th>
                    <th>Age</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dependents as $dependent)
                    <tr>
                        <td>{{ $dependent->dependents }}</td>
                        <td>{{ $dependent->dep_age }}</td>
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
                <p style="font-size: 8px;">BARANGAY: <span class="bold-text">${{ $barangay }}</span></p>
                <p style="font-size: 8px;">SLP: <span class="bold-text">{{ $slp ?? 'All' }}</span></p>
            </div>
            <div class="flex-container">
                <div class="table-container">
                    <h3>Members</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Member Name</th>
                                <th>Birthdate</th>
                                <th>Zone/Sitio</th>
                                <th>Cellphone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                                <tr>
                                    <td>{{ $member->member }}</td>
                                    <td>{{ $member->birthdate }}</td>
                                    <td>{{ $member->sitio_zone }}</td>
                                    <td>{{ $member->cellphone }}</td>
                                </tr>
                            @endforeach
                                <tr>
                                    <th colspan='4' >Total Members: {{ $members->count() }}</tht>
                                </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-container2">
                    <h3>Dependents</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Dependent Name</th>
                                <th>Age</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dependents as $dependent)
                                <tr>
                                    <td>{{ $dependent->dependents }}</td>
                                    <td>{{ $dependent->dep_age }}</td>
                                </tr>
                            @endforeach
                                <tr>
                                    <th colspan='2'>Total Members: {{ $dependents->count() }}</tht>
                                </tr>
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

<script>
document.getElementById('exportBtn').addEventListener('click', function() {
    var membersTable = document.querySelector(".table-container:nth-child(1) table tbody");
    var dependentsTable = document.querySelector(".table-container:nth-child(2) table tbody");

    var membersData = Array.from(membersTable.rows).map(row => {
        return Array.from(row.cells).map(cell => cell.textContent.trim());
    });

    var dependentsData = Array.from(dependentsTable.rows).map(row => {
        return Array.from(row.cells).map(cell => cell.textContent.trim());
    });

    var allData = [...membersData, ...dependentsData];

    // Prepare the form for Excel export
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '/export-visible-members'; 

    var dataInput = document.createElement('input');
    dataInput.type = 'hidden';
    dataInput.name = 'data';
    dataInput.value = JSON.stringify(allData); 
    form.appendChild(dataInput);

    var csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}'; 
    form.appendChild(csrfInput);

    document.body.appendChild(form);
    form.submit();
});
</script>
