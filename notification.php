<?php
/**
 * Template Name: Notification
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

<?php
global $wpdb;
$current_user_id = get_current_user_id(); // get logged-in user ID

// Fetch notifications for the logged-in user
$notifications = $wpdb->get_results(
    $wpdb->prepare("
        SELECT * 
        FROM {$wpdb->prefix}user_notifications 
        WHERE user_id = %d 
        ORDER BY created_at DESC
    ", $current_user_id)
);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Notifications</h3>
    <form method="post">
        <button type="submit" name="mark_all_read" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-check me-1"></i> Mark all as read
        </button>
    </form>
</div>

<div class="list-group">
    <?php if (!empty($notifications)) : ?>
        <?php foreach ($notifications as $note) : ?>
            <a href="<?php echo esc_url( get_permalink($note->related_item_id) ); ?>" 
               class="list-group-item list-group-item-action border-0 <?php echo $note->status === 'unread' ? 'fw-bold' : ''; ?>">
               
                <div class="d-flex flex-wrap justify-content-between p-2">
                    <div>
                        <?php echo esc_html($note->message); ?>
                    </div>
                    <div>
                        <small class="text-muted">
                            <?php echo human_time_diff(strtotime($note->created_at), current_time('timestamp')); ?> ago
                        </small>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="list-group-item border-0 text-muted">No notifications yet.</div>
    <?php endif; ?>
</div>

<?php
// Handle "Mark all as read"
if (isset($_POST['mark_all_read'])) {
    $wpdb->update(
        "{$wpdb->prefix}user_notifications",
        [ 'status' => 'read' ],
        [ 'user_id' => $current_user_id ]
    );
    wp_safe_redirect($_SERVER['REQUEST_URI']);
    exit;
}
?>

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


<?php
get_footer();?>