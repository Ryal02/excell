
    <style>
        .slp-container {
            margin: 20px;
        }

        .slp-section {
            margin-bottom: 40px;
        }

        .table-container {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        h3, h4 {
            margin-bottom: 10px;
        }

        .total-count {
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>

    <div class="slp-container">
        <h2>All SLPs</h2>

        @foreach($slpData as $data)
            <div class="slp-section">
                <h3>SLP: {{ $data['slp']->name }}</h3> <!-- Assuming 'name' is the SLP name -->
                
                <!-- Members Table -->
                <div class="table-container">
                    <h4>Members</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Member Name</th>
                                <th>Birthdate</th>
                                <th>Zone/Sitio</th>
                                <th>Cellphone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['members'] as $member)
                                <tr>
                                    <td>{{ $member->member }}</td>
                                    <td>{{ $member->birthdate }}</td>
                                    <td>{{ $member->sitio_zone }}</td>
                                    <td>{{ $member->cellphone }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="total-count">
                        <p>Total Members: {{ $data['members']->count() }}</p>
                    </div>
                </div>

                <!-- Dependents Table -->
                <div class="table-container">
                    <h4>Dependents</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Dependent Name</th>
                                <th>Age</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['dependents'] as $dependent)
                                <tr>
                                    <td>{{ $dependent->dependents }}</td>
                                    <td>{{ $dependent->dep_age }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="total-count">
                        <p>Total Dependents: {{ $data['dependents']->count() }}</p>
                    </div>
                </div>
            </div>
            <hr>
        @endforeach
    </div>
