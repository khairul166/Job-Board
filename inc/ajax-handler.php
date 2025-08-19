<?php
// Add AJAX URL and nonce to the page
add_action('wp_head', 'add_ajax_url_to_head');
function add_ajax_url_to_head() {
    if (is_page_template('user-profile.php')) {
        ?>
        <script type="text/javascript">
            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
            var profile_nonce = '<?php echo wp_create_nonce('profile_nonce'); ?>';
        </script>
        <?php
    }
}

// Register AJAX handlers for logged-in users
add_action('wp_ajax_update_about_me', 'update_about_me_handler');
add_action('wp_ajax_update_personal_info', 'update_personal_info_handler');
add_action('wp_ajax_update_education', 'update_education_handler');
add_action('wp_ajax_update_training', 'update_training_handler');
add_action('wp_ajax_update_work_experience', 'update_work_experience_handler');
// add_action('wp_ajax_update_skills', 'update_skills_handler');
add_action('wp_ajax_update_languages', 'update_languages_handler');
add_action('wp_ajax_update_references', 'update_references_handler');
add_action('wp_ajax_upload_resume', 'upload_resume_handler');
add_action('wp_ajax_upload_profile_picture', 'upload_profile_picture_handler');
add_action('wp_ajax_delete_item', 'delete_item_handler');
add_action('wp_ajax_delete_resume', 'delete_resume_handler');
add_action('wp_ajax_add_skills', 'add_skills_handler');
add_action('wp_ajax_remove_skill', 'remove_skill_handler');
add_action('wp_ajax_add_languages', 'add_languages_handler');
add_action('wp_ajax_remove_language', 'remove_language_handler');
// add_action('wp_ajax_submit_application', 'submit_application_handler');

// function submit_application_handler() {
//     check_ajax_referer('apply_nonce', 'nonce');
//     $data = $_POST['data'];
//     $user_id = $data['user_id'];
//     $job_id = $data['job_id'];

//     // Save application (e.g., custom post type or custom table)
//     // Example: Insert into a custom table
//     global $wpdb;
//     $table = $wpdb->prefix . 'job_applications';
//     $wpdb->insert($table, [
//         'user_id' => $user_id,
//         'job_id' => $job_id,
//         'full_name' => sanitize_text_field($data['full_name']),
//         'email' => sanitize_email($data['email']),
//         'contact_number' => sanitize_text_field($data['contact_number']),
//         'resume_data' => maybe_serialize($data['resume']),
//         'applied_at' => current_time('mysql')
//     ]);

//     wp_send_json_success('Application submitted successfully!');
// }

function submit_application_handler() {
    check_ajax_referer('apply_nonce', 'nonce'); // Verify nonce

    global $wpdb;
    $table = $wpdb->prefix . 'job_applications';

    // Get data from POST
    $user_id    = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $job_id     = isset($_POST['job_id']) ? intval($_POST['job_id']) : 0;
    $full_name  = isset($_POST['full_name']) ? sanitize_text_field($_POST['full_name']) : '';
    $email      = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $contact    = isset($_POST['contact_number']) ? sanitize_text_field($_POST['contact_number']) : '';
    $resume     = isset($_POST['resume']) ? sanitize_text_field($_POST['resume']) : '';

    // Check if user already applied
    $already_applied = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM $table WHERE user_id = %d AND job_id = %d",
        $user_id, $job_id
    ));

    if ( $already_applied ) {
        wp_send_json_error('You have already applied for this job.');
    }

    // Insert application
    $inserted = $wpdb->insert(
        $table,
        array(
            'user_id'        => $user_id,
            'job_id'         => $job_id,
            'full_name'      => $full_name,
            'email'          => $email,
            'contact_number' => $contact,
            'resume_data'    => maybe_serialize($resume),
            'applied_at'     => current_time('mysql'),
        )
    );

    if ( $inserted ) {
        wp_send_json_success('Application submitted successfully!');
    } else {
        wp_send_json_error('Failed to submit application.');
    }
}

