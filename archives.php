<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senior Frontend Developer - Job Details</title>
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

    <!-- Job Header -->
    <section class="job-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-3">
                        
                        <div>
                            <h1 class="display-5 fw-bold mb-2">Archives</h1>          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<div class="container">
            <div class="row">
            <div class="col-lg-8">
                <!-- Job Listings -->
                <div id="job-listings-container">

                    <div class="card job-card" data-job-type="full-time remote" data-experience="senior"
                        data-industry="tech">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><a href="job-details-final.html" class="job-title">Full Stack Developer</a></h4>
                                    <p class="text-muted">
                                        <i class="fas fa-calendar-week me-2 text-muted"></i>15 September, 2025
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-map-marker-alt me-1"></i> Dhaka
                                        <span class="mx-2">|</span>
                                        <i class="fa-solid fa-bangladeshi-taka-sign"></i> &#2547;20000 -
                                        &#2547;25000/Monthly
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <span class="status-badge status-featured">Featured</span>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-8">
                                    <p>Join our dynamic team working on cutting-edge web applications. We're looking for
                                        someone proficient in both frontend and backend technologies.</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="job-type full-time">Full-time</span>
                                        <span class="job-type remote">Remote</span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-md-0 mt-3">
                                    <small class="text-muted d-block mb-2"><i class="fas fa-clock me-1"></i>Posted 2
                                        days ago</small>
                                    <a href="job-details-final.html" class="btn btn-outline-success btn-sm">Apply
                                        Now</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card job-card" data-job-type="part-time" data-experience="entry"
                        data-industry="education">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><a href="job-details-final.html" class="job-title">Teaching Assistant</a></h4>
                                    <p class="text-muted">
                                        <i class="fas fa-calendar-week me-2 text-muted"></i>20 September, 2025
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-map-marker-alt me-1"></i> Chittagong
                                        <span class="mx-2">|</span>
                                        <i class="fa-solid fa-bangladeshi-taka-sign"></i> &#2547;15000 -
                                        &#2547;18000/Monthly
                                    </p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-8">
                                    <p>Assist professors with course preparation and student mentoring. Perfect
                                        opportunity for recent graduates.</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="job-type part-time">Part-time</span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-md-0 mt-3">
                                    <small class="text-muted d-block mb-2"><i class="fas fa-clock me-1"></i>Posted 1
                                        week ago</small>
                                    <a href="job-details-final.html" class="btn btn-outline-success btn-sm">Apply
                                        Now</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card job-card" data-job-type="contract" data-experience="mid" data-industry="finance">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><a href="job-details-final.html" class="job-title">Financial Analyst</a></h4>
                                    <p class="text-muted">
                                        <i class="fas fa-calendar-week me-2 text-muted"></i>10 September, 2025
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-map-marker-alt me-1"></i> Dhaka
                                        <span class="mx-2">|</span>
                                        <i class="fa-solid fa-bangladeshi-taka-sign"></i> &#2547;30000 -
                                        &#2547;35000/Monthly
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <span class="status-badge status-featured">Featured</span>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-8">
                                    <p>6-month contract position for financial modeling and analysis. Must have 3+ years
                                        experience in corporate finance.</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="job-type contract">Contract</span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-md-0 mt-3">
                                    <small class="text-muted d-block mb-2"><i class="fas fa-clock me-1"></i>Posted 3
                                        days ago</small>
                                    <a href="job-details-final.html" class="btn btn-outline-success btn-sm">Apply
                                        Now</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card job-card" data-job-type="full-time remote" data-experience="executive"
                        data-industry="tech">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><a href="job-details-final.html" class="job-title">CTO (Chief Technology Officer)</a></h4>
                                    <p class="text-muted">
                                        <i class="fas fa-calendar-week me-2 text-muted"></i>5 September, 2025
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-map-marker-alt me-1"></i> Remote
                                        <span class="mx-2">|</span>
                                        <i class="fa-solid fa-bangladeshi-taka-sign"></i> &#2547;150000 -
                                        &#2547;200000/Monthly
                                    </p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-8">
                                    <p>Lead our technology strategy and engineering teams. Must have 10+ years
                                        experience scaling tech companies.</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="job-type full-time">Full-time</span>
                                        <span class="job-type remote">Remote</span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-md-0 mt-3">
                                    <small class="text-muted d-block mb-2"><i class="fas fa-clock me-1"></i>Posted 5
                                        days ago</small>
                                    <a href="job-details-final.html" class="btn btn-outline-success btn-sm">Apply
                                        Now</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card job-card" data-job-type="part-time remote" data-experience="mid"
                        data-industry="healthcare">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><a href="job-details-final.html" class="job-title">Telemedicine Physician</a></h4>
                                    <p class="text-muted">
                                        <i class="fas fa-calendar-week me-2 text-muted"></i>18 September, 2025
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-map-marker-alt me-1"></i> Remote
                                        <span class="mx-2">|</span>
                                        <i class="fa-solid fa-bangladeshi-taka-sign"></i> &#2547;40000 -
                                        &#2547;50000/Monthly
                                    </p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-8">
                                    <p>Provide virtual consultations to patients. Must be licensed in Bangladesh and
                                        have 3+ years clinical experience.</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="job-type part-time">Part-time</span>
                                        <span class="job-type remote">Remote</span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-md-0 mt-3">
                                    <small class="text-muted d-block mb-2"><i class="fas fa-clock me-1"></i>Posted
                                        yesterday</small>
                                    <a href="job-details-final.html" class="btn btn-outline-success btn-sm">Apply
                                        Now</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card job-card" data-job-type="full-time" data-experience="entry"
                        data-industry="finance">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><a href="job-details-final.html" class="job-title">Junior Accountant</a></h4>
                                    <p class="text-muted">
                                        <i class="fas fa-calendar-week me-2 text-muted"></i>12 September, 2025
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-map-marker-alt me-1"></i> Sylhet
                                        <span class="mx-2">|</span>
                                        <i class="fa-solid fa-bangladeshi-taka-sign"></i> &#2547;18000 -
                                        &#2547;22000/Monthly
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <span class="status-badge status-featured">Featured</span>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-8">
                                    <p>Entry-level position in our accounting department. Fresh graduates with
                                        accounting degrees encouraged to apply.</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="job-type full-time">Full-time</span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-md-0 mt-3">
                                    <small class="text-muted d-block mb-2"><i class="fas fa-clock me-1"></i>Posted 4
                                        days ago</small>
                                    <a href="job-details-final.html" class="btn btn-outline-success btn-sm">Apply
                                        Now</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Pagination -->
                <nav aria-label="Job listings pagination">
                    <ul class="pagination justify-content-center mt-4">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="col-lg-4">
                <!-- Sidebar -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Job Alerts</h5>
                    </div>
                    <div class="card-body">
                        <p>Get notified about new jobs matching your search criteria</p>
                        <div class="mb-3">
                            <label for="job-alert-email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="job-alert-email"
                                placeholder="name@example.com">
                        </div>
                        <button class="btn btn-success w-100">Create Job Alert</button>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Job Market Insights</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-bold">Software Engineer Demand</h6>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-info" style="width: 85%">85%</div>
                            </div>
                            <small class="text-muted">↑ 15% from last quarter</small>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold">Remote Jobs</h6>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-warning" style="width: 65%">65%</div>
                            </div>
                            <small class="text-muted">↑ 22% from last year</small>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold">Entry Level Positions</h6>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" style="width: 45%">45%</div>
                            </div>
                            <small class="text-muted">↓ 5% from last quarter</small>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Quick Tips</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-bold">Tailor Your Resume</h6>
                            <p class="small">Customize your resume to match the job description keywords.</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold">Follow Up</h6>
                            <p class="small">Send a thank you email within 24 hours after an interview.</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold">Network</h6>
                            <p class="small">80% of jobs are filled through networking, not job boards.</p>
                        </div>
                    </div>
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