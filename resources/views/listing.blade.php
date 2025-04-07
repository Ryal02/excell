
<style>
    .center {
        text-align: center;
        vertical-align: middle;
    }
</style>
<div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th colspan='8' class='center'> BATCH 11</th>
            </tr>
            <tr>
                <th colspan='5' class='center'> DISTRICT 2</th>
                <th colspan='4' class='center'> DISTRICT 1</th>
            </tr>
            <tr>
                <th class='center'></th>
                <th colspan='2' class='center'> GOOD</th>
                <th colspan='2' class='center'> BAD</th>
            </tr>
            <tr>
                <th>BARANGAY</th>
                <th>MEMBER</th>
                <th>DEPENDENT</th>
                <th>MEMBER</th>
                <th>DEPENDENT</th>
                <th>MEMBER</th>
                <th>DEPENDENT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($listingData as $data)
                <tr>
                    <td>{{ $data['barangay'] }}</td>
                    <td>{{ $data['total_members'] }}</td>
                    <td>{{ $data['district2_good'] }}</td>
                    <td>{{ $data['district2_bad'] }}</td>
                    <td>{{ $data['district1_good'] }}</td>
                    <td>{{ $data['district1_bad'] }}</td>
                </tr>
            @endforeach
            <tr>
                <td><strong>Total</strong></td>
                <td><strong>{{ collect($listingData)->sum('district2_good') }}</strong></td>
                <td><strong>{{ collect($listingData)->sum('district2_bad') }}</strong></td>
                <td><strong>{{ collect($listingData)->sum('district1_good') }}</strong></td>
                <td><strong>{{ collect($listingData)->sum('district1_bad') }}</strong></td>
            </tr>
            <tr>
                <th colspan='5'>MEMBER</th>
                <th colspan='5'>MEMBER</th>
            </tr>
            <tr>
                <th colspan='5'>DEPENDENT</th>
                <th colspan='5'>DEPENDENT</th>
            </tr>
            <tr>
                <th colspan='5'>TOTAL</th>
                <th colspan='5'>TOTAL</th>
            </tr>
        </tbody>
    </table>
</div>
