<?php

/**
 * Add theme activation notice
 */
function job_portal_theme_activation_notice() {
    // Check if our notice has been dismissed
    $dismissed = get_option('job_portal_pages_created_notice_dismissed', false);
    
    // Check if the pages already exist
    $pages_exist = job_portal_check_required_pages_exist();
    
    // Only show the notice if pages don't exist and notice hasn't been dismissed
    if (!$pages_exist && !$dismissed && current_user_can('manage_options')) {
        // Get the list of required pages for display
        $required_pages = job_portal_get_required_pages_list();
        $page_names = array();
        foreach ($required_pages as $slug => $data) {
            $page_names[] = $data['title'];
        }
        ?>
        <div class="notice notice-info is-dismissible job-portal-pages-notice">
            <h3><?php _e('Welcome to Job Portal Theme!', 'job-portal'); ?></h3>
            <p><?php _e('This theme requires some essential pages to function properly. The following pages need to be created:', 'job-portal'); ?></p>
            <ul style="list-style: disc; margin-left: 20px;">
                <?php foreach ($page_names as $name): ?>
                    <li><?php echo esc_html($name); ?></li>
                <?php endforeach; ?>
            </ul>
            <p><?php _e('Would you like to create them now?', 'job-portal'); ?></p>
            <p>
                <button id="create-job-portal-pages" class="button button-primary">
                    <?php _e('Create Required Pages', 'job-portal'); ?>
                </button>
                <button id="dismiss-job-portal-notice" class="button">
                    <?php _e('Skip for Now', 'job-portal'); ?>
                </button>
            </p>
        </div>
        
        <script>
            jQuery(document).ready(function($) {
                // Handle create pages button click
                $('#create-job-portal-pages').on('click', function(e) {
                    e.preventDefault();
                    
                    var button = $(this);
                    button.prop('disabled', true);
                    button.text('<?php _e('Creating Pages...', 'job-portal'); ?>');
                    
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'create_job_portal_pages',
                            nonce: '<?php echo wp_create_nonce('create_job_portal_pages_nonce'); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                $('.job-portal-pages-notice').html(
                                    '<div class="notice notice-success"><p>' + response.data.message + '</p></div>'
                                );
                            } else {
                                $('.job-portal-pages-notice').html(
                                    '<div class="notice notice-error"><p>' + response.data.message + '</p></div>'
                                );
                            }
                        },
                        error: function() {
                            $('.job-portal-pages-notice').html(
                                '<div class="notice notice-error"><p><?php _e('An error occurred. Please try again.', 'job-portal'); ?></p></div>'
                            );
                        }
                    });
                });
                
                // Handle dismiss notice button click
                $('#dismiss-job-portal-notice').on('click', function(e) {
                    e.preventDefault();
                    
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'dismiss_job_portal_pages_notice',
                            nonce: '<?php echo wp_create_nonce('dismiss_job_portal_pages_notice_nonce'); ?>'
                        },
                        success: function() {
                            $('.job-portal-pages-notice').fadeOut();
                        }
                    });
                });
                
                // Handle WordPress dismiss button
                $(document).on('click', '.job-portal-pages-notice .notice-dismiss', function() {
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'dismiss_job_portal_pages_notice',
                            nonce: '<?php echo wp_create_nonce('dismiss_job_portal_pages_notice_nonce'); ?>'
                        }
                    });
                });
            });
        </script>
        <?php
    }
}
add_action('admin_notices', 'job_portal_theme_activation_notice');

/**
 * Get required pages list
 */
function job_portal_get_required_pages_list() {
    return array(
        'applied-jobs' => array(
            'title' => __('Applied Jobs', 'job-portal'),
            'slug' => 'applied-jobs',
            'content' => '',
            'template' => 'applied-jobs.php'
        ),
        'forgot-password' => array(
            'title' => __('Forgot Password', 'job-portal'),
            'slug' => 'forgot-password',
            'content' => '',
            'template' => 'password-reset.php'
        ),
        'home-page' => array(
            'title' => __('Home Page', 'job-portal'),
            'slug' => 'home-page',
            'content' => '',
            'template' => 'home.php'
        ),
        'job-listing' => array(
            'title' => __('Jobs', 'job-portal'),
            'slug' => 'job-listing',
            'content' => '',
            'template' => 'jobs.php'
        ),
        'login' => array(
            'title' => __('Login', 'job-portal'),
            'slug' => 'login',
            'content' => '',
            'template' => 'Login.php'
        ),
        'notification' => array(
            'title' => __('Notification', 'job-portal'),
            'slug' => 'notification',
            'content' => '',
            'template' => 'notification.php'
        ),
        'resume' => array(
            'title' => __('Resume', 'job-portal'),
            'slug' => 'resume',
            'content' => '',
            'template' => 'Resume.php'
        ),
        'settings' => array(
            'title' => __('Settings', 'job-portal'),
            'slug' => 'settings',
            'content' => '',
            'template' => 'settings.php'
        ),
        'signup' => array(
            'title' => __('Signup', 'job-portal'),
            'slug' => 'signup',
            'content' => '',
            'template' => 'Signup.php'
        ),
        'user-profile' => array(
            'title' => __('User Profile', 'job-portal'),
            'slug' => 'user-profile',
            'content' => '',
            'template' => 'user-profile.php'
        ) 
    );
}

