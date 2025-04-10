@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Select Bad SLP</h4>
    <select id="viewSLP" class="form-select w-25">
        <option value="">Select BAD SLP</option>
        <option value="All">All</option>
        @foreach($uniqueSlps as $slp)
            <option value="{{ $slp }}">{{ $slp }}</option>
        @endforeach
    </select>
</div>
@endsection
