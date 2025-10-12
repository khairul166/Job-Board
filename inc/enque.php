<?php
function job_listing_enqueue_scripts() {
    // Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
    
    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
    
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
    // wp_enqueue_script('address.js', get_template_directory_uri() . '/js/address.js', array('jquery'), '1.0', true);
    wp_enqueue_script('application-ajax', get_template_directory_uri() . '/js/application-ajax.js', array('jquery'), '1.0', true);
    
// Custom AJAX script - Only for logged-in users
    // if (is_user_logged_in()) {
        // Load on ALL pages for logged-in users to ensure it works everywhere
        wp_enqueue_script('custom-ajax', get_template_directory_uri() . '/js/custom-ajax.js', array('jquery'), '2.0', true);
        
        // Localize with all necessary variables
        wp_localize_script('custom-ajax', 'ajax_common_vars', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'profile_nonce' => wp_create_nonce('profile_nonce')
        ));
        
    // Add job-specific variables only if needed
    if (is_singular('job')) {
        $user_id = get_current_user_id();
        $user_data = get_userdata($user_id);
        
        wp_localize_script('custom-ajax', 'application_vars', array(
            'ajaxurl' => admin_url('admin-ajax.php'), // FIXED: Added ajaxurl here too
            'job_id'         => get_the_ID(),
            'user_id'        => $user_id,
            'full_name'      => get_user_meta($user_id, 'full_name', true),
            'email'          => $user_data->user_email,
            'contact_number' => get_user_meta($user_id, 'contact_number', true),
            'resume_data'    => get_user_meta($user_id, 'resume_data', true),
            'nonce'          => wp_create_nonce('apply_nonce'),
        ));
    }
    // }
}
add_action('wp_enqueue_scripts', 'job_listing_enqueue_scripts');

/**
 * Localize AJAX script for user profile page
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

function enqueue_job_admin_styles() {
    wp_enqueue_style('admin-style', get_template_directory_uri() . '/css/admin-style.css');
}
add_action('admin_enqueue_scripts', 'enqueue_job_admin_styles');

function enqueue_admin_icon(){
    wp_enqueue_style('admin-icon', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
}
add_action('admin_enqueue_scripts', 'enqueue_admin_icon');


function debug_script_loading() {
    error_log('=== DEBUG SCRIPT LOADING ===');
    error_log('User logged in: ' . is_user_logged_in());
    error_log('Current page ID: ' . get_the_ID());
    error_log('Current page template: ' . get_page_template_slug());
    error_log('Is singular: ' . is_singular());
    error_log('Is page: ' . is_page());
    
    // Check what pages we're on
    if (is_page()) {
        $page = get_post();
        error_log('Page slug: ' . $page->post_name);
        error_log('Page title: ' . $page->post_title);
    }
}
add_action('wp_enqueue_scripts', 'debug_script_loading', 5);


function enqueue_admin_scripts() {
    wp_enqueue_script('job-applications-admin', get_template_directory_uri() . '/js/admincustom.js', array('jquery'), '1.0', true);
    
    // Pass variables to script
    wp_localize_script('job-applications-admin', 'job_applications_vars', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('job_applications_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'enqueue_admin_scripts');

function enqueue_admin_select2() {
    // Select2 CSS
    wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
    
    // Select2 JS
    wp_enqueue_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), '4.1.0', true);
    
    // Custom script for Select2 initialization
    wp_enqueue_script('admin-select2-init', get_template_directory_uri() . '/js/admin-select2-init.js', array('jquery', 'select2-js'), '1.0', true);
    
    // Pass variables to our script
    wp_localize_script('admin-select2-init', 'select2_vars', array(
        'placeholder' => __('Select Skills', 'text-domain'),
    ));
}
add_action('admin_enqueue_scripts', 'enqueue_admin_select2');


function enqueue_test_ajax_script() {
    if (is_page_template('test-page.php')) {
        wp_enqueue_script('test-ajax', get_template_directory_uri() . '/js/test-ajax.js', array('jquery'), '1.0', true);
        
        // Localize script with AJAX URL and nonce
        wp_localize_script('test-ajax', 'test_ajax_vars', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('test_ajax_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_test_ajax_script');



function theme_enqueue_scripts() {
    // Enqueue address.js
    wp_enqueue_script('address', get_template_directory_uri() . '/js/address.js', array(), '1.0', true);
    
    // Enqueue custom-ajax.js with address.js as a dependency
    wp_enqueue_script('custom-ajax', get_template_directory_uri() . '/js/custom-ajax.js', array('jquery', 'address'), '1.0', true);
    
    // Get current user data for address fields
    $user_id = get_current_user_id();
    $present_division = get_user_meta($user_id, 'present_division', true);
    $present_district = get_user_meta($user_id, 'present_district', true);
    $present_upazila = get_user_meta($user_id, 'present_upazila', true);
    $permanent_division = get_user_meta($user_id, 'permanent_division', true);
    $permanent_district = get_user_meta($user_id, 'permanent_district', true);
    $permanent_upazila = get_user_meta($user_id, 'permanent_upazila', true);
    
    // Prepare data to pass to JavaScript
    $address_data = array(
        'present_division' => $present_division,
        'present_district' => $present_district,
        'present_upazila' => $present_upazila,
        'permanent_division' => $permanent_division,
        'permanent_district' => $permanent_district,
        'permanent_upazila' => $permanent_upazila,
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('profile_nonce')
    );
    
    wp_localize_script('custom-ajax', 'addressData', $address_data);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');


