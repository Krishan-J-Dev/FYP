<!DOCTYPE html>
<html lang="ta">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BreakdownHelp</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google reCAPTCHA JS Script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="text-center mb-2 fw-bold">Create Account</h3>
                    <p class="text-center text-muted mb-4">Join BreakdownHelp community today</p>

                    <!-- 1. எர்ரர் செய்திகளைக் காண்பித்தல் -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register.post') }}" method="POST">
                        <!-- 2. CSRF பாதுகாப்பு -->
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+94 7X XXX XXXX" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Register As</label>
                            <select name="user_type" class="form-select" required>
                                <option value="driver" {{ old('user_type') == 'driver' ? 'selected' : '' }}>Vehicle Driver</option>
                                <option value="mechanic" {{ old('user_type') == 'mechanic' ? 'selected' : '' }}>Mechanic</option>
                                <option value="tow_service" {{ old('user_type') == 'tow_service' ? 'selected' : '' }}>Tow Service Provider</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <!-- 3. Google reCAPTCHA Widget -->
                        <div class="mb-3 d-flex justify-content-center">
                            <div class="g-recaptcha" data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"></div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">Sign Up</button>
                    </form>

                    <div class="text-center mt-3">
                        <small>Already have an account? <a href="#">Log In</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>