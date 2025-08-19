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

        <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h3 class="mb-0">My Job Applications</h3>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-filter me-1"></i> Filter
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                            <li><a class="dropdown-item" href="#">All Applications</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="#">Pending</a></li>
                                            <li><a class="dropdown-item" href="#">Under Review</a></li>
                                            <li><a class="dropdown-item" href="#">Accepted</a></li>
                                            <li><a class="dropdown-item" href="#">Rejected</a></li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <!-- Application Cards -->
                                <div class="card job-card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h4>Senior Frontend Developer</h4>
                                                <p class="text-muted">
                                                    <i class="fas fa-building me-1"></i> TechGiant Inc.
                                                    <span class="mx-2">|</span>
                                                    <i class="fas fa-map-marker-alt me-1"></i> San Francisco, CA (Remote)
                                                    <span class="mx-2">|</span>
                                                    <i class="fas fa-clock me-1"></i> Applied: 2 days ago
                                                </p>
                                            </div>
                                            <div class="col-md-4 text-md-end">
                                                <span class="status-badge status-reviewing">Under Review</span>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-8">
                                                <p>We are looking for an experienced Frontend Developer to join our team. You will be responsible for building user interfaces using React and TypeScript.</p>
                                                <div class="d-flex flex-wrap">
                                                    <span class="badge bg-light text-dark me-2 mb-2">React</span>
                                                    <span class="badge bg-light text-dark me-2 mb-2">TypeScript</span>
                                                    <span class="badge bg-light text-dark me-2 mb-2">Redux</span>
                                                    <span class="badge bg-light text-dark me-2 mb-2">Jest</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-md-end mt-md-0 mt-3">
                                                <a href="#" class="btn btn-outline-primary">View Job</a>
                                                <button class="btn btn-link text-danger">Withdraw</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card job-card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h4>Full Stack Developer</h4>
                                                <p class="text-muted">
                                                    <i class="fas fa-building me-1"></i> WebSolutions LLC
                                                    <span class="mx-2">|</span>
                                                    <i class="fas fa-map-marker-alt me-1"></i> New York, NY (Hybrid)
                                                    <span class="mx-2">|</span>
                                                    <i class="fas fa-clock me-1"></i> Applied: 1 week ago
                                                </p>
                                            </div>
                                            <div class="col-md-4 text-md-end">
                                                <span class="status-badge status-pending">Pending</span>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-8">
                                                <p>Join our dynamic team working on cutting-edge web applications. We're looking for someone proficient in both frontend and backend technologies.</p>
                                                <div class="d-flex flex-wrap">
                                                    <span class="badge bg-light text-dark me-2 mb-2">Node.js</span>
                                                    <span class="badge bg-light text-dark me-2 mb-2">React</span>
                                                    <span class="badge bg-light text-dark me-2 mb-2">MongoDB</span>
                                                    <span class="badge bg-light text-dark me-2 mb-2">AWS</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-md-end mt-md-0 mt-3">
                                                <a href="#" class="btn btn-outline-primary">View Job</a>
                                                <button class="btn btn-link text-danger">Withdraw</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card job-card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h4>Backend Engineer</h4>
                                                <p class="text-muted">
                                                    <i class="fas fa-building me-1"></i> DataSystems Corp.
                                                    <span class="mx-2">|</span>
                                                    <i class="fas fa-map-marker-alt me-1"></i> Austin, TX (On-site)
                                                    <span class="mx-2">|</span>
                                                    <i class="fas fa-clock me-1"></i> Applied: 1 month ago
                                                </p>
                                            </div>
                                            <div class="col-md-4 text-md-end">
                                                <span class="status-badge status-accepted">Accepted</span>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-8">
                                                <p>We're looking for a skilled Backend Engineer to develop and maintain our high-performance server infrastructure and APIs.</p>
                                                <div class="d-flex flex-wrap">
                                                    <span class="badge bg-light text-dark me-2 mb-2">Python</span>
                                                    <span class="badge bg-light text-dark me-2 mb-2">Django</span>
                                                    <span class="badge bg-light text-dark me-2 mb-2">PostgreSQL</span>
                                                    <span class="badge bg-light text-dark me-2 mb-2">Docker</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-md-end mt-md-0 mt-3">
                                                <a href="#" class="btn btn-success">Accept Offer</a>
                                                <button class="btn btn-link text-danger">Decline</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card job-card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h4>Product Manager</h4>
                                                <p class="text-muted">
                                                    <i class="fas fa-building me-1"></i> InnovateTech
                                                    <span class="mx-2">|</span>
                                                    <i class="fas fa-map-marker-alt me-1"></i> Boston, MA (Remote)
                                                    <span class="mx-2">|</span>
                                                    <i class="fas fa-clock me-1"></i> Applied: 3 weeks ago
                                                </p>
                                            </div>
                                            <div class="col-md-4 text-md-end">
                                                <span class="status-badge status-rejected">Rejected</span>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-8">
                                                <p>Looking for an experienced Product Manager to lead our product development team and drive product strategy.</p>
                                                <div class="d-flex flex-wrap">
                                                    <span class="badge bg-light text-dark me-2 mb-2">Product Management</span>
                                                    <span class="badge bg-light text-dark me-2 mb-2">Agile</span>
                                                    <span class="badge bg-light text-dark me-2 mb-2">UX</span>
                                                    <span class="badge bg-light text-dark me-2 mb-2">Market Research</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-md-end mt-md-0 mt-3">
                                                <a href="#" class="btn btn-outline-primary">View Job</a>
                                                <button class="btn btn-link">Request Feedback</button>
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