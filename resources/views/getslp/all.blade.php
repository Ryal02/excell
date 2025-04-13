@extends('layouts.app')

<style>
    .text-xs {
        font-size: 0.75rem !important;
    }

    .table-sm td,
    .table-sm th {
        padding: 0.3rem !important;
    }

    th, td {
        text-align: left;
        white-space: nowrap;
    }
</style>

@section('content')
<div class="container">
    <h4>All SLP Overview</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-sm text-xs" id="slp-table">
            <thead>
                <tr id="header-row">
                    <th style="white-space: nowrap;">No.</th> <!-- "No." header -->
                    <th style="white-space: nowrap;">SLP</th> <!-- "SLP" header -->
                    <!-- Dynamic Barangay headers will be added here -->
                </tr>
            </thead>
            <tbody class="text-xs"></tbody>
        </table>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        $.ajax({
            url: "{{ route('getslp.fetchAll') }}",
            method: 'GET',
            success: function (response) {
                const { columns, data } = response;

                // Build header (without No. column repeated)
                let headerHtml = `<th style="white-space: nowrap;">No.</th>`; // "No." column header
                headerHtml += `<th style="white-space: nowrap;">SLP</th>`; // "SLP" column header
                
                // Add dynamic Barangay headers
                columns.forEach(bgry => {
                    headerHtml += `<th style="white-space: nowrap;">${bgry}</th>`;
                });

                // Add the "Member", "Dependent", "Total" columns to the header
                headerHtml += `
                    <th style="white-space: nowrap;">Member</th>
                    <th style="white-space: nowrap;">Dependent</th>
                    <th style="white-space: nowrap;">Total</th>
                `;
                $('#header-row').html(headerHtml);

                // Build body with No. column (index starts from 1)
                let bodyHtml = '';
                data.forEach((row, index) => {
                    // Add No. column (index starts from 1)
                    bodyHtml += `<tr><td>${index + 1}</td><td>${row.slp}</td>`; // "No." and "SLP"

                    // Add Barangay data dynamically
                    columns.forEach(bgry => {
                        bodyHtml += `<td>${row[bgry] ?? 0}</td>`; // If no data, show 0
                    });

                    // Add "Member", "Dependent", "Total" data
                    bodyHtml += `
                        <td>${row.member}</td>
                        <td>${row.dependent}</td>
                        <td>${row.total}</td>
                    </tr>`;
                });

                $('#slp-table tbody').html(bodyHtml); // Insert the rows in the table body
            },
            error: function (xhr) {
                console.error("AJAX error:", xhr.responseText);
            }
        });
    });
</script>