/**
 * Check if required pages exist
 */
function job_portal_check_required_pages_exist() {
    $required_pages = job_portal_get_required_pages_list();
    
    foreach ($required_pages as $key => $page_data) {
        // Get the slug from the page data
        $slug = $page_data['slug'];
        
        $page = get_page_by_path($slug);
        if (!$page) {
            return false;
        }
    }
    
    return true;
}

/**
 * AJAX handler for creating required pages
 */
function ajax_create_job_portal_pages() {
    // Verify nonce
    check_ajax_referer('create_job_portal_pages_nonce', 'nonce');
    
    // Check user permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('You do not have sufficient permissions to perform this action.', 'job-portal')));
    }
    
    $required_pages = job_portal_get_required_pages_list();
    
    $created_pages = array();
    $errors = array();
    
    foreach ($required_pages as $key => $page_data) {
        // Get the slug from the page data
        $slug = $page_data['slug'];
        
        // Check if page already exists
        $existing_page = get_page_by_path($slug);
        
        if (!$existing_page) {
            // Create the page
            $page_id = wp_insert_post(array(
                'post_title' => $page_data['title'],
                'post_name' => $slug,
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => get_current_user_id()
            ));
            
            if (is_wp_error($page_id)) {
                $errors[] = sprintf(__('Failed to create %s page: %s', 'job-portal'), $page_data['title'], $page_id->get_error_message());
            } else {
                // Assign the template to the page
                if (!empty($page_data['template'])) {
                    update_post_meta($page_id, '_wp_page_template', $page_data['template']);
                }
                
                $created_pages[] = $page_data['title'];
                
                // Store the page ID in options for later use
                $option_name = 'job_portal_' . $slug . '_page_id';
                update_option($option_name, $page_id);
            }
        } else {
            // Store the existing page ID in options
            $option_name = 'job_portal_' . $slug . '_page_id';
            update_option($option_name, $existing_page->ID);
            
            // Update the template if it's different
            if (!empty($page_data['template'])) {
                $current_template = get_post_meta($existing_page->ID, '_wp_page_template', true);
                if ($current_template !== $page_data['template']) {
                    update_post_meta($existing_page->ID, '_wp_page_template', $page_data['template']);
                }
            }
        }
    }
    
    if (empty($errors)) {
        $message = sprintf(
            __('Successfully created %d pages: %s', 'job-portal'),
            count($created_pages),
            implode(', ', $created_pages)
        );
        
        // Set option to indicate pages have been created
        update_option('job_portal_pages_created', true);
        
        wp_send_json_success(array('message' => $message));
    } else {
        $message = __('Some pages could not be created:', 'job-portal') . ' ' . implode(', ', $errors);
        wp_send_json_error(array('message' => $message));
    }
}
add_action('wp_ajax_create_job_portal_pages', 'ajax_create_job_portal_pages');

/**
 * AJAX handler for dismissing the notice
 */
function ajax_dismiss_job_portal_pages_notice() {
    // Verify nonce
    check_ajax_referer('dismiss_job_portal_pages_notice_nonce', 'nonce');
    
    // Check user permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error();
    }
    
    // Set option to indicate notice has been dismissed
    update_option('job_portal_pages_created_notice_dismissed', true);
    
    wp_send_json_success();
}
add_action('wp_ajax_dismiss_job_portal_pages_notice', 'ajax_dismiss_job_portal_pages_notice');

/**
 * Reset the notice on theme switch
 */
function job_portal_reset_notice_on_theme_switch() {
    // Only reset if our theme is being activated
    if (get_option('theme_switched') && wp_get_theme()->get('Name') === 'Job Portal') {
        // Reset notice dismissal status
        update_option('job_portal_pages_created_notice_dismissed', false);
        
        // Reset pages created status
        update_option('job_portal_pages_created', false);
    }
}
add_action('after_switch_theme', 'job_portal_reset_notice_on_theme_switch');

/**
 * Get page ID by slug
 */
function job_portal_get_page_id($slug) {
    $option_name = 'job_portal_' . $slug . '_page_id';
    return get_option($option_name, 0);
}

/**
 * Get page URL by slug
 */
function job_portal_get_page_url($slug) {
    $page_id = job_portal_get_page_id($slug);
    return $page_id ? get_permalink($page_id) : '';
}