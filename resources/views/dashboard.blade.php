<!DOCTYPE html>
<html lang="ta">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BreakdownHelp</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">BreakdownHelp</a>
        <div class="d-flex align-items-center">
            <!-- Logged in users name and category -->
            <span class="text-white me-3">
                Welcome, {{ Auth::user()->full_name }} ({{ ucfirst(Auth::user()->user_type) }})
            </span>
            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<!-- Content -->
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4>Welcome to BreakdownHelp Dashboard!</h4>
                    <p class="text-muted">You are logged in as a <strong>{{ Auth::user()->user_type }}</strong>.</p>

                    @if (session('success'))
                        <div class="alert alert-success mt-3 mb-0">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>