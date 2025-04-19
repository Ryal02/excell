@extends('layouts.app')

@section('content')

@if(isset($matchingMembers) && $matchingMembers->isNotEmpty())
    <div class="mt-4">
        <h5>Matching SLP Members</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Member</th>
                        <th>Batch</th>
                        <th>Age</th>
                        <th>Barangay</th>
                        <th>SLP</th>
                        <th>Dependents</th> <!-- Add this -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matchingMembers as $member)
                        <tr>
                            <td>{{ $member->member }}</td>
                            <td>{{ $member->batch }}</td>
                            <td>{{ $member->age }}</td>
                            <td>{{ $member->barangay }}</td>
                            <td>{{ $member->slp }}</td>
                            <td>{{ $member->dependents_count }}</td> <!-- Display count -->
                            <td>
                                <form action="{{ route('slp.remove', $member->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this member?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@elseif(isset($matchingMembers))
    <div class="mt-4 alert alert-warning">No matching SLPs found.</div>
@endif

@endsection
