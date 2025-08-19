<?php
/**
 * Template Name: Resume
 *
 * @package Job_Listing_Theme
 */

// Get current user ID
$user_id = get_current_user_id();

// Get user data
$user_data = get_userdata($user_id);
$email = $user_data->user_email;

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="<?php echo bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Of <?php echo esc_html($full_name) . ' | ' . get_bloginfo('name'); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/custom-style.css">
</head>

<body>
    <div class="container resume-container p-0">
        <!-- Resume Header Section -->
        <div class="resume-header">
            <div class="row align-items-center">
                <div class="col-3 text-center">
                    <img src="<?php echo esc_url($profile_picture); ?>" alt="<?php echo esc_attr($full_name); ?>" class="resume-photo rounded-circle">
                </div>
                <div class="col-9">
                    <div class="header-content">
                        <h1 class="mb-0"><?php echo esc_html($full_name); ?></h1> 
                        <div class="contact-info">
                            <div class="contact-line">
                                <i class="fas fa-map-marker-alt"></i> <?php echo esc_html($present_address); ?>
                            </div>
                            <div class="contact-line">
                                <i class="fas fa-phone"></i> <?php echo esc_html($contact_number); ?>
                            </div>
                            <div class="contact-line">
                                <i class="fas fa-envelope"></i> <?php echo esc_html($email); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resume Body -->
        <div class="resume-body p-4">
            <!-- About Section -->
            <div class="mb-4">
                <h3 class="section-title">Professional Summary</h3>
                <p><?php echo $about_me; ?></p>
            </div>

            <!-- Work Experience -->

<!-- Work Experience -->
<div class="mb-4">
    <h3 class="section-title">Work Experience</h3>
    <?php if (!empty($experience_entries)) : ?>
        <?php foreach ($experience_entries as $entry) :
                        // Ensure we have a valid ID
            $entry_id = isset($entry['id']) ? esc_attr($entry['id']) : 'experience_' . uniqid();
            
            // Extract and sanitize all necessary fields
            $job_title = isset($entry['job_title']) ? esc_html($entry['job_title']) : '';
            $company = isset($entry['company']) ? esc_html($entry['company']) : '';
            $start_date = isset($entry['start_date']) ? $entry['start_date'] : '';
            $end_date = isset($entry['end_date']) ? $entry['end_date'] : '';
            $responsibilities = isset($entry['responsibilities']) ? wp_kses_post($entry['responsibilities']) : '';
            
            // Format dates for display
            $start_date_display = !empty($start_date) ? date('M Y', strtotime($start_date)) : '';
            $end_date_display = ($end_date === 'Present') ? 'Present' : (!empty($end_date) ? date('M Y', strtotime($end_date)) : '');
            
            // Calculate duration
            $duration = '';
            if (!empty($start_date) && !empty($end_date)) {
                $duration = calculate_duration($start_date, $end_date);
            } ?>
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <h4 class="job-title mb-1"><?php echo esc_html($job_title); ?></h4>
                    <div class="date-range">
                        <?php
                        echo $start_date_display.' - '.$end_date_display;
                        ?>
                    </div>
                </div>
                <h5 class="company-name mb-2"><?php echo esc_html($company); ?></h5>
                <?php if (!empty($entry['responsibilities'])) : ?>
                    <ul>
                        <?php
                        // If responsibilities are stored as HTML, split into list items
                        if (is_string($entry['responsibilities'])) {
                            // Try to split by <li> if stored as HTML
                            $items = preg_split('/<\/li>/', $entry['responsibilities']);
                            foreach ($items as $item) {
                                $item = trim(strip_tags($item));
                                if ($item) echo '<li>' . esc_html($item) . '</li>';
                            }
                        } elseif (is_array($entry['responsibilities'])) {
                            foreach ($entry['responsibilities'] as $item) {
                                echo '<li>' . esc_html($item) . '</li>';
                            }
                        }
                        ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>No work experience added yet.</p>
    <?php endif; ?>
</div>


<!-- Education -->
<div class="mb-4">
    <h3 class="section-title">Education</h3>
    <?php if (!empty($education_entries)) : ?>
        <?php foreach ($education_entries as $entry) : ?>
            <div class="education-item">
                <div class="d-flex justify-content-between">
                    <h4 class="job-title mb-1">
                        <?php echo !empty($entry['degree']) ? esc_html($entry['degree']) : ''; ?>
                    </h4>
                    <div class="date-range">
                        <?php echo !empty($entry['passing_year']) ? esc_html($entry['passing_year']) : ''; ?>
                    </div>
                </div>
                <h5 class="company-name mb-1">
                    <?php echo !empty($entry['institution']) ? esc_html($entry['institution']) : ''; ?>
                </h5>
                <p>
                    <?php
                    if (!empty($entry['major'])) {
                        echo esc_html($entry['major']);
                    }
                    if (!empty($entry['gpa'])) {
                        echo '. GPA: ' . esc_html($entry['gpa']);
                    }
                    ?>
                </p>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>No education information added yet.</p>
    <?php endif; ?>
</div>



