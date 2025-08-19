<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | JobPortal</title>
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

        <div class="container py-5">
        <!-- Profile Header -->
        <div class="profile-header position-relative">

            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/a1ee95f0-8a09-4f70-aa08-1edba3a5599d.png"
                        alt="Profile Picture" class="profile-pic mb-3">
                    <div>
                        <button class="btn btn-sm btn-outline-secondary">Change Photo</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2 class="mb-1">John Doe</h2>
                    <p class="text-muted mb-2"><i class="fas fa-briefcase me-2"></i> Senior Software Developer at
                        TechCorp</p>
                    <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> San Francisco, CA, USA</p>
                    <!-- <div class="d-flex flex-wrap">
                        <span class="badge bg-success me-2 mb-2"><i class="fas fa-star me-1"></i> Premium Member</span>
                        <span class="badge bg-info me-2 mb-2"><i class="fas fa-check-circle me-1"></i> Verified</span>
                    </div> -->
                </div>
                <div class="col-md-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Profile Completeness:</span>
                        <span>85%</span>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 85%" aria-valuenow="85"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#resumeUploadModal"></i> Upload Resume</button>
                        <button class="btn btn-outline-primary"><i class="fas fa-eye me-1"></i> View Public
                            Profile</button>
                    </div>
                </div>
            </div>
        </div>

                                        <h3 class="mb-4">Account Settings</h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title"><i class="fas fa-user-cog me-2"></i> Profile Settings</h5>
                                                <hr>
                                                <div class="mb-3">
                                                    <label class="form-label">Email Notifications</label>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="emailNotify" checked>
                                                        <label class="form-check-label" for="emailNotify">Receive email notifications</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title"><i class="fas fa-shield-alt me-2"></i> Security</h5>
                                                <hr>
                                                <button class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                                    <i class="fas fa-lock me-1"></i> Change Password
                                                </button>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>

        </div>
<!-- Resume Upload Modal -->
<div class="modal fade" id="resumeUploadModal" tabindex="-1" aria-labelledby="resumeUploadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="resumeUploadModalLabel">Upload Your Resume</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="resumeUploadForm" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="mb-3">
            <label for="resumeFile" class="form-label fw-bold">Select Resume File <span class="text-danger">*</span></label>
            <input type="file" class="form-control" id="resumeFile" name="resumeFile" accept=".pdf,.doc,.docx" required>
            <div class="form-text">Accepted formats: PDF, DOC, DOCX. Max size: 5MB.</div>
          </div>

          <div id="resumeUploadMessage" class="text-success d-none">
            ✅ Resume uploaded successfully!
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Upload Resume</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="currentPassword" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback">
                            Please enter your current password.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="newPassword" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback">
                            Please enter a new password.
                        </div>
                        <div class="form-text">Password must be at least 8 characters long and include uppercase, lowercase, numbers, and special characters.</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirmPassword" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback">
                            Passwords do not match.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Strength</label>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar" id="passwordStrengthBar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted" id="passwordStrengthText">Enter a password</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="changePasswordBtn">Change Password</button>
            </div>
        </div>
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