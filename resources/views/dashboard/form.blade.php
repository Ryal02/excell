<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Philippines Democratic Country</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }
        .sidebar {
            background-color: #343a40;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            z-index: 1030;
            padding-top: 56px; /* Adjust for navbar height */
        }

        .content-area {
            margin-left: 250px; /* Make space for the sidebar */
            padding: 20px;
        }

        /* Center the time in the navbar */
        .navbar .navbar-center {
            flex: 1;
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: absolute;
                width: 100%;
                height: auto;
                margin-top: 56px; /* Adjust for navbar height */
            }
            .content-area {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Back Button -->
    <div class="container-fluid">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary mb-3">
            <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
        </a>

        <!-- Form Start -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="barangay" class="form-label">Barangay</label>
                <input type="text" class="form-control" id="barangay" name="barangay">
            </div>
            <div class="col-md-3">
                <label for="slp" class="form-label">SLP</label>
                <input type="text" class="form-control" id="slp" name="slp">
            </div>
            <div class="col-md-3">
                <label for="member" class="form-label">Member Name</label>
                <input type="text" class="form-control" id="member" name="member">
            </div>
            <div class="col-md-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" class="form-control" id="age" name="age">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label for="gender" class="form-label">Gender</label>
                <input type="text" class="form-control" id="gender" name="gender">
            </div>
            <div class="col-md-3">
                <label for="birthdate" class="form-label">Birthdate</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate">
            </div>
            <div class="col-md-3">
                <label for="sitio_zone" class="form-label">Sitio/Zone</label>
                <input type="text" class="form-control" id="sitio_zone" name="sitio_zone">
            </div>
            <div class="col-md-3">
                <label for="cellphone" class="form-label">Cellphone</label>
                <input type="text" class="form-control" id="cellphone" name="cellphone">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label for="d2" class="form-label">D2</label>
                <input type="text" class="form-control" id="d2" name="d2">
            </div>
            <div class="col-md-3">
                <label for="brgy_d2" class="form-label">Barangay D2</label>
                <input type="text" class="form-control" id="brgy_d2" name="brgy_d2">
            </div>
            <div class="col-md-3">
                <label for="d1" class="form-label">D1</label>
                <input type="text" class="form-control" id="d1" name="d1">
            </div>
            <div class="col-md-3">
                <label for="brgy_d1" class="form-label">Barangay D1</label>
                <input type="text" class="form-control" id="brgy_d1" name="brgy_d1">
            </div>
        </div>
        <!-- Form End -->
    </div>
    <div class="row mb-3  pe-2">
        <div class="col-auto ms-auto">
            <button type="submit" class="btn btn-primary w-auto float-end ">Submit</button>
        </div>
    </div>
</body>
</html>