add_action('wp_ajax_submit_application', 'submit_application_handler');
add_action('wp_ajax_nopriv_submit_application', 'submit_application_handler');


// About Me Handler
function update_about_me_handler() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'profile_nonce')) {
        wp_send_json_error('Security check failed');
    }
    
    // Get current user ID
    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('User not logged in');
    }
    
    // Sanitize input
    $about = sanitize_textarea_field($_POST['about']);
    
    // Update user meta
    update_user_meta($user_id, 'about_me', $about);
    
    wp_send_json_success(array('message' => 'About Me updated successfully'));
}

// Personal Information Handler
function update_personal_info_handler() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'profile_nonce')) {
        wp_send_json_error('Security check failed');
    }
    
    // Get current user ID
    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('User not logged in');
    }
    
    // Sanitize and validate input
    $personal_info = array(
        'full_name' => sanitize_text_field($_POST['fullName']),
        'father_name' => sanitize_text_field($_POST['fatherName']),
        'mother_name' => sanitize_text_field($_POST['motherName']),
        'dob' => sanitize_text_field($_POST['dob']),
        'gender' => sanitize_text_field($_POST['gender']),
        'blood_group' => sanitize_text_field($_POST['bloodGroup']),
        'nationality' => sanitize_text_field($_POST['nationality']),
        'birth_country' => sanitize_text_field($_POST['birthCountry']),
        'contact_number' => sanitize_text_field($_POST['contactNumber']),
        'alt_contact' => sanitize_text_field($_POST['altContact']),
        'email' => sanitize_email($_POST['email']),
        'present_address' => sanitize_textarea_field($_POST['presentAddress']),
        'presentcity' => sanitize_textarea_field($_POST['presentcity']),
        'placeofbirth' => sanitize_textarea_field($_POST['placeofbirth']),
    );
    
    // Update user meta
    foreach ($personal_info as $key => $value) {
        update_user_meta($user_id, $key, $value);
    }
    
    // Update user email if changed
    if ($personal_info['email'] !== wp_get_current_user()->user_email) {
        wp_update_user(array(
            'ID' => $user_id,
            'user_email' => $personal_info['email']
        ));
    }
    
    wp_send_json_success(array('message' => 'Personal information updated successfully'));
}

//================= Education Handler ==============//
// Education Handler
function update_education_handler() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'profile_nonce')) {
        wp_send_json_error('Security check failed');
    }
    
    // Get current user ID
    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('User not logged in');
    }
    
    // Get existing education entries
    $education_entries = get_user_meta($user_id, 'education', true);
    if (!is_array($education_entries)) {
        $education_entries = array();
    }
    
    // Prepare new education entry
    $new_entry = array(
        'id' => sanitize_text_field($_POST['educationId']),
        'level' => sanitize_text_field($_POST['edulevel']),
        'degree' => sanitize_text_field($_POST['degree']),
        'institution' => sanitize_text_field($_POST['institution']),
        'major' => sanitize_text_field($_POST['majorsub']),
        'passing_year' => intval($_POST['passing_year']),
        'result_type' => sanitize_text_field($_POST['result']),
        'gpa' => sanitize_text_field($_POST['gpapoint']),
    );
    
    // If editing existing entry
    if (!empty($new_entry['id'])) {
        $found = false;
        foreach ($education_entries as $key => $entry) {
            if (isset($entry['id']) && $entry['id'] === $new_entry['id']) {
                $education_entries[$key] = $new_entry;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            wp_send_json_error('Education entry not found');
        }
    } else {
        // Add new entry with unique ID
        $new_entry['id'] = 'edu_' . uniqid();
        $education_entries[] = $new_entry;
    }
    
    // Update user meta
    $result = update_user_meta($user_id, 'education', $education_entries);
    
    if ($result === false) {
        wp_send_json_error('Failed to update education');
    }
    
    // Clear any cache
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
    
    wp_send_json_success(array(
        'message' => 'Education updated successfully',
        'id' => $new_entry['id']
    ));
}



