<style>
    .center {
        text-align: center;
        vertical-align: middle;
    }

    /* Center the table in the middle of the screen */
    .table-container {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 13px;
    }
</style>

<div class="table-container">
    <table class="table table-bordered" style="width: 50%;">
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
                    <td>{{ $data['D2_good_member'] }}</td>
                    <td>{{ $data['D2_good_dependent'] }}</td>
                    <td>{{ $data['D2_bad_member'] }}</td>
                    <td>{{ $data['D2_bad_dependent'] }}</td>
                    <td>{{ $data['member_district1'] }}</td>
                    <td>{{ $data['dependent_distric1'] }}</td>
                </tr>
            @endforeach
            <tr>
                <td><strong>Total</strong></td>
                <td><strong>{{ collect($listingData)->sum('D2_good_member') }}</strong></td>
                <td><strong>{{ collect($listingData)->sum('D2_good_dependent') }}</strong></td>
                <td><strong>{{ collect($listingData)->sum('D2_bad_member') }}</strong></td>
                <td><strong>{{ collect($listingData)->sum('D2_bad_dependent') }}</strong></td>
                <td><strong>{{ collect($listingData)->sum('member_district1') }}</strong></td>
                <td><strong>{{ collect($listingData)->sum('dependent_distric1') }}</strong></td>
            </tr>
            <tr>
                <td><strong>MEMBER : </strong></td>
                <td colspan='7'></td>
            </tr>
            <tr>
                <td><strong>DEPENDENT</strong></td>
                <td colspan='7'></td>
            </tr>
            <tr>
                <td><strong>TOTAL</strong></td>
                <td colspan='7'></td>
            </tr>
        </tbody>
    </table>
</div>
