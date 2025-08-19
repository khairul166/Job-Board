<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
<!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <!-- intlTelInput CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
    <link rel="stylesheet" href="css/custom-style.css">
</head>

<body>
 <nav class="navbar navbar-expand-lg navbar-light bg-white rounded">
            <div class="container">
                <!-- Logo on the left -->
                <a class="navbar-brand" href="Index.html">
                    <img src="https://easyfashion.com.bd/wp-content/uploads/2019/05/Asset-6-2.png.webp" alt="Company Logo">
                </a>
                
                <!-- Mobile toggle button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Navbar content -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <!-- Menu items -->
                        <li class="nav-item">
                            <a class="nav-link active" href="Index.html">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Pricing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">About</a>
                        </li>
                        
                        <!-- Profile dropdown -->
                        <li class="nav-item dropdown profile-dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown">
                                <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/a1ee95f0-8a09-4f70-aa08-1edba3a5599d.png" class="profile-img" alt="Profile">
                                <span class="d-none d-md-inline ms-2">John Doe</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                 <li><a class="dropdown-item" href="user-profile-2.html">My Profile</a></li>
                            <li><a class="dropdown-item" href="applied-jobs.html">Applied Jobs</a></li>
                            <li><a class="dropdown-item" href="notification.html">Notifications</a></li>
                            <li><a class="dropdown-item" href="settings.html">Settings</a></li>
                            <!-- <li>
                                <hr class="dropdown-divider">
                            </li> -->
                            <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    <div class="container">
        <div class="signup-container">
            <div class="signup-header">
                <i class="bi bi-person-plus-fill"></i>
                <h2 class="mt-3 text-success">Create Account</h2>
                <p class="text-muted">Join us today!</p>
            </div>
            
            <form>
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingUsername" placeholder="Username" required>
                    <label for="floatingUsername">Username</label>
                </div>
                
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingEmailMobile" placeholder="Email or Mobile Number" required>
                    <label for="floatingEmailMobile">Email or Mobile Number</label>
                </div>
                
                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                    <label for="floatingPassword">Password</label>
                    <div class="password-strength mt-2">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                    <small class="text-muted">At least 8 characters with a mix of letters, numbers and symbols</small>
                </div>
                
                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingConfirmPassword" placeholder="Confirm Password" required>
                    <label for="floatingConfirmPassword">Confirm Password</label>
                    <div id="passwordMatch" class="text-danger small"></div>
                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="" id="termsCheck" required>
                    <label class="form-check-label" for="termsCheck">
                        I agree to the <a href="#" class="text-success text-decoration-none">Terms and Conditions</a>
                    </label>
                </div>
                
                <button class="btn btn-signup btn-success" type="submit">Sign Up</button>
                
                <div class="additional-links">
                    <p class="text-muted mt-3">Already have an account? <a href="#" class="text-success text-decoration-none">Sign In</a></p>
                    <hr>
                    <p class="text-muted">Or sign up with</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" class="btn btn-outline-success"><i class="fa-brands fa-google"></i></a>
                        <a href="#" class="btn btn-outline-success"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" class="btn btn-outline-success"><i class="fa-brands fa-apple"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-3">JobFinder</h5>
                    <p>Connecting top talent with world-class companies since 2020.</p>
                    <div class="social-icons">
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="mb-3">For Job Seekers</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Browse Jobs</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Create Resume</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Job Alerts</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Career Advice</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="mb-3">For Employers</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Post a Job</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Browse Candidates</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Pricing</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Recruiting Solutions</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3">Stay Updated</h5>
                    <p>Subscribe to our newsletter for the latest jobs and career tips.</p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Your email" aria-label="Your email">
                        <button class="btn btn-success" type="button">Subscribe</button>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small mb-0">&copy; 2025 JobFinder. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="#" class="text-white text-decoration-none small">Privacy
                                Policy</a></li>
                        <li class="list-inline-item"><a href="#" class="text-white text-decoration-none small">Terms of
                                Service</a></li>
                        <li class="list-inline-item"><a href="#" class="text-white text-decoration-none small">Contact
                                Us</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="js/main.js"></script>
    <!-- intlTelInput JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
</body>

</html>