<?php
/**
 * Template Name: Settings
 *
 * @package Job_Listing_Theme
 */

// Redirect non-logged-in users before sending any output
if (! is_user_logged_in()) {
    $login_page = home_url('/login/');
    wp_safe_redirect($login_page);
    exit;
} else {

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
        'about_me',
        'full_name',
        'job_title',
        'company',
        'location',
        'father_name',
        'mother_name',
        'dob',
        'gender',
        'blood_group',
        'nationality',
        'birth_country',
        'contact_number',
        'present_address',
        'permanent_address',
        'skills',
        'languages'
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
                        <button class="btn btn-outline-success resume_upload-btn" style="display:<?php if (!empty($resume_file)) : echo 'none';
                                                                                                    else: echo 'block';
                                                                                                    endif; ?>" data-bs-toggle="modal" data-bs-target="#resumeUploadModal">Upload Resume</button>
                        <a class="btn btn-outline-success" href="<?php echo esc_url(get_permalink(get_page_by_path('resume'))); ?>" target="_blank"><i class="fas fa-eye me-1"></i> View Public Profile</a>
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
                                <input class="form-check-input" type="checkbox" id="emailNotify" <?php echo get_user_meta($user_id, 'email_notifications', true) === '1' ? 'checked' : ''; ?>>
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
                        <button class="btn btn-outline-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="fas fa-lock me-1"></i> Change Password
                        </button>

                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <!-- Profile Picture Upload Modal -->
    <div class="modal fade" id="profilepicUploadModal" tabindex="-1" aria-labelledby="profilepicUploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profilepicUploadModalLabel">Change Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="profilepicUploadForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3 text-center">
                            <img id="profilepicPreview" src="<?php echo esc_url($profile_picture); ?>" alt="Profile Preview" class="img-thumbnail mb-3" style="max-width: 200px;">
                        </div>
                        <div class="mb-3">
                            <label for="profilepic" class="form-label">Select Profile Picture</label>
                            <input type="file" class="form-control" id="profilepic" name="profilepic" accept="image/jpeg" required>
                            <div class="form-text">Only JPG images are allowed. Maximum size: 2MB.</div>
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
                        <button type="submit" class="btn btn-success">Upload Resume</button>
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
                <form id="changePasswordForm" class="">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="currentPassword" name="current_password" required>
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
                            <input type="password" class="form-control" id="newPassword" name="new_password" required>
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
                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
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
                <div id="cngpasssuccessMessage" class="alert text-center d-none" role="alert">
                    <div class="success-animation mx-auto mb-3">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                        </svg>
                    </div>
                    <h4 id="cngpasssuccessMessagetext" class="alert-heading mb-3"></h4>
                </div>
                <div id="cngpasserrorMessage" class="alert text-center d-none" role="alert">
                    <div class="success-animation mx-auto mb-3">
                        <svg class="exclamationmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <!-- Animated circle -->
                            <circle class="exclamationmark__circle" cx="26" cy="26" r="25" fill="none" stroke="#F97316"
                                stroke-width="2" />
                
                            <!-- Exclamation mark parts -->
                            <path class="exclamationmark__stem" fill="none" stroke="#F97316" stroke-width="4" stroke-linecap="round"
                                d="M26 12v20" />
                            <circle class="exclamationmark__dot" fill="#F97316" cx="26" cy="36" r="2" />
                        </svg>
                    </div>
                    <h4 id="cngpasserrorMessagetext" class="alert-heading mt-4"></h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="changePasswordBtn">Change Password</button>
            </div>
        </div>
    </div>
</div>
<?php
// Get email notifications setting
 $email_notifications = get_user_meta($user_id, 'email_notifications', true);
?>

<script>
    // Pass email notifications setting to JavaScript
    window.emailNotifications = <?php echo json_encode($email_notifications === '1'); ?>;
</script>
<?php
}
get_footer(); ?>