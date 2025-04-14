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
                    <th rowspan="2" style="white-space: nowrap;">No.</th> <!-- "No." header -->
                    <th rowspan="2" style="white-space: nowrap;">SLP</th> <!-- "SLP" header -->
                </tr>
                <tr id="sub-header-row"></tr> <!-- New sub-header row -->
            </thead>
            <tbody class="text-xs"></tbody>
            <tfoot>
                <tr id="overall-totals">
                    <!-- This will be populated by JS -->
                </tr>
            </tfoot>
        </table>
        <div class="d-flex justify-content-start align-items-center gap-4 mt-3">
                <div class='w-full'><strong>MEMBER:</strong> <span id="total-member">0</span></div>
                <div class='w-full'><strong>DEPENDENT:</strong> <span id="total-dependent">0</span></div>
                <div class='w-full'><strong>TOTAL:</strong> <span id="total-all">0</span></div>
            </div>
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
                const { barangays, data } = response;

                // Build headers
                let headerTop = `
                    <th rowspan="2">No.</th>
                    <th rowspan="2">SLP</th>
                `;
                barangays.forEach(bgry => {
                    headerTop += `<th colspan="2">${bgry}</th>`;
                });
                headerTop += `
                    <th rowspan="2">Member</th>
                    <th rowspan="2">Dependent</th>
                    <th rowspan="2">Total</th>
                `;
                $('#header-row').html(headerTop);

                let subHeader = '';
                barangays.forEach(() => {
                    subHeader += `<th>Member</th><th>Dependent</th>`;
                });
                $('#sub-header-row').html(subHeader);

                // Table body + running totals
                let bodyHtml = '';
                let overallMember = 0;
                let overallDependent = 0;
                let barangayTotals = {};

                data.forEach((row, index) => {
                    bodyHtml += `<tr><td>${index + 1}</td><td>${row.slp}</td>`;
                    barangays.forEach(bgry => {
                        const m = row['member_' + bgry] ?? 0;
                        const d = row['dependent_' + bgry] ?? 0;
                        bodyHtml += `<td>${m}</td><td>${d}</td>`;

                        // Track totals per barangay
                        barangayTotals[bgry] = barangayTotals[bgry] || { member: 0, dependent: 0 };
                        barangayTotals[bgry].member += m;
                        barangayTotals[bgry].dependent += d;
                    });

                    bodyHtml += `<td>${row.member}</td><td>${row.dependent}</td><td>${row.total}</td></tr>`;
                    overallMember += row.member;
                    overallDependent += row.dependent;
                });

                $('#slp-table tbody').html(bodyHtml);

                // Build totals row
                let totalRow = `<td colspan="2"><strong>OVERALL TOTAL</strong></td>`;
                barangays.forEach(bgry => {
                    const m = barangayTotals[bgry]?.member ?? 0;
                    const d = barangayTotals[bgry]?.dependent ?? 0;
                    totalRow += `<td><strong>${m}</strong></td><td><strong>${d}</strong></td>`;
                });
                totalRow += `
                    <td><strong>${overallMember}</strong></td>
                    <td><strong>${overallDependent}</strong></td>
                    <td><strong>${overallMember + overallDependent}</strong></td>
                `;
                $('#overall-totals').html(totalRow);

                // Update the summary below the table
                $('#total-member').text(overallMember);
                $('#total-dependent').text(overallDependent);
                $('#total-all').text(overallMember + overallDependent);
            },
            error: function (xhr) {
                console.error("AJAX error:", xhr.responseText);
            }
        });
    });

</script>
