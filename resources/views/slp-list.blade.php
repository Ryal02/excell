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
    <a href="#" class="btn btn-success" id="exportBtn">Export to Excel</a>
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
        <div class="total-count">
            <p>Total Bad Members: {{ $members->count() }}</p>
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
            <p>Total Bad Dependent: {{ $dependents->count() }}</p>
        </div>
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
        var membersTable = document.querySelector(".table-container:nth-child(1) table tbody");
        var dependentsTable = document.querySelector(".table-container:nth-child(2) table tbody");

        // Gather the rows from both tables
        var membersData = Array.from(membersTable.rows).map(row => {
            return Array.from(row.cells).map(cell => cell.textContent.trim());
        });

        var dependentsData = Array.from(dependentsTable.rows).map(row => {
            return Array.from(row.cells).map(cell => cell.textContent.trim());
        });

        // Combine the two datasets (members and dependents)
        var allData = [...membersData, ...dependentsData];

        // Log the data to verify the structure before sending it
        console.log(allData);  // <-- Add this line

        // Create the form to submit data
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
