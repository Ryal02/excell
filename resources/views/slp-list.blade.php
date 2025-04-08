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
    <button class="btn btn-primary" id="printBtn">Print</button>
    <a href="{{ url('export-members') }}" class="btn btn-success" id="exportBtn">Export to Excel</a>
</div>
<div class="info-container">
    <p>BARANGAY: <span class="bold-text">{{$barangay}}</span></p>
    <p>SLP: <span class="bold-text">{{ $slp }}</span></p>
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
    </div>

    <!-- Dependents Table -->
    <div class="table-container">
        <h3>Dependents</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Dependent Name</th>
                    <th>Age</th>
                    <th>Cellphone</th>
                    <th>Barangay</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                    @foreach($member->dependents as $dependent)
                        <tr>
                            <td>{{ $dependent->dependents }}</td>
                            <td>{{ $dependent->dep_age }}</td>
                            <td>{{ $dependent->dep_cellphone }}</td>
                            <td>{{ $dependent->dep_brgy_d2 }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('printBtn').addEventListener('click', function() {
        var printContent = document.querySelector('.flex-container');  // Select the content to print
        var printWindow = window.open('', '', 'height=800,width=800');  // Open a new print window
        printWindow.document.write('<html><head><title>Print</title>');  // Start writing the HTML
        printWindow.document.write('<style>table {width: 100%; border-collapse: collapse;} th, td {padding: 8px; border: 1px solid #ddd;} h3 {text-align: center;}</style>');  // Optional styles for the printed content
        printWindow.document.write('</head><body>');
        printWindow.document.write(printContent.innerHTML);  // Copy the content of the flex-container
        printWindow.document.write('</body></html>');
        printWindow.document.close();  // Close the document for printing
        printWindow.print();  // Trigger the print dialog
    });
</script>

<script>
    document.getElementById('exportBtn').addEventListener('click', function() {
        var membersTable = document.querySelector(".table-container:nth-child(1) table tbody"); // Get the Members table rows
        var dependentsTable = document.querySelector(".table-container:nth-child(2) table tbody"); // Get the Dependents table rows
        
        // Gather the rows from both tables
        var membersData = Array.from(membersTable.rows).map(row => {
            return Array.from(row.cells).map(cell => cell.textContent.trim());
        });
        
        var dependentsData = Array.from(dependentsTable.rows).map(row => {
            return Array.from(row.cells).map(cell => cell.textContent.trim());
        });
        
        // Combine the two datasets (members and dependents)
        var allData = [...membersData, ...dependentsData];
        
        // Create a form to send the data to the server for export
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/export-visible-members'; // The endpoint to handle the export

        var dataInput = document.createElement('input');
        dataInput.type = 'hidden';
        dataInput.name = 'data';
        dataInput.value = JSON.stringify(allData); // Send the data as a JSON string
        form.appendChild(dataInput);

        // Append the CSRF token for security
        var csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}'; // Add CSRF token
        form.appendChild(csrfInput);

        // Submit the form
        document.body.appendChild(form);
        form.submit();
    });
</script>
