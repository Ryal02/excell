<table class="table table-bordered">
            <thead>
                <tr>
                    <th>BARANGAY</th>
                    <th>SLP</th>
                    <th>MEMBER</th>
                    <th>AGE</th>
                    <th>GENDER</th>
                    <th>BIRTHDATE</th>
                    <th>SITIO/ZONE</th>
                    <th>CELLPHONE</th>
                    <th>D2</th>
                    <th>BRGY D2</th>
                    <th>D1</th>
                    <th>BRGY D1</th>
                    <th>DEPENDENT</th>
                    <th>DEP_AGE</th>
                    <th>DEP_D2</th>
                    <th>DEP_BRGY_D2</th>
                    <th>DEP_D1</th>
                    <th>DEP_BRGY_D1</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                    <tr>
                        <td>{{ $member->barangay }}</td>
                        <td>{{ $member->slp }}</td>
                        <td>{{ $member->member }}</td>
                        <td>{{ $member->age }}</td>
                        <td>{{ $member->gender }}</td>
                        <td>{{ $member->birthdate }}</td>
                        <td>{{ $member->sitio_zone }}</td>
                        <td>{{ $member->cellphone }}</td>
                        <td>{{ $member->d2 }}</td>
                        <td>{{ $member->brgy_d2 }}</td>
                        <td>{{ $member->d1 }}</td>
                        <td>{{ $member->brgy_d1 }}</td>
                        <td>{{ $member->dependents->pluck('dependents')->implode(', ') }}</td>
                        <td>{{ $member->dependents->pluck('dep_age')->implode(', ') }}</td>
                        <td>{{ $member->dependents->pluck('dep_d2')->implode(', ') }}</td>
                        <td>{{ $member->dependents->pluck('dep_brgy_d2')->implode(', ') }}</td>
                        <td>{{ $member->dependents->pluck('dep_d1')->implode(', ') }}</td>
                        <td>{{ $member->dependents->pluck('dep_brgy_d1')->implode(', ') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="17">No members found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>