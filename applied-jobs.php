<?php
/**
 * Template Name: Applied Jobs
 *
 * @package Job_Listing_Theme
 */

get_header();

// Get current user ID
$user_id = get_current_user_id();

// Get user data
$user_data = get_userdata($user_id);

// Get profile picture
$profile_picture = get_user_meta($user_id, 'profile_picture', true);
if (empty($profile_picture)) {
    $profile_picture = get_avatar_url($user_id, array('size' => 150));
}

// Get user meta data
$about_me = get_user_meta($user_id, 'about_me', true);
$full_name = get_user_meta($user_id, 'full_name', true);
$job_title = get_user_meta($user_id, 'job_title', true);
$company = get_user_meta($user_id, 'company', true);
$location = get_user_meta($user_id, 'location', true);

// Personal information
$father_name = get_user_meta($user_id, 'father_name', true);
$mother_name = get_user_meta($user_id, 'mother_name', true);
$dob = get_user_meta($user_id, 'dob', true);
$gender = get_user_meta($user_id, 'gender', true);
$blood_group = get_user_meta($user_id, 'blood_group', true);
$nationality = get_user_meta($user_id, 'nationality', true);
$birth_country = get_user_meta($user_id, 'birth_country', true);
$contact_number = get_user_meta($user_id, 'contact_number', true);
$alt_contact = get_user_meta($user_id, 'alt_contact', true);
$present_address = get_user_meta($user_id, 'present_address', true);
$permanent_address = get_user_meta($user_id, 'permanent_address', true);
$presentcity = get_user_meta($user_id, 'presentcity', true);
$placeofbirth = get_user_meta($user_id, 'placeofbirth', true);


// Education
$education_entries = get_user_meta($user_id, 'education', true);
if (!is_array($education_entries)) {
    $education_entries = array();
}

// Training
$training_entries = get_user_meta($user_id, 'training', true);
if (!is_array($training_entries)) {
    $training_entries = array();
}

// Work Experience
$experience_entries = get_user_meta($user_id, 'work_experience', true);
if (!is_array($experience_entries)) {
    $experience_entries = array();
}

// References
$reference_entries = get_user_meta($user_id, 'references', true);
if (!is_array($reference_entries)) {
    $reference_entries = array();
}

// Skills
$skills = get_user_meta($user_id, 'skills', true);
if (!is_array($skills)) {
    $skills = array();
}

// Languages
$languages = get_user_meta($user_id, 'languages', true);
if (!is_array($languages)) {
    $languages = array();
}

// Resume
$resume_file = get_user_meta($user_id, 'resume_file', true);
$resume_filename = get_user_meta($user_id, 'resume_filename', true);
$resume_uploaded = get_user_meta($user_id, 'resume_uploaded', true);

// Calculate profile completeness
$completeness_fields = array(
    'about_me', 'full_name', 'job_title', 'company', 'location', 'father_name', 'mother_name', 
    'dob', 'gender', 'blood_group', 'nationality', 'birth_country', 'contact_number', 
    'present_address', 'permanent_address', 'skills', 'languages'
);
$filled_fields = 0;
foreach ($completeness_fields as $field) {
    $value = get_user_meta($user_id, $field, true);
    if (!empty($value)) {
        $filled_fields++;
    }
}
// Add education, training, experience, and references to completeness calculation
if (!empty($education_entries)) $filled_fields++;
if (!empty($training_entries)) $filled_fields++;
if (!empty($experience_entries)) $filled_fields++;
if (!empty($reference_entries)) $filled_fields++;
if (!empty($resume_file)) $filled_fields++;