// Delete Item Handler
function delete_item_handler() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'profile_nonce')) {
        wp_send_json_error('Security check failed');
    }
    
    // Get current user ID
    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('User not logged in');
    }
    
    // Get item details
    $item_id = sanitize_text_field($_POST['itemId']);
    $item_type = sanitize_text_field($_POST['itemType']);
    
    // Determine which meta field to update
    $meta_key = '';
    switch ($item_type) {
        case 'education':
            $meta_key = 'education';
            break;
        case 'training':
            $meta_key = 'training';
            break;
        case 'experience':
            $meta_key = 'work_experience';
            break;
        case 'reference':
            $meta_key = 'references';
            break;
        default:
            wp_send_json_error('Invalid item type');
    }
    
    // Get existing entries
    $entries = get_user_meta($user_id, $meta_key, true);
    if (!is_array($entries)) {
        wp_send_json_error('No entries found');
    }
    
    // Debug: Log the entries and item ID
    error_log('Entries: ' . print_r($entries, true));
    error_log('Item ID to delete: ' . $item_id);
    
    // Find and remove the item
    $found = false;
    foreach ($entries as $key => $entry) {
        if (isset($entry['id']) && $entry['id'] === $item_id) {
            unset($entries[$key]);
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        wp_send_json_error('Item not found');
    }
    
    // Re-index array
    $entries = array_values($entries);
    
    // Update user meta
    $result = update_user_meta($user_id, $meta_key, $entries);
    
    if ($result === false) {
        wp_send_json_error('Failed to delete item');
    }
    
    // Clear any cache
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
    
    wp_send_json_success(array('message' => 'Item deleted successfully'));
}

// Training Handler
function update_training_handler() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'profile_nonce')) {
        error_log('Training update: Security check failed');
        wp_send_json_error('Security check failed');
    }
    
    // Get current user ID
    $user_id = get_current_user_id();
    if (!$user_id) {
        error_log('Training update: User not logged in');
        wp_send_json_error('User not logged in');
    }
    
    // Get existing training entries
    $training_entries = get_user_meta($user_id, 'training', true);
    if (!is_array($training_entries)) {
        $training_entries = array();
    }
    
    error_log('Training update: Existing entries: ' . print_r($training_entries, true));
    
    // Prepare new training entry
    $new_entry = array(
        'id' => sanitize_text_field($_POST['trainingId']),
        'title' => sanitize_text_field($_POST['title']),
        'institution' => sanitize_text_field($_POST['institution']),
        'start_year' => sanitize_text_field($_POST['startYear']),
        'end_year' => sanitize_text_field($_POST['endYear']),
        'description' => sanitize_textarea_field($_POST['description']),
    );
    
    error_log('Training update: New entry: ' . print_r($new_entry, true));
    
    // If editing existing entry
    if (!empty($new_entry['id'])) {
        $found = false;
        foreach ($training_entries as $key => $entry) {
            if (isset($entry['id']) && $entry['id'] === $new_entry['id']) {
                error_log('Training update: Found existing entry at key ' . $key);
                $training_entries[$key] = $new_entry;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            error_log('Training update: Entry not found');
            wp_send_json_error('Training entry not found');
        }
    } else {
        // Add new entry with unique ID
        $new_entry['id'] = 'training_' . uniqid();
        $training_entries[] = $new_entry;
        error_log('Training update: Added new entry with ID ' . $new_entry['id']);
    }
    
    // Update user meta
    $result = update_user_meta($user_id, 'training', $training_entries);
    
    if ($result === false) {
        error_log('Training update: Failed to update user meta');
        wp_send_json_error('Failed to update training');
    }
    
    // Clear any cache
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
    
    error_log('Training update: Success');
    wp_send_json_success(array(
        'message' => 'Training updated successfully',
        'id' => $new_entry['id']
    ));
}

