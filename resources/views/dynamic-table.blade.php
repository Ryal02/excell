<!-- dynamic-table.blade.php -->
<table class="table">
    <thead>
        <tr>
            <th>{{ request()->category}}</th>
        </tr>
    </thead>
    <tbody>
        @if($category == 'dependents')
            @foreach($data as $dependent)
                <tr>
                    <td>
                        <!-- Assuming dependents table has 'name' and 'age' columns -->
                        {{ $dependent->dependents }} (Age: {{ $dependent->dep_age }})
                    </td>
                </tr>
            @endforeach
        @elseif($category == 'barangays')
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->barangay }}</td>
                </tr>
            @endforeach
        @elseif($category == 'slp')
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->slp }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td>No data found</td>
            </tr>
        @endif
    </tbody>
</table>