$completeness_percentage = round(($filled_fields / (count($completeness_fields) + 5)) * 100);
?>
        <div class="container py-5">
    <!-- Profile Header -->
    <div class="profile-header position-relative">
        <div class="row align-items-center">
            <div class="col-md-2 text-center profile-pic-col">
                <img src="<?php echo esc_url($profile_picture); ?>" alt="Profile Picture" class="profile-pic mb-3">
                <div>
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#profilepicUploadModal">Change Photo</button>
                </div>
            </div>
            <div class="col-md-6">
                <h2 class="mb-1"><?php echo esc_html(!empty($full_name) ? $full_name : $user_data->display_name); ?></h2>
                <p class="text-muted mb-2"><i class="fas fa-phone me-2"></i> <?php echo esc_html($contact_number); ?>, <?php echo esc_html($alt_contact); ?></p>
                <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> <?php echo esc_html($present_address); ?></p>
            </div>
            <div class="col-md-4">
                <div class="d-flex justify-content-between mb-2">
                    <span>Profile Completeness:</span>
                    <span><?php echo esc_html($completeness_percentage); ?>%</span>
                </div>
                <div class="progress mb-3">
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo esc_attr($completeness_percentage); ?>%" aria-valuenow="<?php echo esc_attr($completeness_percentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary resume_upload-btn" style="display:<?php if (!empty($resume_file)) : echo 'none'; else: echo 'block'; endif;?>"  data-bs-toggle="modal" data-bs-target="#resumeUploadModal">Upload Resume</button>
                    <a class="btn btn-outline-primary" href="<?php echo esc_url(get_permalink(get_page_by_path('resume'))); ?>"><i class="fas fa-eye me-1"></i> View Public Profile</a>
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
                                <?php
                                global $wpdb;
                                $current_user_id = get_current_user_id();

                                // Fetch applications for the logged-in user
                                $applications = $wpdb->get_results(
                                    $wpdb->prepare(
                                        "SELECT a.*, j.post_title AS job_title, j.ID AS job_post_id
                                        FROM {$wpdb->prefix}job_applications a
                                        LEFT JOIN {$wpdb->posts} j ON a.job_id = j.ID
                                        WHERE a.user_id = %d
                                        ORDER BY a.applied_at DESC",
                                        $current_user_id
                                    )
                                );
                                // print_r($applications);
                                if(!empty($applications)) {
                                    foreach ($applications as $application) {

                                        $job_title = esc_html($application->job_title);
                                        $job_id = esc_html($application->job_post_id);
                                        $status = esc_html($application->status);
                                        $deadline = get_post_meta($job_id, '_job_deadline', true);
                                        $location = get_post_meta($job_id, '_job_location', true);
                                        $salary_type = get_post_meta($job_id, '_job_salary_type', true);
                                        $skills = get_the_terms($job_id, 'job_skill');

                                        if ($status === 'shortlisted') {
                                            $status_class = 'status-shortlisted';
                                        } elseif ($status === 'rejected') {
                                            $status_class = 'status-rejected';
                                        } else {
                                            $status_class = 'status-reviewing';
                                        }
                                                            // Format salary information
                                        $salary_html = '';
                                        if ($salary_type === 'negotiable') {
                                            $salary_html = __('Negotiable', 'job-listing');
                                        } elseif ($salary_type === 'fixed') {
                                            $fixed_salary = get_post_meta($job_id, '_job_fixed_salary', true);
                                            $fixed_salary_period = get_post_meta($job_id, '_job_fixed_salary_period', true);
                                            $salary_html = esc_html($fixed_salary) . '/' . esc_html($fixed_salary_period);
                                        } elseif ($salary_type === 'range') {
                                            $min_salary = get_post_meta($job_id, '_job_min_salary', true);
                                            $max_salary = get_post_meta($job_id, '_job_max_salary', true);
                                            $salary_range_period = get_post_meta($job_id, '_job_salary_range_period', true);
                                            $salary_html = esc_html($min_salary) . ' - ' . esc_html($max_salary) . '/' . esc_html($salary_range_period);
                                        }
                                        $applied_at = strtotime($application->applied_at); // convert DB datetime → timestamp
                                        $current_time = current_time('timestamp');        // WP current timestamp
                                        $time_diff = human_time_diff($applied_at, $current_time);


                                        ?>
                                        <!-- Application Cards -->
                                        <div class="card job-card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <h4><a href="<?php the_permalink($job_id); ?>"><?php echo $job_title; ?></a></h4>
                                                        <p class="text-muted">
                                                            <i class="fas fa-calendar-week me-2 text-muted"></i><?php echo date_i18n('d F, Y', strtotime($deadline)); ?>
                                                            <span class="mx-2">|</span>
                                                            <i class="fas fa-map-marker-alt me-1"></i> <?php echo esc_html($location); ?>
                                                            <span class="mx-2">|</span>
                                                            <i class="fa-solid fa-bangladeshi-taka-sign"></i> <?php echo $salary_html; ?>
                                                            <span class="mx-2">|</span>
                                                            <i class="fas fa-clock me-1"></i><?php echo "Applied: " . esc_html($time_diff) . " ago"; ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4 text-md-end">
                                                        <span class="status-badge <?php echo $status_class; ?>"><?php echo $status; ?></span>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-md-8">
                                                        <p><?php 
                                                        $job_content = get_post_field('post_content', $job_id);
                                                        echo wp_trim_words($job_content, 30, '...');?></p>
                                                        <div class="d-flex flex-wrap">
                                                            <?php if ($skills && !is_wp_error($skills)) : ?>
                                                                <?php foreach ($skills as $skill) : ?>
                                                                    <span class="badge bg-light text-dark me-2 mb-2"><?php echo esc_html($skill->name); ?></span>
                                                                <?php endforeach;
                                                                endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 text-md-end mt-md-0 mt-3">
                                                        <a href="<?php echo get_permalink($job_id); ?>" class="btn btn-outline-primary">View Job</a>
                                                        <button class="btn btn-link text-danger">Withdraw</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php
                                    }
                                } ?>
                                <!-- Application Cards -->
                                <!-- <div class="card job-card">
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
                                </div> -->
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

<!-- Profile Picture Upload Modal -->
<div class="modal fade" id="profilepicUploadModal" tabindex="-1" aria-labelledby="profilepicUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="profilepicUploadModalLabel">Upload Profile Picture</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="profilepicUploadForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Image Preview -->
                    <div class="text-center mb-3">
                        <img id="profilepicPreview" src="<?php echo esc_url(get_user_meta(get_current_user_id(), 'profile_picture', true)); ?>" class="rounded-circle profile-pic" width="150" height="150" style="object-fit: cover;">
                    </div>
                    <div class="mb-3">
                        <label for="profilepic" class="form-label fw-bold">Select Profile Picture <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="profilepic" name="profilepic" accept=".jpg,.jpeg" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload Picture</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php
get_footer();?>