// Work Experience Handler
function update_work_experience_handler() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'profile_nonce')) {
        wp_send_json_error('Security check failed');
    }
    
    // Get current user ID
    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('User not logged in');
    }
    
    // Get existing experience entries
    $experience_entries = get_user_meta($user_id, 'work_experience', true);
    if (!is_array($experience_entries)) {
        $experience_entries = array();
    }
    
    // Prepare new experience entry
    $new_entry = array(
        'id' => sanitize_text_field($_POST['experienceId']),
        'job_title' => sanitize_text_field($_POST['jobTitle']),
        'company' => sanitize_text_field($_POST['companyName']),
        'start_date' => sanitize_text_field($_POST['startDate']),
        'end_date' => sanitize_text_field($_POST['endDate']),
        'responsibilities' => wp_kses_post($_POST['responsibilities']),
    );
    
    // If editing existing entry
    if (!empty($new_entry['id'])) {
        $found = false;
        foreach ($experience_entries as $key => $entry) {
            if (isset($entry['id']) && $entry['id'] === $new_entry['id']) {
                $experience_entries[$key] = $new_entry;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            wp_send_json_error('Work experience entry not found');
        }
    } else {
        // Add new entry with unique ID
        $new_entry['id'] = 'exp_' . uniqid();
        $experience_entries[] = $new_entry;
    }
    
    // Update user meta
    $result = update_user_meta($user_id, 'work_experience', $experience_entries);
    
    if ($result === false) {
        wp_send_json_error('Failed to update work experience');
    }
    
    // Clear any cache
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
    
    wp_send_json_success(array(
        'message' => 'Work experience updated successfully',
        'id' => $new_entry['id']
    ));
}

// Skills Handler
// function update_skills_handler() {
//     // Verify nonce
//     if (!wp_verify_nonce($_POST['nonce'], 'profile_nonce')) {
//         wp_send_json_error('Security check failed');
//     }
    
//     // Get current user ID
//     $user_id = get_current_user_id();
//     if (!$user_id) {
//         wp_send_json_error('User not logged in');
//     }
    
//     // Sanitize and split skills
//     $skills_input = sanitize_text_field($_POST['skills']);
//     $skills = array_map('trim', explode(',', $skills_input));
//     $skills = array_filter($skills); // Remove empty values
    
//     // Update user meta
//     update_user_meta($user_id, 'skills', $skills);
    
//     wp_send_json_success(array('message' => 'Skills updated successfully'));
// }

// // Languages Handler
// function update_languages_handler() {
//     // Verify nonce
//     if (!wp_verify_nonce($_POST['nonce'], 'profile_nonce')) {
//         wp_send_json_error('Security check failed');
//     }
    
//     // Get current user ID
//     $user_id = get_current_user_id();
//     if (!$user_id) {
//         wp_send_json_error('User not logged in');
//     }
    
//     // Sanitize and split languages
//     $languages_input = sanitize_text_field($_POST['languages']);
//     $languages = array_map('trim', explode(',', $languages_input));
//     $languages = array_filter($languages); // Remove empty values
    
//     // Update user meta
//     update_user_meta($user_id, 'languages', $languages);
    
//     wp_send_json_success(array('message' => 'Languages updated successfully'));
// }

