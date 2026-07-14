<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In - Equal Gate-Pass</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #de2942;
            /* Matching your text-success branding theme */
            --bg-light: #f8f9fa;
        }

        body {
            background-color: var(--bg-light);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }

        .signin-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .signin-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 2.5rem 2rem;
        }

        .brand-logo {
            max-width: 110px;
            height: auto;
        }

        .form-control:focus {
            border-color: #de2942;
            box-shadow: 0 0 0 0.25rem rgba(222, 41, 66, 0.2);
        }

        .btn-primary-theme {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            font-weight: 500;
            transition: background-color 0.2s ease;
        }

        .btn-primary-theme:hover {
            background-color: #e76a7b;
            color: white;
        }

        .input-group-text {
            background-color: transparent;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="container signin-container">
        <div class="signin-card">
            <!-- Logo and Header -->
            <div class="text-center mb-4">
                <img src="../assets/images/Equaloffshorelimited.Logoalone-ezgif.com-crop.gif" alt="Equal Logistics Logo" class="brand-logo mb-3">
                <h1 class="h4 fw-bold mb-1">Welcome Back</h1>
                <p class="text-muted small">Gate-Pass Terminal Admin Management Portal</p>
            </div>

            <!-- Sign In Form -->
            <form id="adminSignInForm" method="POST" action="login_process.php" novalidate>
                <!-- Email Input -->
                <div class="mb-3">
                    <label for="adminEmail" class="form-label small fw-semibold text-secondary">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                        <input type="email" id="adminEmail" name="email" class="form-control border-start-0" placeholder="admin@equaloffshore.com" required>
                    </div>
                </div>

                <!-- Password Input -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="adminPassword" class="form-label small fw-semibold text-secondary mb-0">Password</label>
                        <a href="#" class="text-danger small text-decoration-none">Forgot password?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                        <input type="password" id="adminPassword" name="password" class="form-control border-start-0 border-end-0" placeholder="••••••••" required>
                        <span class="input-group-text border-start-0 text-muted" id="togglePassword">
                            <i class="bi bi-eye" id="passwordIcon"></i>
                        </span>
                    </div>
                </div>

                <!-- Keep me signed in -->
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                    <label class="form-check-label small text-muted" for="rememberMe">Keep me signed in on this machine</label>
                </div>

                <!-- Action Button -->
                <button type="submit" class="btn btn-primary-theme w-100 rounded-3 mb-3">
                    Sign In to Workspace <i class="bi bi-arrow-right-short ms-1"></i>
                </button>
            </form>

            <!-- Dynamic Copyright Footer -->
            <div class="text-center mt-4">
                <p class="text-muted xx-small mb-0" style="font-size: 0.75rem;">
                    &copy; 2026 AdminHMD. All security parameters active.
                </p>
            </div>
        </div>
    </div>

    <!-- Password Visibility Script -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#adminPassword');
        const passwordIcon = document.querySelector('#passwordIcon');

        togglePassword.addEventListener('click', function() {
            // Toggle the input type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Toggle the visual icon layout states
            passwordIcon.classList.toggle('bi-eye');
            passwordIcon.classList.toggle('bi-eye-slash');
        });
    </script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>