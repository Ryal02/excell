<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Philippines Democratic Country</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            background-color: #343a40;
            color: white;
        }

        .sidebar .nav-link {
            color: #adb5bd;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: #495057;
            border-radius: 5px;
        }

        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
            border-radius: 5px;
        }

        .sidebar .nav-link i {
            margin-right: 8px;
        }

        .sidebar-heading {
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            padding-bottom: .5rem;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }

        /* Sidebar Fix */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            z-index: 1030;
        }

        /* Content Area */
        .content-area {
            margin-left: 250px;
            padding: 20px;
        }

        /* Custom Dropdown Styles */
        .dropdown-btn {
            background-color: transparent; /* No background on button */
            color: #adb5bd;
            padding: 10px;
            font-size: 16px;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .dropdown-container {
            display: none;
            background-color: #495057;
            padding-left: 20px;
            border-left: 2px solid #ddd;
            width: 100%;
        }

        .dropdown-container a {
            padding: 8px;
            text-decoration: none;
            display: block;
            color: #ddd;
        }

        .dropdown-container a:hover {
            background-color: #ddd;
            color: #fff;
        }

        /* Active dropdown button style */
        .dropdown-btn.active {
            background-color: #495057; /* Optional, if you want to give feedback on hover */
            color: #fff;
        }
        .slp-text {
            margin-left: 8px;  /* Adjust the value as needed */
        }
        .slp-btn {
            margin-left: 6px;  /* Adjust the value as needed */
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

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar p-3">
            <div class="sidebar-heading text-white mb-3">Philippines IHAG System</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('batches') || request()->is('members/batch/*') ? 'active' : '' }}" href="{{ route('batches') }}">
                        <i class="bi bi-people-fill"></i> Member List
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('slpGood') || request()->is('slp/good') || request()->is('getslp/all') ? 'active' : '' }}" href="{{ route('slpGood') }}">
                        <i class="bi bi-people-fill"></i> SLP List
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('redundant') || request()->is('redundant/batch/*') ? 'active' : '' }}" href="{{ route('redundant.index') }}">
                        <i class="bi bi-people-fill"></i> Redundant Lists
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('barangay') ? 'active' : '' }}" href="{{ route('barangay.index') }}">
                        <i class="bi bi-houses-fill"></i> Barangay Lists
                    </a>
                </li>
            </ul>
        </div>

        <!-- Content -->
        <div class="col-md-10 content-area">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

<script>
    // Loop through all dropdown buttons to toggle between hiding and showing its dropdown content
    var dropdowns = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdowns.length; i++) {
        dropdowns[i].addEventListener("click", function() {
            // Toggle active class to style the dropdown button
            this.classList.toggle("active");

            // Get the next sibling (dropdown content) and toggle its visibility
            var dropdownContent = this.nextElementSibling;

            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }
</script>

</body>
</html>