// References Handler
function update_references_handler() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'profile_nonce')) {
        wp_send_json_error('Security check failed');
    }
    
    // Get current user ID
    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('User not logged in');
    }
    
    // Get existing reference entries
    $reference_entries = get_user_meta($user_id, 'references', true);
    if (!is_array($reference_entries)) {
        $reference_entries = array();
    }
    
    // Prepare new reference entry
    $new_entry = array(
        'id' => sanitize_text_field($_POST['referenceId']),
        'name' => sanitize_text_field($_POST['name']),
        'position' => sanitize_text_field($_POST['position']),
        'company' => sanitize_text_field($_POST['company']),
        'email' => sanitize_email($_POST['email']),
        'phone' => sanitize_text_field($_POST['phone']),
    );
    
    // If editing existing entry
    if (!empty($new_entry['id'])) {
        foreach ($reference_entries as $key => $entry) {
            if ($entry['id'] === $new_entry['id']) {
                $reference_entries[$key] = $new_entry;
                break;
            }
        }
    } else {
        // Add new entry with unique ID
        $new_entry['id'] = 'ref_' . uniqid();
        $reference_entries[] = $new_entry;
    }
    
    // Update user meta
    update_user_meta($user_id, 'references', $reference_entries);
    
    wp_send_json_success(array('message' => 'Reference updated successfully', 'id' => $new_entry['id']));
}

// Resume Upload Handler
function upload_resume_handler() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'profile_nonce')) {
        wp_send_json_error('Security check failed');
    }
    
    // Get current user ID
    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('User not logged in');
    }
    
    // Check if file was uploaded
    if (empty($_FILES['resumeFile'])) {
        wp_send_json_error('No file uploaded');
    }
    
    $file = $_FILES['resumeFile'];
    
    // Check for errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        wp_send_json_error('File upload error: ' . $file['error']);
    }
    
    // Validate file type
    $allowed_types = array('application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    if (!in_array($file['type'], $allowed_types)) {
        wp_send_json_error('Invalid file type. Only PDF, DOC, and DOCX files are allowed.');
    }
    
    // Validate file size (5MB limit)
    if ($file['size'] > 5 * 1024 * 1024) {
        wp_send_json_error('File size exceeds 5MB limit.');
    }
    
    // Handle file upload
    $upload_dir = wp_upload_dir();
    $filename = sanitize_file_name($file['name']);
    $filename = $user_id . '_' . time() . '_' . $filename; // Add user ID and timestamp to filename
    $file_path = $upload_dir['path'] . '/' . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $file_path)) {
        wp_send_json_error('Failed to move uploaded file');
    }
    
    // Update user meta with file info
    update_user_meta($user_id, 'resume_file', $upload_dir['url'] . '/' . $filename);
    update_user_meta($user_id, 'resume_filename', $file['name']);
    update_user_meta($user_id, 'resume_uploaded', current_time('mysql'));
    
    wp_send_json_success(array(
        'message' => 'Resume uploaded successfully',
        'url' => $upload_dir['url'] . '/' . $filename,
        'filename' => $file['name']
    ));
}

// Enqueue scripts
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script(
        'profile-upload',
        get_template_directory_uri() . '/js/custom-ajax.js',
        ['jquery'],
        null,
        true
    );
    wp_localize_script('profile-upload', 'profile_upload', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('profile_nonce')
    ]);
});

// Register AJAX handlers
add_action('wp_ajax_upload_profile_picture_handler', 'upload_profile_picture_handler');
add_action('wp_ajax_nopriv_upload_profile_picture_handler', 'upload_profile_picture_handler');

function upload_profile_picture_handler() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'profile_nonce')) {
        wp_send_json_error('Security check failed');
    }

    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('User not logged in');
    }

    if (empty($_FILES['profilepic'])) {
        wp_send_json_error('No file uploaded');
    }

    $file = $_FILES['profilepic'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        wp_send_json_error('File upload error: ' . $file['error']);
    }

    $allowed_types = ['image/jpeg', 'image/jpg'];
    if (!in_array($file['type'], $allowed_types)) {
        wp_send_json_error('Invalid file type. Only JPG images are allowed.');
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        wp_send_json_error('File size exceeds 2MB limit.');
    }

    $upload_dir = wp_upload_dir();
    $filename = $user_id . '_profile_' . time() . '.jpg';
    $file_path = $upload_dir['path'] . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $file_path)) {
        wp_send_json_error('Failed to move uploaded file');
    }

    // Update user meta
    update_user_meta($user_id, 'profile_picture', $upload_dir['url'] . '/' . $filename);

    // Create attachment and set as user avatar
    $attachment = array(
        'post_mime_type' => $file['type'],
        'post_title'     => sanitize_file_name($file['name']),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    $attach_id = wp_insert_attachment($attachment, $file_path);
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
    wp_update_attachment_metadata($attach_id, $attach_data);

    // Set as user avatar (optional)
    update_user_meta($user_id, 'wp_user_avatar', $attach_id);

    wp_send_json_success([
        'message' => 'Profile picture uploaded successfully',
        'url' => $upload_dir['url'] . '/' . $filename
    ]);
}


