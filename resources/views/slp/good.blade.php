@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Select Good SLP</h4>
    <select id="viewgoodSLP" class="form-select w-25">
        <option value="">Select GOOD SLP</option>
        <option value="All">All</option>
        @foreach($uniqueSlps as $slp)
            <option value="{{ $slp }}">{{ $slp }}</option>
        @endforeach
    </select>
</div>
@endsection
