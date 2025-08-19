<?php
function job_listing_enqueue_scripts() {
    // Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
    
    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
    // wp_enqueue_style('font-awesome', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/fontawesome.min.css');
    
    // Quill CSS
    wp_enqueue_style('quill-css', 'https://cdn.quilljs.com/1.3.7/quill.snow.css');
    
    // intlTelInput CSS
    wp_enqueue_style('intl-tel-input-css', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css');
    
    // Custom CSS
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/css/custom-style.css');
    
    // Bootstrap JS
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array(), '5.3.2', true);
    
    // Quill JS
    wp_enqueue_script('quill-js', 'https://cdn.quilljs.com/1.3.6/quill.js', array(), '1.3.6', true);
    
    // intlTelInput JS
    wp_enqueue_script('intl-tel-input-js', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js', array(), '17.0.8', true);
    
    // Custom JS
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/main.js', array('jquery'), '1.0', true);
    // Custom JS
    wp_enqueue_script('custom-ajax', get_template_directory_uri() . '/js/custom-ajax.js', array('jquery'), '1.0', true);
    wp_enqueue_script('application-ajax', get_template_directory_uri() . '/js/application-ajax.js', array('jquery'), '1.0', true);
        // Only localize if it's a single job post and user is logged in
    if (is_singular('job') && is_user_logged_in()) {
        $user_id = get_current_user_id();
        $user_data = get_userdata($user_id);

        wp_localize_script('custom-ajax', 'application_vars', array(
            'job_id'         => get_the_ID(),
            'user_id'        => $user_id,
            'full_name'      => get_user_meta($user_id, 'full_name', true),
            'email'          => $user_data->user_email,
            'contact_number' => get_user_meta($user_id, 'contact_number', true),
            'resume_data'    => get_user_meta($user_id, 'resume_data', true),
            'ajaxurl'        => admin_url('admin-ajax.php'),
            'nonce'          => wp_create_nonce('apply_nonce'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'job_listing_enqueue_scripts');


/**
 * Localize AJAX script
 */
function job_listing_localize_ajax_script() {
    if (is_page_template('user-profile.php')) {
        wp_localize_script('main', 'jobListingAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('job_listing_nonce'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'job_listing_localize_ajax_script', 100);


// function enqueue_job_admin_assets($hook) {
//     $load_assets = false;
    
//     // Check if we're on the view job applications page (with job_id)
//     if ($hook === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'view_job_applications' && isset($_GET['job_id']) && intval($_GET['job_id']) > 0) {
//         $load_assets = true;
//     }
    
//     // Check if we're on the job applications list page
//     if ($hook === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'job' && isset($_GET['page']) && $_GET['page'] === 'job_applications_list') {
//         $load_assets = true;
//     }
    
//     if ($load_assets) {
//         wp_enqueue_style('admin-style', get_template_directory_uri() . '/css/admin-style.css');
    
//         // Bootstrap JS
//         wp_enqueue_script('bootstrap-js', get_template_directory_uri(). '/js/admincustom.js', array(), '5.3.2', true);
        
//         // Pass variables to JavaScript if needed
//         wp_localize_script('bootstrap-js', 'jobAdminVars', array(
//             'ajaxUrl' => admin_url('admin-ajax.php'),
//             'nonce' => wp_create_nonce('job_admin_nonce')
//         ));
//     }
// }
// add_action('admin_enqueue_scripts', 'enqueue_job_admin_assets');


function enqueue_job_admin_styles() {
    wp_enqueue_style('admin-style', get_template_directory_uri() . '/css/admin-style.css');
    
    // Only enqueue the script once
    // wp_enqueue_script('job-applications-admin', get_template_directory_uri() . '/js/admincustom.js', array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'enqueue_job_admin_styles');

function enqueue_admin_icon(){
    wp_enqueue_style('admin-icon', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
}
add_action('admin_enqueue_scripts', 'enqueue_admin_icon');