// Delete Resume Handler
function delete_resume_handler() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'profile_nonce')) {
        wp_send_json_error('Security check failed');
    }
    
    // Get current user ID
    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('User not logged in');
    }
    
    // Get resume file path
    $resume_file = get_user_meta($user_id, 'resume_file', true);
    
    // Delete file if it exists
    if (!empty($resume_file)) {
        $upload_dir = wp_upload_dir();
        $filename = basename($resume_file);
        $file_path = $upload_dir['path'] . '/' . $filename;
        
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    // Delete user meta
    delete_user_meta($user_id, 'resume_file');
    delete_user_meta($user_id, 'resume_filename');
    delete_user_meta($user_id, 'resume_uploaded');
    
    wp_send_json_success(array('message' => 'Resume deleted successfully'));
}




// Add Skills Handler (appends new skills, does not overwrite)
function add_skills_handler() {
    if (!wp_verify_nonce($_POST['nonce'], 'profile_nonce')) {
        wp_send_json_error('Security check failed');
    }
    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('User not logged in');
    }
    $new_skills = isset($_POST['skills']) ? (array)$_POST['skills'] : [];
    $new_skills = array_map('sanitize_text_field', $new_skills);
    $new_skills = array_filter($new_skills);

    // Get existing skills
    $skills = get_user_meta($user_id, 'skills', true);
    if (!is_array($skills)) $skills = [];

    // Merge and deduplicate
    $skills = array_unique(array_merge($skills, $new_skills));

    update_user_meta($user_id, 'skills', $skills);

    wp_send_json_success(['message' => 'Skills added successfully', 'skills' => $skills]);
}

// Remove Skill Handler (removes only the selected skill)
function remove_skill_handler() {
    if (!wp_verify_nonce($_POST['nonce'], 'profile_nonce')) {
        wp_send_json_error('Security check failed');
    }
    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('User not logged in');
    }
    $skill_to_remove = isset($_POST['skill']) ? sanitize_text_field($_POST['skill']) : '';
    if (!$skill_to_remove) {
        wp_send_json_error('No skill specified');
    }

    // Get existing skills
    $skills = get_user_meta($user_id, 'skills', true);
    if (!is_array($skills)) $skills = [];

    // Remove the skill
    $skills = array_diff($skills, [$skill_to_remove]);
    update_user_meta($user_id, 'skills', $skills);

    wp_send_json_success(['message' => 'Skill removed successfully', 'skills' => $skills]);
}

// Add Languages Handler (appends new languages, does not overwrite)
function add_languages_handler() {
    if (!wp_verify_nonce($_POST['nonce'], 'profile_nonce')) {
        wp_send_json_error('Security check failed');
    }
    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('User not logged in');
    }
    $new_languages = isset($_POST['languages']) ? (array)$_POST['languages'] : [];
    $new_languages = array_map('sanitize_text_field', $new_languages);
    $new_languages = array_filter($new_languages);

    // Get existing languages
    $languages = get_user_meta($user_id, 'languages', true);
    if (!is_array($languages)) $languages = [];

    // Merge and deduplicate
    $languages = array_unique(array_merge($languages, $new_languages));

    update_user_meta($user_id, 'languages', $languages);

    wp_send_json_success(['message' => 'Languages added successfully', 'languages' => $languages]);
}