<!-- Training & Certifications -->
<div class="mb-4">
    <h3 class="section-title">Training & Certifications</h3>
    <?php if (!empty($training_entries)) : ?>
        <?php foreach ($training_entries as $entry) : ?>
            <div class="education-item">
                <div class="d-flex justify-content-between">
                    <h4 class="job-title mb-1">
                        <?php echo !empty($entry['title']) ? esc_html($entry['title']) : ''; ?>
                    </h4>
                    <div class="date-range">
                        <?php
                        $start = !empty($entry['start_year']) ? $entry['start_year'] : '';
                        $end = !empty($entry['end_year']) ? $entry['end_year'] : '';
                        echo get_duration_string($start, $end);
                        ?>
                    </div>
                </div>
                <h5 class="company-name mb-1">
                    <?php echo !empty($entry['institution']) ? esc_html($entry['institution']) : ''; ?>
                </h5>
                <p>
                    <?php echo !empty($entry['description']) ? esc_html($entry['description']) : ''; ?>
                </p>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>No training information added yet.</p>
    <?php endif; ?>
</div>


<!-- Skills -->
<div class="mb-4">
    <h3 class="section-title">Technical Skills</h3>
    <div>
        <?php if (!empty($skills)) : ?>
            <?php foreach ($skills as $skill) : ?>
                <?php
                // Split by comma and trim each skill
                $skill_list = array_map('trim', explode(',', $skill));
                foreach ($skill_list as $single_skill) :
                    if ($single_skill): // skip empty
                ?>
                    <span class="skill-badge"><?php echo esc_html($single_skill); ?></span>
                <?php
                    endif;
                endforeach;
                ?>
            <?php endforeach; ?>
        <?php else : ?>
            <span>No skills added yet.</span>
        <?php endif; ?>
    </div>
</div>
                        <!-- Skills -->

<div class="mb-4">
    <h3 class="section-title">Language</h3>
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
            <!-- Personal Information -->

<div class="mb-4">
    <h3 class="section-title">Personal Information</h3>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-borderless personal-info-table">
                <tbody>
                    <tr>
                        <td class="field-label">Full Name</td>
                        <td class="separator">:</td>
                        <td><?php echo esc_html($full_name); ?></td>
                    </tr>
                    <tr>
                        <td class="field-label">Father's name</td>
                        <td class="separator">:</td>
                        <td><?php echo esc_html($father_name); ?></td>
                    </tr>
                    <tr>
                        <td class="field-label">Mother's name</td>
                        <td class="separator">:</td>
                        <td><?php echo esc_html($mother_name); ?></td>
                    </tr>
                    <tr>
                        <td class="field-label">Date of Birth</td>
                        <td class="separator">:</td>
                        <td><?php echo esc_html($dob); ?></td>
                    </tr>
                    <tr>
                        <td class="field-label">Gender</td>
                        <td class="separator">:</td>
                        <td><?php echo esc_html($gender); ?></td>
                    </tr>
                    <tr>
                        <td class="field-label">Nationality</td>
                        <td class="separator">:</td>
                        <td><?php echo esc_html($nationality); ?></td>
                    </tr>
                    <tr>
                        <td class="field-label">Country of birth</td>
                        <td class="separator">:</td>
                        <td><?php echo esc_html($birth_country); ?></td>
                    </tr>
                    <tr>
                        <td class="field-label">Blood Group</td>
                        <td class="separator">:</td>
                        <td><?php echo esc_html($blood_group); ?></td>
                    </tr>
                    <tr>
                        <td class="field-label">Contact number</td>
                        <td class="separator">:</td>
                        <td>
                            <?php
                            echo esc_html($contact_number);
                            if (!empty($alt_contact)) {
                                echo ', ' . esc_html($alt_contact);
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="field-label">Email</td>
                        <td class="separator">:</td>
                        <td><?php echo esc_html($email); ?></td>
                    </tr>
                    <tr>
                        <td class="field-label">Present Address</td>
                        <td class="separator">:</td>
                        <td><?php echo esc_html($present_address); ?></td>
                    </tr>
                    <tr>
                        <td class="field-label">Permanent address</td>
                        <td class="separator">:</td>
                        <td><?php echo esc_html($permanent_address); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <h3 class="section-title">References</h3>
        <?php if (!empty($reference_entries)) : ?>
        
            <div class="row">
                <div class="col-2">
                    <p class="mb-0 field-label">Name</p>
                    <p class="mb-0 field-label">Designation</p>
                    <p class="mb-0 field-label">Organisation</p>
                    <p class="mb-0 field-label">Phone</p>
                    <p class="mb-0 field-label">Email</p>
            </div>
            <?php foreach ($reference_entries as $entry) : ?>
            <div class="col-5">
                <p class="mb-0 text-primary"><strong><?php echo esc_html($entry['name']); ?></strong></p>
                <p class="mb-0"><?php echo esc_html($entry['position']); ?></p>
                <p class="mb-0"><?php echo esc_html($entry['company']); ?></p>
                <p class="mb-0"><?php echo esc_html($entry['phone']); ?></p>
                <p class="mb-0"><?php echo esc_html($entry['email']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="col-12">
            <p>No references added yet.</p>
        </div>
    <?php endif; ?>
</div>
    </div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="js/main.js"></script>
    <!-- intlTelInput JS -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script> -->
</body>

</html>