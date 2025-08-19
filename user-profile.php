<?php
/**
 * Template Name: User Profile
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

    <!-- Main Content -->
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-8">
            <!-- About Section -->
            <div class="profile-section position-relative">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="section-title mb-0">About Me</h4>
                    <button class="btn btn-sm btn-outline-primary edit-section-btn" data-bs-toggle="modal" data-bs-target="#editAboutModal">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
                <p><?php echo esc_html($about_me); ?></p>
            </div>

            <!-- Personal Details Section -->
            <div class="profile-section position-relative personalinfo-section">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="section-title mb-0">Personal Information</h4>
                    <button class="btn btn-sm btn-outline-primary edit-section-btn" data-bs-toggle="modal" data-bs-target="#editPersonalInfoModal">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-4">
                            <h5>Full Name</h5>
                            <p><?php echo esc_html(!empty($full_name) ? $full_name : $user_data->display_name); ?></p>
                        </div>
                        <div class="mb-4">
                            <h5>Father's name</h5>
                            <p><?php echo esc_html($father_name); ?></p>
                        </div>
                        <div class="mb-4">
                            <h5>Mother's name</h5>
                            <p><?php echo esc_html($mother_name); ?></p>
                        </div>
                        <div class="mb-4">
                            <h5>Date of birth</h5>
                            <p><?php if (!empty($dob)) {
                                try {
                                    $date = new DateTime($dob);
                                    $formatted_dob = $date->format('d/m/Y');
                                    echo $formatted_dob; // Outputs: 27/07/2025 (or whatever the date is)
                                } catch (Exception $e) {
                                    // Handle invalid date format
                                    echo 'Invalid date';
                                }
                            } else {
                                // No date of birth found
                                echo 'No date of birth set';
                            } ?></p>
                        </div>
                        <div class="mb-4">
                            <h5>Gender</h5>
                            <p><?php echo esc_html($gender); ?></p>
                        </div>
                        <div class="mb-4">
                            <h5>Blood group</h5>
                            <p><?php echo esc_html($blood_group); ?></p>
                        </div>
                        <div class="mb-4">
                            <h5>Nationality</h5>
                            <p><?php echo esc_html($nationality); ?></p>
                        </div>
                        <div class="mb-4">
                            <h5>Country of birth</h5>
                            <p><?php echo esc_html($birth_country); ?></p>
                        </div>
                    </div>
                    <div class="col-sm-6">

                        <div class="mb-4">
                            <h5>Contact number</h5>
                            <p><?php echo esc_html($contact_number); ?></p>
                        </div>
                        <div class="mb-4">
                            <h5>Alternative contact number</h5>
                            <p><?php echo esc_html($alt_contact); ?></p>
                        </div>
                        <div class="mb-4">
                            <h5>Email</h5>
                            <p><?php echo esc_html($user_data->user_email); ?></p>
                        </div>
                        <div class="mb-4">
                            <h5>Present address</h5>
                            <p><?php echo esc_html($present_address); ?></p>
                        </div>
                        <div class="mb-4">
                            <h5>Permanent address</h5>
                            <p><?php echo esc_html($permanent_address); ?></p>
                        </div>
                        <div class="mb-4">
                            <h5>Present City</h5>
                            <p><?php echo esc_html($presentcity); ?></p>
                        </div>
                        <div class="mb-4">
                            <h5>Home District</h5>
                            <p><?php echo esc_html($placeofbirth); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Education Section -->
            <div class="profile-section position-relative education-section">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="section-title">Education</h4>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editEducationModal">
                        <i class="fa-solid fa-plus"></i> Add
                    </button>
                </div>
                
                <?php if (!empty($education_entries)) : ?>
                    <?php foreach ($education_entries as $entry) : ?>
                        <?php
                        // Ensure we have a valid ID
                        $entry_id = isset($entry['id']) ? esc_attr($entry['id']) : 'edu_' . uniqid();
                        
                        // Build the entry HTML
                        $degree = esc_html($entry['degree']);
                        $institution = esc_html($entry['institution']);
                        $year = isset($entry['passing_year']) ? intval($entry['passing_year']) : '';
                        
                        // Format result text
                        $result_text = '';
                        if (!empty($entry['result_type']) && !empty($entry['gpa']) && 
                            ($entry['result_type'] === 'gpa4' || $entry['result_type'] === 'gpa5')) {
                            $scale = $entry['result_type'] === 'gpa4' ? '4.0' : '5.0';
                            $result_text = 'GPA: ' . esc_html($entry['gpa']) . '/' . $scale;
                        } elseif (!empty($entry['result_type'])) {
                            $result_text = esc_html(ucwords(str_replace(array('1st', '2nd', '3rd'), array('1st ', '2nd ', '3rd '), $entry['result_type'])));
                        }
                        
                        // Build description
                        $description = '';
                        if (!empty($entry['major'])) {
                            $description = 'Specialized in ' . esc_html($entry['major']) . '. ';
                        }
                        $description .= $result_text;
                        ?>
                        <div class="mb-4 pb-3 border-bottom d-flex justify-content-between align-items-start education-item" data-id="<?php echo $entry_id; ?>">
                            <div>
                                <h5><?php echo $degree; ?></h5>
                                <p class="text-muted mb-1"><?php echo $institution; ?> • <?php echo $year; ?></p>
                                <p><?php echo $description; ?></p>
                            </div>
                            <div class="ms-3 text-nowrap">
                                <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editEducationModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No education information added yet.</p>
                <?php endif; ?>
            </div>

            <!-- Training Section -->
<div class="profile-section position-relative training-section">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="section-title">Training Summary</h4>
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editTrainingModal">
            <i class="fas fa-plus"></i> Add
        </button>
    </div>
    
    <?php if (!empty($training_entries)) : ?>
        <?php foreach ($training_entries as $entry) : ?>
            <?php
            // Ensure we have a valid ID
            $entry_id = isset($entry['id']) ? esc_attr($entry['id']) : 'training_' . uniqid();
            
            // Build the entry HTML
$title = esc_html($entry['title']);
$institution = esc_html($entry['institution']);
$start_year = isset($entry['start_year']) ? esc_html($entry['start_year']) : '';
$end_year = isset($entry['end_year']) ? esc_html($entry['end_year']) : '';
$description = esc_html($entry['description']);

// Calculate duration (with proper parameter order and validation)
$duration = '';
if (!empty($start_year) && !empty($end_year)) {
    // Convert year-only to full date format (assuming June as mid-year)
    $start_date = "$start_year-06-01"; // Using June 1st as default
    $end_date = "$end_year-06-01";    // Using June 1st as default
    $duration = calculate_training_duration($start_date, $end_date);
}
?>
<div class="mb-4 border-bottom pb-3 d-flex justify-content-between align-items-start training-item" data-id="<?php echo $entry_id; ?>">
    <div>
        <h5><?php echo $title; ?></h5>
        <p class="text-muted mb-1">
            <?php echo $institution; ?>
            <?php if ($duration): ?>
             • <?php echo $duration; ?>
            <?php endif; ?>
        </p>
        <p><?php echo $description; ?></p>
    </div>
    <div class="ms-3 text-nowrap">
        <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editTrainingModal">
            <i class="fas fa-edit"></i>
        </button>
        <button class="btn btn-sm btn-outline-danger">
            <i class="fas fa-trash-alt"></i>
        </button>
    </div>
</div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>No training information added yet.</p>
    <?php endif; ?>
</div>

           <!-- Experience Section -->
<div class="profile-section position-relative experiance-section">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="section-title">Work Experience</h4>
        <button class="btn btn-sm btn-outline-primary edit-section-btn" data-bs-toggle="modal" data-bs-target="#workExperienceModal">
            <i class="fa-solid fa-plus"></i> Add
        </button>
    </div>
    
    <?php if (!empty($experience_entries)) : ?>
        <?php foreach ($experience_entries as $entry) : ?>
            <?php
            // Ensure we have a valid ID
            $entry_id = isset($entry['id']) ? esc_attr($entry['id']) : 'experience_' . uniqid();
            
            // Extract and sanitize all necessary fields
            $job_title = isset($entry['job_title']) ? esc_html($entry['job_title']) : '';
            $company = isset($entry['company']) ? esc_html($entry['company']) : '';
            $start_date = isset($entry['start_date']) ? $entry['start_date'] : '';
            $end_date = isset($entry['end_date']) ? $entry['end_date'] : '';
            $responsibilities = isset($entry['responsibilities']) ? wp_kses_post($entry['responsibilities']) : '';
            
            // Format dates for display
            $start_date_display = !empty($start_date) ? date('d M, Y', strtotime($start_date)) : '';
            $end_date_display = ($end_date === 'Present') ? 'Present' : (!empty($end_date) ? date('d M, Y', strtotime($end_date)) : '');
            
            // Calculate duration
            $duration = '';
            if (!empty($start_date) && !empty($end_date)) {
                $duration = calculate_duration($start_date, $end_date);
            }
            ?>
            
            <div class="mb-4 border-bottom pb-3 d-flex justify-content-between align-items-start experience-item" data-id="<?php echo $entry_id; ?>">
                <div>
                    <h5><?php echo $job_title; ?></h5>
                    <p class="text-muted mb-1"><span class="company"><?php echo $company; ?></span> • <span class="start-date"><?php echo $start_date_display; ?></span> - <span class="end-date"><?php echo $end_date_display; ?> </span>
                        <?php if (!empty($duration)): ?>
                            • <?php echo $duration; ?>
                        <?php endif; ?>
                    </p>
                    <p class="responsibiltiy"><?php echo $responsibilities; ?></p>
                </div>
                <div class="ms-3 text-nowrap">
                    <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#workExperienceModal">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>No work experience added yet.</p>
    <?php endif; ?>
</div>

            <!-- References Section -->
            <div class="profile-section position-relative reference-section">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="section-title">References</h4>
                    <button class="btn btn-sm btn-outline-primary edit-section-btn" data-bs-toggle="modal" data-bs-target="#referenceModal">
                        <i class="fa-solid fa-plus"></i> Add
                    </button>
                </div>
                
                <?php if (!empty($reference_entries)) : ?>
                    <?php foreach ($reference_entries as $entry) : ?>
                        <div class="mb-4 border-bottom pb-3 d-flex justify-content-between align-items-start reference-entry" data-id="<?php echo esc_attr($entry['id']); ?>">
                            <div>
                                <h5><?php echo esc_html($entry['name']); ?></h5>
                                <p class="text-muted mb-1"><?php echo esc_html($entry['position']); ?>, <?php echo esc_html($entry['company']); ?></p>
                                <p><i class="fas fa-envelope me-2"></i><?php echo esc_html($entry['email']); ?></p>
                                <?php if (!empty($entry['phone'])) : ?>
                                    <p><i class="fas fa-phone me-2"></i><?php echo esc_html($entry['phone']); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="ms-3 text-nowrap">
                                <button class="btn btn-sm btn-outline-secondary me-1 edit-reference-btn" data-bs-toggle="modal" data-bs-target="#referenceModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-reference-btn">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No references added yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-4">
            <!-- Skills Section -->
<div class="profile-section">
    <h4 class="section-title">Skills</h4>
    <div class="d-flex flex-wrap skill-section">
        <?php if (!empty($skills)) : ?>
            <?php foreach ($skills as $skill) : ?>
                <?php
                // Split by comma and trim each skill
                $skill_list = array_map('trim', explode(',', $skill));
                foreach ($skill_list as $single_skill) :
                    if ($single_skill): // skip empty
                ?>
                    <span class="skill-badge rounded-pill">
                        <?php echo esc_html($single_skill); ?>
                        <button type="button" class="btn btn-sm btn-link text-danger ms-2 p-0 remove-skill-btn" data-skill="<?php echo esc_attr($single_skill); ?>" title="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                <?php
                    endif;
                endforeach;
                ?>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No skills added yet.</p>
        <?php endif; ?>
    </div>
    <button class="btn btn-sm btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addSkillsModal">+ Add Skills</button>
</div>

            <!-- Language Section -->
            <div class="profile-section">
    <h4 class="section-title">Languages</h4>
    <div class="d-flex flex-wrap langguage-section">
        <?php if (!empty($languages)) : ?>
            <?php foreach ($languages as $language) : ?>
                <?php
                // Split by comma and trim each language
                $lang_list = array_map('trim', explode(',', $language));
                foreach ($lang_list as $lang) :
                    if ($lang): // skip empty
                ?>
                    <span class="langguage-badge rounded-pill">
                        <?php echo esc_html($lang); ?>
                        <button type="button" class="btn btn-sm btn-link text-danger ms-2 p-0 remove-language-btn" data-language="<?php echo esc_attr($lang); ?>" title="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                <?php
                    endif;
                endforeach;
                ?>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No languages added yet.</p>
        <?php endif; ?>
    </div>
    <button class="btn btn-sm btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addlangguageModal">+ Add Language</button>
</div>

<!-- Resume Section -->
<div class="profile-section resume-section">
    <h4 class="section-title">Custom Resume</h4>
    <?php if (!empty($resume_file)) : ?>
        <div class="resume-preview mb-3">
            <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
            <p class="mb-1"><?php echo esc_html($resume_filename); ?></p>
            <small class="text-muted">Uploaded: <?php echo esc_html(date('M d, Y', strtotime($resume_uploaded))); ?></small>
        </div>
        <div class="d-grid gap-2 resume-actions">
            <a href="<?php echo esc_url($resume_file); ?>" class="btn btn-outline-primary" download><i class="fas fa-download me-1"></i> Download</a>
            <button class="btn btn-outline-danger" id="removeResume"><i class="fas fa-trash me-1"></i> Remove</button>
        </div>
    <?php else : ?>
        <div class="text-center text-muted py-4 no-resume-placeholder">
            <i class="fas fa-file-upload fa-3x mb-3"></i>
            <p>No resume uploaded</p>
        </div>
    <?php endif; ?>
</div>

            <!-- Similar Jobs -->
            <div class="section-card">
                <h4 class="section-title">Recent Jobs</h4>
                <div class="list-group">
                    <?php $args = array(
                    'post_type'      => 'job',
                    'posts_per_page' => 5,
                    'paged'          => $paged,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                );
                
                
                $job_query = new WP_Query($args);
                if ($job_query->have_posts()) :
                    while ($job_query->have_posts()) : $job_query->the_post(); 
                    
                        $salary_type = get_post_meta(get_the_ID(), '_job_salary_type', true);
                         $salary_html = '';
                        if ($salary_type === 'negotiable') {
                            $salary_html = __('Negotiable', 'job-listing');
                        } elseif ($salary_type === 'fixed') {
                            $fixed_salary = get_post_meta(get_the_ID(), '_job_fixed_salary', true);
                            $fixed_salary_period = get_post_meta(get_the_ID(), '_job_fixed_salary_period', true);
                            $salary_html = esc_html($fixed_salary) . '/' . esc_html($fixed_salary_period);
                        } elseif ($salary_type === 'range') {
                            $min_salary = get_post_meta(get_the_ID(), '_job_min_salary', true);
                            $max_salary = get_post_meta(get_the_ID(), '_job_max_salary', true);
                            $salary_range_period = get_post_meta(get_the_ID(), '_job_salary_range_period', true);
                            $salary_html = esc_html($min_salary) . ' - ' . esc_html($max_salary) . '/' . esc_html($salary_range_period);
                        }
                        $location = get_post_meta(get_the_ID(), '_job_location', true);
                        $deadline = get_post_meta(get_the_ID(), '_job_deadline', true);
                        ?>
                    <div class="list-group-item d-flex flex-column gap-2">
                        <h6 class="mb-1"><a href="<?php the_permalink(); ?>" class="job-title">
                                <i class="fas fa-briefcase me-2"></i><?php the_title(); ?></a>
                        </h6>
                        <div><i class="fas fa-dollar-sign me-2"></i><?php echo $salary_html; ?></div>
                        <div><i class="fas fa-map-marker-alt me-2"></i><?php echo $location; ?></div>
                        <div><i class="fas fa-calendar-alt me-2"></i><?php echo $deadline ?></div>
                    </div>
<?php endwhile;   
else : ?>                    <div class="no-jobs-found">
                        <h3><?php _e('No jobs found', 'job-listing'); ?></h3>
                        <p><?php _e('Try adjusting your search criteria or check back later for new opportunities.', 'job-listing'); ?></p>
                    </div>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add nonce for AJAX requests -->
<?php wp_nonce_field('profile_nonce', 'profile_nonce'); ?>

<!-- Modals (same as before) -->
<!-- Edit About Me Modal -->
<div class="modal fade" id="editAboutModal" tabindex="-1" aria-labelledby="editAboutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAboutModalLabel">Edit About Me</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="aboutMeForm">
                    <div class="mb-3">
                        <label for="aboutMeTextarea" class="form-label">About Me</label>
                        <textarea class="form-control" id="aboutMeTextarea" rows="5"><?php echo esc_textarea($about_me); ?></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Personal Information Modal -->
<div class="modal fade" id="editPersonalInfoModal" tabindex="-1" aria-labelledby="editPersonalInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPersonalInfoModalLabel">Edit Personal Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="personalInfoForm">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="fullName" value="<?php echo esc_attr($full_name); ?>">
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Father's Name</label>
                            <input type="text" class="form-control" name="fatherName" value="<?php echo esc_attr($father_name); ?>">
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Mother's Name</label>
                            <input type="text" class="form-control" name="motherName" value="<?php echo esc_attr($mother_name); ?>">
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="dob" value="<?php echo esc_attr($dob); ?>">
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="gender">
                                <option value="Male" <?php selected($gender, 'Male'); ?>>Male</option>
                                <option value="Female" <?php selected($gender, 'Female'); ?>>Female</option>
                                <option value="Other" <?php selected($gender, 'Other'); ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Blood Group</label>
                            <input type="text" class="form-control" name="bloodGroup" value="<?php echo esc_attr($blood_group); ?>">
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Nationality</label>
                            <input type="text" class="form-control" name="nationality" value="<?php echo esc_attr($nationality); ?>">
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Country of Birth</label>
                            <input type="text" class="form-control" name="birthCountry" value="<?php echo esc_attr($birth_country); ?>">
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" name="contactNumber" id="contactNumber" value="<?php echo esc_attr($contact_number); ?>">
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Alternative Contact Number</label>
                            <input type="tel" class="form-control" name="altContact" id="altContact" value="<?php echo esc_attr($alt_contact); ?>">
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo esc_attr($user_data->user_email); ?>">
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Present Address</label>
                            <textarea class="form-control" name="presentAddress" rows="2"><?php echo esc_textarea($present_address); ?></textarea>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Permanent Address</label>
                            <textarea class="form-control" name="permanentAddress" rows="2"><?php echo esc_textarea($permanent_address); ?></textarea>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Present City</label>
                            <input type="text" class="form-control" name="presentcity" value="<?php echo esc_attr($presentcity); ?>">
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Place of Birth</label>
                            <input type="text" class="form-control" name="placeofbirth" value="<?php echo esc_attr($placeofbirth); ?>">
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Education Modal -->
<div class="modal fade" id="editEducationModal" tabindex="-1" aria-labelledby="editEducationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="educationForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEducationModalLabel">Edit Education</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="edulevel" class="form-label">Education level</label>
                            <select name="edulevel[]" class="form-select" required>
                                <option value="">Select Degree</option>
                                <option value="SSC">SSC</option>
                                <option value="HSC">HSC</option>
                                <option value="Honours">Honours</option>
                                <option value="Masters">Masters</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="degree" class="form-label">Degree Title</label>
                            <input type="text" name="degree[]" class="form-control" required>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="institution" class="form-label">Institution Name</label>
                            <input type="text" name="institution[]" class="form-control" required>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label for="majorsub" class="form-label">Group/Major Subject</label>
                            <input type="text" name="majorsub[]" class="form-control" required>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label for="passing_year" class="form-label">Passing Year</label>
                            <select name="passing_year[]" class="form-control" required>
                                <!-- Options will be added by JavaScript -->
                            </select>
                        </div>
                        <div class="col-md-12 mt-2 form-group">
                            <label class="form-label">Result</label>
                            <div class="input-group">
                                <select name="result[]" class="form-control" id="resultSelect" required>
                                    <option value="" disabled selected>Select Result Type</option>
                                    <optgroup label="Divisions">
                                        <option value="1stdivision">1st Division</option>
                                        <option value="2nddivision">2nd Division</option>
                                        <option value="3rddivision">3rd Division</option>
                                    </optgroup>
                                    <optgroup label="Classes">
                                        <option value="1stclass">1st Class</option>
                                        <option value="2ndclass">2nd Class</option>
                                        <option value="3rdclass">3rd Class</option>
                                    </optgroup>
                                    <optgroup label="GPA">
                                        <option value="gpa4">GPA (Out Of 4)</option>
                                        <option value="gpa5">GPA (Out Of 5)</option>
                                    </optgroup>
                                    <optgroup label="Status">
                                        <option value="Appeared">Appeared</option>
                                        <option value="Enrolled">Enrolled</option>
                                        <option value="Awarded">Awarded</option>
                                    </optgroup>
                                </select>
                                <input type="text" name="gpapoint[]" id="gpaPoints" class="form-control d-none" placeholder="Points" style="max-width: 150px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                    <p>Are you sure you want to delete this item? This action cannot be undone.</p>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteReference">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Work Experience Modal -->
<div class="modal fade" id="workExperienceModal" tabindex="-1" aria-labelledby="workExperienceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="workExperienceModalLabel">Add Work Experience</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <form id="workExperienceForm">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="jobTitle" class="form-label">Job Title</label>
                            <input type="text" class="form-control" id="jobTitle" name="jobTitle" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="companyName" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="companyName" name="companyName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="startDate" name="startDate" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="endDate" name="endDate">
                        </div>
                        <div class="col-md-12 mb-3">
                            <input type="checkbox" class="form-check-input" id="currentlyWorking">
                            <label class="form-check-label" for="currentlyWorking">I am currently working in this role</label>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Responsibilities</label>
                            <div id="quillEditor" style="height: 150px;"></div>
                            <input type="hidden" name="responsibilities" id="responsibilities">
                        </div>
                    </div>
                </form>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="saveWorkExperience">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Training Modal -->
<div class="modal fade" id="editTrainingModal" tabindex="-1" aria-labelledby="editTrainingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="trainingForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTrainingModalLabel">Edit Training</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="trainingTitle" class="form-label">Training Title</label>
                        <input type="text" class="form-control" name="trainingTitle" id="trainingTitle" required>
                    </div>
                    <div class="mb-3">
                        <label for="institution" class="form-label">Institution</label>
                        <input type="text" class="form-control" name="institution" id="institution" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="startYear" class="form-label">Start Year</label>
                            <input type="date" class="form-control" name="startYear" id="startYear" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="endYear" class="form-label">End Year</label>
                            <input type="date" class="form-control" name="endYear" id="endYear" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Training Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Training</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Skills Modal -->
<div class="modal fade" id="addSkillsModal" tabindex="-1" aria-labelledby="addSkillsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="addSkillsModalLabel">Add Skills</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <form id="skillsForm">
                    <div class="mb-3">
                        <label for="skillsInput" class="form-label">Enter Skills</label>
                        <input type="text" class="form-control" id="skillsInput" placeholder="e.g., JavaScript, HTML, CSS" required>
                        <div class="form-text">Separate each skill with a comma.</div>
                    </div>
                </form>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveSkills">Save Skills</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Language Modal -->
<div class="modal fade" id="addlangguageModal" tabindex="-1" aria-labelledby="addlangguageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="addlangguageModalLabel">Add Language</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <form id="langguageForm">
                    <div class="mb-3">
                        <label for="langguageInput" class="form-label">Enter Languages</label>
                        <input type="text" class="form-control" id="langguageInput" placeholder="e.g., Bangla, English" required>
                        <div class="form-text">Separate each language with a comma.</div>
                    </div>
                </form>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveLangguage">Save Language</button>
            </div>
        </div>
    </div>
</div>

<!-- Resume Upload Modal -->
<div class="modal fade" id="resumeUploadModal" tabindex="-1" aria-labelledby="resumeUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="resumeUploadModalLabel">Upload Your Resume</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="resumeUploadForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div id="resume-upload-section" class="mb-3">
                        <label for="resumeFile" class="form-label fw-bold">Select Resume File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="resumeFile" name="resumeFile" accept=".pdf,.doc,.docx" required>
                        <div class="form-text">Accepted formats: PDF, DOC, DOCX. Max size: 5MB.</div>
                    </div>
                    <div id="resumeUploadMessage" class="d-none text-center py-4">
                        <div class="success-animation mx-auto mb-3">
                            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                            </svg>
                        </div>
                        <h4 class="alert-heading mb-3">Resume Uploaded Successfully!</h4>
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

<!-- Reference Modal -->
<div class="modal fade" id="referenceModal" tabindex="-1" aria-labelledby="referenceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="referenceModalLabel">Add Reference</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <form id="referenceForm">
                    <input type="hidden" id="referenceId" value="">
                    <div class="mb-3">
                        <label for="referenceName" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="referenceName" required>
                    </div>
                    <div class="mb-3">
                        <label for="referencePosition" class="form-label">Position <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="referencePosition" required>
                    </div>
                    <div class="mb-3">
                        <label for="referenceCompany" class="form-label">Company <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="referenceCompany" required>
                    </div>
                    <div class="mb-3">
                        <label for="referenceEmail" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="referenceEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="referencePhone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="referencePhone">
                    </div>
                </form>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveReference">Save Reference</button>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>