// Remove Language Handler (removes only the selected language)
function remove_language_handler() {
    if (!wp_verify_nonce($_POST['nonce'], 'profile_nonce')) {
        wp_send_json_error('Security check failed');
    }
    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('User not logged in');
    }
    $language_to_remove = isset($_POST['language']) ? sanitize_text_field($_POST['language']) : '';
    if (!$language_to_remove) {
        wp_send_json_error('No language specified');
    }

    // Get existing languages
    $languages = get_user_meta($user_id, 'languages', true);
    if (!is_array($languages)) $languages = [];

    // Remove the language
    $languages = array_diff($languages, [$language_to_remove]);
    update_user_meta($user_id, 'languages', $languages);

    wp_send_json_success(['message' => 'Language removed successfully', 'languages' => $languages]);
}




// Add AJAX handlers for job applications
add_action('wp_ajax_shortlist_applicant', 'ajax_shortlist_applicant');
add_action('wp_ajax_reject_applicant', 'ajax_reject_applicant');
add_action('wp_ajax_download_cv', 'ajax_download_cv');
add_action('wp_ajax_get_cv_details', 'ajax_get_cv_details');

function ajax_shortlist_applicant() {
    // Debug log
    error_log('AJAX shortlist_applicant called');
    error_log('POST data: ' . print_r($_POST, true));
    
    // Verify nonce
    check_ajax_referer('job_applications_nonce', 'nonce');
    
    // Check user permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error('You do not have sufficient permissions to access this page.');
    }
    
    $applicant_id = isset($_POST['applicant_id']) ? intval($_POST['applicant_id']) : 0;
    error_log('Applicant ID: ' . $applicant_id);
    
    if (!$applicant_id) {
        wp_send_json_error('Invalid applicant ID');
    }
    
    global $wpdb;
    $table = $wpdb->prefix . 'job_applications';
    
    // Check if the application exists
    $application = $wpdb->get_row($wpdb->prepare("SELECT id FROM $table WHERE id = %d", $applicant_id));
    if (!$application) {
        wp_send_json_error('Application not found');
    }
    
    // Update application status
    $result = $wpdb->update(
        $table,
        array('status' => 'shortlisted'),
        array('id' => $applicant_id),
        array('%s'),
        array('%d')
    );
    
    if ($result === false) {
        wp_send_json_error('Database error: ' . $wpdb->last_error);
    }
    
    wp_send_json_success('Applicant shortlisted successfully');
}

// Function to reject applicant
function ajax_reject_applicant() {
    // Verify nonce
    check_ajax_referer('job_applications_nonce', 'nonce');
    
    // Check user permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error('You do not have sufficient permissions to access this page.');
    }
    
    $applicant_id = isset($_POST['applicant_id']) ? intval($_POST['applicant_id']) : 0;
    
    if (!$applicant_id) {
        wp_send_json_error('Invalid applicant ID');
    }
    
    global $wpdb;
    $table = $wpdb->prefix . 'job_applications';
    
    // Check if the application exists
    $application = $wpdb->get_row($wpdb->prepare("SELECT id FROM $table WHERE id = %d", $applicant_id));
    if (!$application) {
        wp_send_json_error('Application not found');
    }
    
    // Update application status
    $result = $wpdb->update(
        $table,
        array('status' => 'rejected'),
        array('id' => $applicant_id),
        array('%s'),
        array('%d')
    );
    
    if ($result === false) {
        wp_send_json_error('Database error: ' . $wpdb->last_error);
    }
    
    wp_send_json_success('Applicant rejected successfully');
}
function ajax_download_cv() {
    // Verify nonce
    check_ajax_referer('job_applications_nonce', 'nonce');
    
    // Check user permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error('You do not have sufficient permissions to access this page.');
    }
    
    $applicant_id = isset($_POST['applicant_id']) ? intval($_POST['applicant_id']) : 0;
    
    if (!$applicant_id) {
        wp_send_json_error('Invalid applicant ID');
    }
    
    global $wpdb;
    $table = $wpdb->prefix . 'job_applications';
    
    // Get application data
    $application = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table WHERE id = %d",
        $applicant_id
    ));
    
    if (!$application) {
        wp_send_json_error('Application not found');
    }
    
    // Generate resume HTML using the template
    $html = generate_resume_html($application->user_id);
    
    // Create a temporary file
    $upload_dir = wp_upload_dir();
    $filename = 'CV_' . sanitize_file_name($application->full_name) . '_' . date('Y-m-d') . '.html';
    $file_path = $upload_dir['path'] . '/' . $filename;
    
    // Save the HTML to the file
    file_put_contents($file_path, $html);
    
    // Return file URL and name
    wp_send_json_success(array(
        'file_url' => $upload_dir['url'] . '/' . $filename,
        'file_name' => $filename
    ));
}

