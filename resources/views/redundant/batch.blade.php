@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Redundant Members - Batch {{ $batch }}</h4>
        <div>
            <a href="{{ route('redundant.index') }}" class="btn btn-secondary btn-sm">← Back to Batches</a>
            <button class="btn btn-primary btn-sm" onclick="window.print()">🖨️ Print</button>
        </div>
    </div>

    <div class="row">
        {{-- Left Table: Members --}}
        <div class="col-md-6 mb-4">
            <h5>Redundant Members</h5>
            <strong>Total REDUNDANT: </strong>{{ count($members) }}
            <strong>Total REDUNDANT GOOD MEMBER: </strong>{{ $totalRedundant }}
            <div class="table-responsive">
                <table class="table table-sm table-bordered align-middle small">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Batch</th>
                            <th>Barangay</th>
                            <th>SLP</th>
                            <th>Member</th>
                            <th>Age</th>
                            <th>Precint</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $i => $member)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $member->batch }}</td>
                                <td>{{ $member->barangay }}</td>
                                <td>{{ $member->slp }}</td>
                                <td>{{ $member->member }}</td>
                                <td>{{ $member->age ?? 'N/A' }}</td>
                                <td>{{ $member->d2 ? $member->d2 : $member->d1 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right Table: Dependents --}}
        <div class="col-md-6 mb-4">
            <h5>Redundant Dependents</h5>
            <strong>Total DEPENDENT: </strong>{{ count($dependents) }} <!-- Total count -->
            <strong>Total REDUNDANT GOOD DEPENDENT: </strong>{{ $totaldependent }} <!-- Total count -->
            <div class="table-responsive">
                <table class="table table-sm table-bordered align-middle small">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Batch</th>
                            <th>Dependent Name</th>
                            <th>Age</th>
                            <th>precint</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $dcount = 1; @endphp
                        @foreach($dependents as $dependent)
                                <tr>
                                    <td>{{ $dcount++ }}</td>
                                    <td>{{ $dependent->batch_belong }}</td>
                                    <td>{{ $dependent->dependents }}</td>
                                    <td>{{ $dependent->dep_age ?? 'N/A' }}</td>
                                    <td>{{ $dependent->dep_d2 ? $dependent->dep_d2 : $dependent->dep_d1  }}</td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Print Styling --}}
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .container, .container * {
            visibility: visible;
        }

        .container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 0;
            margin: 0;
        }

        button, .btn, a.btn {
            display: none !important;
        }

        .table {
            font-size: 12px;
            width: 100%;
            text-align: center;
        }

        th, td {
            text-align: center !important;
            vertical-align: middle !important;
        }

        /* Flex row setup for printing */
        .row.print-flex {
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            gap: 20px !important; /* Add space between tables */
        }

        .col-md-6.print-half {
            width: 50% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
    }
</style>


@endsection