// Add this with your other AJAX handlers
add_action('wp_ajax_get_cv_details', 'ajax_get_cv_details');

function ajax_get_cv_details() {
    // Verify nonce
    check_ajax_referer('job_applications_nonce', 'nonce');
    
    // Check user permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error('You do not have sufficient permissions to access this page.');
    }
    
    $applicant_id = isset($_POST['applicant_id']) ? intval($_POST['applicant_id']) : 0;
    
    if (!$applicant_id) {
        wp_send_json_error('Invalid applicant ID');
    }
    
    global $wpdb;
    $table = $wpdb->prefix . 'job_applications';
    
    // Get application data
    $application = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table WHERE id = %d",
        $applicant_id
    ));
    
    if (!$application) {
        wp_send_json_error('Application not found');
    }
    
    // Get user ID from application
    $user_id = $application->user_id;
    
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
    
    // Build HTML for CV with styles
    ob_start();
    ?>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <!-- intlTelInput CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/custom-style.css">
    
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
            <div class="mb-4">
                <h3 class="section-title">Work Experience</h3>
                <?php if (!empty($experience_entries)) : ?>
                    <?php foreach ($experience_entries as $entry) :
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
                        }
                        ?>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <h4 class="job-title mb-1"><?php echo esc_html($job_title); ?></h4>
                                <div class="date-range">
                                    <?php echo $start_date_display.' - '.$end_date_display; ?>
                                </div>
                            </div>
                            <h5 class="company-name mb-2"><?php echo esc_html($company); ?></h5>
                            <?php if (!empty($responsibilities)) : ?>
                                <ul>
                                    <?php
                                    // If responsibilities are stored as HTML, split into list items
                                    if (is_string($responsibilities)) {
                                        // Try to split by <li> if stored as HTML
                                        $items = preg_split('/<\/li>/', $responsibilities);
                                        foreach ($items as $item) {
                                            $item = trim(strip_tags($item));
                                            if ($item) echo '<li>' . esc_html($item) . '</li>';
                                        }
                                    } elseif (is_array($responsibilities)) {
                                        foreach ($responsibilities as $item) {
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
            
            <!-- Languages -->
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
            </div>
            
            <!-- References -->
            <div class="mb-4">
                <h3 class="section-title">References</h3>
                <div class="row">
                    <div class="col-2">
                        <p class="mb-0 field-label">Name</p>
                        <p class="mb-0 field-label">Designation</p>
                        <p class="mb-0 field-label">Organisation</p>
                        <p class="mb-0 field-label">Phone</p>
                        <p class="mb-0 field-label">Email</p>
                    </div>
                    <?php if (!empty($reference_entries)) : ?>
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
                        <div class="col-10">
                            <p>No references added yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/main.js"></script>
    <!-- intlTelInput JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <?php
    $html = ob_get_clean();
    
    wp_send_json_success(array('html' => $html));
}