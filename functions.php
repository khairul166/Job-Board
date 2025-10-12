<?php
require_once(get_template_directory().'/inc/enque.php');
require_once(get_template_directory().'/inc/wp-bootstrap-navwalker-master/class-wp-bootstrap-navwalker.php');
// Include the custom footer menu walker
require_once get_template_directory() . '/inc/footer-menu-walker.php';
require_once get_template_directory() . '/inc/footer-menu-walker-simple.php';
require_once get_template_directory() . '/inc/custom-metabox.php';
require_once get_template_directory() . '/inc/ajax-handler.php';


function job_listing_theme_setup() {
    // Register navigation menus
    register_nav_menus(array(
        'primary'           => __('Primary Menu', 'job-listing'),
        'footer_job_seekers' => __('Footer Job Seekers Menu', 'job-listing'),
        'footer_employers'   => __('Footer Employers Menu', 'job-listing'),
        'footer_bottom'      => __('Footer Bottom Menu', 'job-listing'),
    ));
}
add_action('after_setup_theme', 'job_listing_theme_setup');



// Newsletter form shortcode
function newsletter_form_shortcode() {
    ob_start();
    ?>
    <div class="input-group mb-3">
        <input type="email" class="form-control" placeholder="Your email" aria-label="Your email">
        <button class="btn btn-success" type="button"><?php _e('Subscribe', 'job-listing'); ?></button>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('newsletter_form', 'newsletter_form_shortcode');


/**
 * Add theme customizer settings for social media links
 */
function job_listing_theme_customize_register($wp_customize) {
    // Add a new section for social media links
    $wp_customize->add_section('social_media_section', array(
        'title'    => __('Social Media Links', 'job-listing'),
        'priority' => 30,
    ));

    // Add Facebook setting
    $wp_customize->add_setting('facebook_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('facebook_url', array(
        'label'    => __('Facebook URL', 'job-listing'),
        'section'  => 'social_media_section',
        'settings' => 'facebook_url',
        'type'     => 'url',
    ));

    // Add Twitter setting
    $wp_customize->add_setting('twitter_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('twitter_url', array(
        'label'    => __('Twitter URL', 'job-listing'),
        'section'  => 'social_media_section',
        'settings' => 'twitter_url',
        'type'     => 'url',
    ));

    // Add LinkedIn setting
    $wp_customize->add_setting('linkedin_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('linkedin_url', array(
        'label'    => __('LinkedIn URL', 'job-listing'),
        'section'  => 'social_media_section',
        'settings' => 'linkedin_url',
        'type'     => 'url',
    ));

    // Add Instagram setting
    $wp_customize->add_setting('instagram_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('instagram_url', array(
        'label'    => __('Instagram URL', 'job-listing'),
        'section'  => 'social_media_section',
        'settings' => 'instagram_url',
        'type'     => 'url',
    ));
    // Add to functions.php inside the job_listing_theme_customize_register function

// Add YouTube setting
$wp_customize->add_setting('youtube_url', array(
    'default'           => '#',
    'sanitize_callback' => 'esc_url_raw',
));
$wp_customize->add_control('youtube_url', array(
    'label'    => __('YouTube URL', 'job-listing'),
    'section'  => 'social_media_section',
    'settings' => 'youtube_url',
    'type'     => 'url',
));

// Add Pinterest setting
$wp_customize->add_setting('pinterest_url', array(
    'default'           => '#',
    'sanitize_callback' => 'esc_url_raw',
));
$wp_customize->add_control('pinterest_url', array(
    'label'    => __('Pinterest URL', 'job-listing'),
    'section'  => 'social_media_section',
    'settings' => 'pinterest_url',
    'type'     => 'url',
));
}
add_action('customize_register', 'job_listing_theme_customize_register');

/**
 * Add theme support for custom logo
 */
function job_listing_theme_custom_logo_setup() {
    $defaults = array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array('site-title', 'site-description'),
    );
    add_theme_support('custom-logo', $defaults);
}
add_action('after_setup_theme', 'job_listing_theme_custom_logo_setup');

/**
 * Register Job Post Type
 */
function job_listing_post_type() {
    $labels = array(
        'name'                  => _x('Jobs', 'Post Type General Name', 'job-listing'),
        'singular_name'         => _x('Job', 'Post Type Singular Name', 'job-listing'),
        'menu_name'             => __('Jobs', 'job-listing'),
        'name_admin_bar'        => __('Job', 'job-listing'),
        'archives'              => __('Job Archives', 'job-listing'),
        'attributes'            => __('Job Attributes', 'job-listing'),
        'parent_item_colon'     => __('Parent Job:', 'job-listing'),
        'all_items'             => __('All Jobs', 'job-listing'),
        'add_new_item'          => __('Add New Job', 'job-listing'),
        'add_new'               => __('Add New', 'job-listing'),
        'new_item'              => __('New Job', 'job-listing'),
        'edit_item'             => __('Edit Job', 'job-listing'),
        'update_item'           => __('Update Job', 'job-listing'),
        'view_item'             => __('View Job', 'job-listing'),
        'view_items'            => __('View Jobs', 'job-listing'),
        'search_items'          => __('Search Job', 'job-listing'),
        'not_found'             => __('Not found', 'job-listing'),
        'not_found_in_trash'    => __('Not found in Trash', 'job-listing'),
        'featured_image'        => __('Featured Image', 'job-listing'),
        'set_featured_image'    => __('Set featured image', 'job-listing'),
        'remove_featured_image' => __('Remove featured image', 'job-listing'),
        'use_featured_image'    => __('Use as featured image', 'job-listing'),
        'insert_into_item'      => __('Insert into job', 'job-listing'),
        'uploaded_to_this_item' => __('Uploaded to this job', 'job-listing'),
        'items_list'            => __('Jobs list', 'job-listing'),
        'items_list_navigation' => __('Jobs list navigation', 'job-listing'),
        'filter_items_list'     => __('Filter jobs list', 'job-listing'),
    );
    $args = array(
        'label'                 => __('Job', 'job-listing'),
        'description'           => __('Job Listings', 'job-listing'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'author'), // Added thumbnail support
        'taxonomies'            => array('job_tag', 'job_category', 'job_skill'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-businessman',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );
    register_post_type('job', $args);
}
add_action('init', 'job_listing_post_type', 0);

/**
 * Register Job Tag Taxonomy
 */
function job_tag_taxonomy() {
    $labels = array(
        'name'                       => _x('Job Tags', 'Taxonomy General Name', 'job-listing'),
        'singular_name'              => _x('Job Tag', 'Taxonomy Singular Name', 'job-listing'),
        'menu_name'                  => __('Job Tags', 'job-listing'),
        'all_items'                  => __('All Tags', 'job-listing'),
        'parent_item'                => __('Parent Tag', 'job-listing'),
        'parent_item_colon'          => __('Parent Tag:', 'job-listing'),
        'new_item_name'              => __('New Tag Name', 'job-listing'),
        'add_new_item'               => __('Add New Tag', 'job-listing'),
        'edit_item'                  => __('Edit Tag', 'job-listing'),
        'update_item'                => __('Update Tag', 'job-listing'),
        'view_item'                  => __('View Tag', 'job-listing'),
        'separate_items_with_commas' => __('Separate tags with commas', 'job-listing'),
        'add_or_remove_items'       => __('Add or remove tags', 'job-listing'),
        'choose_from_most_used'      => __('Choose from the most used', 'job-listing'),
        'popular_items'              => __('Popular Tags', 'job-listing'),
        'search_items'               => __('Search Tags', 'job-listing'),
        'not_found'                  => __('Not Found', 'job-listing'),
        'no_terms'                   => __('No tags', 'job-listing'),
        'items_list'                 => __('Tags list', 'job-listing'),
        'items_list_navigation'     => __('Tags list navigation', 'job-listing'),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
    );
    register_taxonomy('job_tag', array('job'), $args);
}
add_action('init', 'job_tag_taxonomy', 0);

/**
 * Register Job Category Taxonomy
 */
function job_category_taxonomy() {
    $labels = array(
        'name'                       => _x('Job Categories', 'Taxonomy General Name', 'job-listing'),
        'singular_name'              => _x('Job Category', 'Taxonomy Singular Name', 'job-listing'),
        'menu_name'                  => __('Job Categories', 'job-listing'),
        'all_items'                  => __('All Categories', 'job-listing'),
        'parent_item'                => __('Parent Category', 'job-listing'),
        'parent_item_colon'          => __('Parent Category:', 'job-listing'),
        'new_item_name'              => __('New Category Name', 'job-listing'),
        'add_new_item'               => __('Add New Category', 'job-listing'),
        'edit_item'                  => __('Edit Category', 'job-listing'),
        'update_item'                => __('Update Category', 'job-listing'),
        'view_item'                  => __('View Category', 'job-listing'),
        'separate_items_with_commas' => __('Separate categories with commas', 'job-listing'),
        'add_or_remove_items'       => __('Add or remove categories', 'job-listing'),
        'choose_from_most_used'      => __('Choose from the most used', 'job-listing'),
        'popular_items'              => __('Popular Categories', 'job-listing'),
        'search_items'               => __('Search Categories', 'job-listing'),
        'not_found'                  => __('Not Found', 'job-listing'),
        'no_terms'                   => __('No categories', 'job-listing'),
        'items_list'                 => __('Categories list', 'job-listing'),
        'items_list_navigation'     => __('Categories list navigation', 'job-listing'),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true, // Set to true for category-like behavior
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
    );
    register_taxonomy('job_category', array('job'), $args);
}
add_action('init', 'job_category_taxonomy', 0);

/**
 * Register Job Skills Taxonomy
 */
function job_skills_taxonomy() {
    $labels = array(
        'name'                       => _x('Job Skills', 'Taxonomy General Name', 'job-listing'),
        'singular_name'              => _x('Job Skill', 'Taxonomy Singular Name', 'job-listing'),
        'menu_name'                  => __('Job Skills', 'job-listing'),
        'all_items'                  => __('All Skills', 'job-listing'),
        'parent_item'                => __('Parent Skill', 'job-listing'),
        'parent_item_colon'          => __('Parent Skill:', 'job-listing'),
        'new_item_name'              => __('New Skill Name', 'job-listing'),
        'add_new_item'               => __('Add New Skill', 'job-listing'),
        'edit_item'                  => __('Edit Skill', 'job-listing'),
        'update_item'                => __('Update Skill', 'job-listing'),
        'view_item'                  => __('View Skill', 'job-listing'),
        'separate_items_with_commas' => __('Separate skills with commas', 'job-listing'),
        'add_or_remove_items'       => __('Add or remove skills', 'job-listing'),
        'choose_from_most_used'      => __('Choose from the most used', 'job-listing'),
        'popular_items'              => __('Popular Skills', 'job-listing'),
        'search_items'               => __('Search Skills', 'job-listing'),
        'not_found'                  => __('Not Found', 'job-listing'),
        'no_terms'                   => __('No skills', 'job-listing'),
        'items_list'                 => __('Skills list', 'job-listing'),
        'items_list_navigation'     => __('Skills list navigation', 'job-listing'),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false, // Set to false for tag-like behavior
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
    );
    register_taxonomy('job_skill', array('job'), $args);
}
add_action('init', 'job_skills_taxonomy', 0);






/**
 * Get unique job types from database with caching
 */
function get_unique_job_types() {
    $cache_key = 'unique_job_types';
    $job_types = get_transient($cache_key);
    
    if (false === $job_types) {
        global $wpdb;
        $job_types_raw = $wpdb->get_col("
            SELECT DISTINCT meta_value
            FROM {$wpdb->postmeta}
            WHERE meta_key = '_job_type'
            AND meta_value != ''
            ORDER BY meta_value
        ");
        
        // Process job types (they are stored as serialized arrays)
        $job_types = array();
        foreach ($job_types_raw as $job_type) {
            $unserialized = maybe_unserialize($job_type);
            if (is_array($unserialized)) {
                foreach ($unserialized as $type) {
                    $job_types[] = $type;
                }
            } else {
                $job_types[] = $job_type;
            }
        }
        
        // Remove duplicates and sort
        $job_types = array_unique($job_types);
        sort($job_types);
        
        // Cache for 1 day
        set_transient($cache_key, $job_types, DAY_IN_SECONDS);
    }
    
    return $job_types;
}



/**
 * Get industries from job categories with caching
 */
function get_industries() {
    $cache_key = 'job_industries';
    $industries = get_transient($cache_key);
    
    if (false === $industries) {
        $industries = get_terms(array(
            'taxonomy' => 'job_category',
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC'
        ));
        
        // Cache for 1 day
        set_transient($cache_key, $industries, DAY_IN_SECONDS);
    }
    
    return $industries;
}

/**
 * Clear filter caches when a job is saved or a taxonomy is updated
 */
function clear_job_filter_caches($post_id) {
    if (get_post_type($post_id) === 'job') {
        delete_transient('unique_job_types');
        delete_transient('experience_levels');
        delete_transient('job_industries');
    }
}
add_action('save_post', 'clear_job_filter_caches');

function clear_taxonomy_caches() {
    delete_transient('experience_levels');
    delete_transient('job_industries');
}
add_action('edited_job_tag', 'clear_taxonomy_caches');
add_action('created_job_tag', 'clear_taxonomy_caches');
add_action('delete_job_tag', 'clear_taxonomy_caches');
add_action('edited_job_category', 'clear_taxonomy_caches');
add_action('created_job_category', 'clear_taxonomy_caches');
add_action('delete_job_category', 'clear_taxonomy_caches');


/**
 * Get experience levels from meta field
 */
function get_experience_levels() {
    $cache_key = 'experience_levels';
    $experience_levels = get_transient($cache_key);
    
    if (false === $experience_levels) {
        global $wpdb;
        $experience_levels = $wpdb->get_col("
            SELECT DISTINCT meta_value
            FROM {$wpdb->postmeta}
            WHERE meta_key = '_job_experience_level'
            AND meta_value != ''
            ORDER BY meta_value
        ");
        
        // Cache for 1 day
        set_transient($cache_key, $experience_levels, DAY_IN_SECONDS);
    }
    
    return $experience_levels;
}

/**
 * Calculates human-readable duration between two dates
 * 
 * @param string $start_date Format: YYYY-MM-DD
 * @param string $end_date Format: YYYY-MM-DD
 * @return string Examples: "1 year", "6 months", "1 year 1 month"
 */
function calculate_training_duration($start_date, $end_date) {
    try {
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        
        if ($start > $end) return ''; // Invalid date range
        
        $interval = $start->diff($end);
        $years = $interval->y;
        $months = $interval->m;
        
        // If duration is less than 1 month, return "1 month" as minimum
        if ($years == 0 && $months == 0 && $interval->days > 0) {
            return '1 month';
        }
        
        $parts = [];
        if ($years > 0) {
            $parts[] = $years . ($years != 1 ? ' years' : ' year');
        }
        if ($months > 0) {
            $parts[] = $months . ($months != 1 ? ' months' : ' month');
        }
        
        return implode(' ', $parts);
        
    } catch (Exception $e) {
        return ''; // Return empty string on invalid dates
    }
}

// Helper function to calculate duration
function calculate_duration($start_date, $end_date) {
    $start = new DateTime($start_date);
    $end = ($end_date === 'Present') ? new DateTime() : new DateTime($end_date);
    
    $interval = $start->diff($end);
    
    $years = $interval->y;
    $months = $interval->m;
    
    $duration = '';
    if ($years > 0) {
        $duration .= $years . ' year' . ($years > 1 ? 's' : '');
    }
    if ($months > 0) {
        if (!empty($duration)) {
            $duration .= ' ';
        }
        $duration .= $months . ' month' . ($months > 1 ? 's' : '');
    }
    
    return empty($duration) ? '0 month' : $duration;
}

function get_duration_string($start, $end) {
    if (empty($start) || empty($end)) return '';
    $start_date = new DateTime($start);
    $end_date = new DateTime($end);
    if ($end_date < $start_date) return '';
    $interval = $start_date->diff($end_date);
    $years = $interval->y;
    $months = $interval->m;
    $days = $interval->d;
    $duration = '';
    if ($years > 0) {
        $duration .= sprintf('%02d year%s', $years, $years > 1 ? 's' : '');
    }
    if ($months > 0) {
        if ($duration) $duration .= ' ';
        $duration .= sprintf('%02d month%s', $months, $months > 1 ? 's' : '');
    }
    if (!$duration) {
        // Show days if less than 1 month
        $duration = sprintf('%d day%s', $days, $days > 1 ? 's' : '');
    }
    return $duration;
}

function create_job_applications_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'job_applications';
    
    // Check if table exists
    if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            job_id INT NOT NULL,
            full_name VARCHAR(255),
            email VARCHAR(255),
            contact_number VARCHAR(50),
            resume_data LONGTEXT,
            applied_at DATETIME,
            status VARCHAR(20) DEFAULT 'new',
            INDEX job_id_index (job_id),
            INDEX status_index (status)
        ) $charset_collate;";
        
        dbDelta($sql);
    } else {
        // Table exists, check if status column exists
        $column_exists = $wpdb->get_results($wpdb->prepare(
            "SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = %s 
            AND TABLE_NAME = %s 
            AND COLUMN_NAME = 'status'",
            DB_NAME,
            $table_name
        ));
        
        // If status column doesn't exist, add it
        if (empty($column_exists)) {
            $wpdb->query("ALTER TABLE $table_name ADD COLUMN status VARCHAR(20) DEFAULT 'new' AFTER applied_at");
            $wpdb->query("ALTER TABLE $table_name ADD INDEX job_id_index (job_id)");
            $wpdb->query("ALTER TABLE $table_name ADD INDEX status_index (status)");
        }
    }
}
add_action('init', 'create_job_applications_table');

// Create notification table on theme activation
function create_notification_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'user_notifications';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        message text NOT NULL,
        type varchar(50) NOT NULL,
        status varchar(20) DEFAULT 'unread',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        related_item_id bigint(20) DEFAULT NULL,
        PRIMARY KEY  (id),
        KEY user_id (user_id),
        KEY status (status)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('init', 'create_notification_table');


function update_job_applications_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'job_applications';
    
    // Check if interview_date column exists
    $column_exists = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s",
        DB_NAME,
        $table_name,
        'interview_date'
    ));
    
    if (empty($column_exists)) {
        $wpdb->query("ALTER TABLE $table_name ADD COLUMN interview_date datetime NULL AFTER status");
    }
    
    // Check if interview_location column exists
    $column_exists = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s",
        DB_NAME,
        $table_name,
        'interview_location'
    ));
    
    if (empty($column_exists)) {
        $wpdb->query("ALTER TABLE $table_name ADD COLUMN interview_location varchar(255) NULL AFTER interview_date");
    }
}
add_action('init', 'update_job_applications_table');

function create_user_profile_page() {
    // Only run on theme activation
    if ( ! get_option( 'user_profile_page_created' ) ) {

        // Check if page with slug 'user-profile' exists
        $page = get_page_by_path('user-profile');

        if ( ! $page ) {
            // Page does not exist → create it
            $page_id = wp_insert_post( array(
                'post_title'   => 'User Profile',
                'post_name'    => 'user-profile',
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '', // optional default content
            ) );

            if ( $page_id && ! is_wp_error( $page_id ) ) {
                // Assign a template if you have a template file
                // Template file should exist in your theme, e.g., page-user-profile.php
                update_post_meta( $page_id, '_wp_page_template', 'user-profile.php' );

                // Mark that we've created the page so we don't do it again
                update_option( 'user_profile_page_created', 1 );
            }
        }
    }
}
add_action( 'after_switch_theme', 'create_user_profile_page' );


// Register the admin menu pages
add_action('admin_menu', 'register_job_applications_admin_pages');

function register_job_applications_admin_pages() {
    // Main applications list page
    $main_page_hook = add_submenu_page(
        'edit.php?post_type=job',
        'Job Applications',
        'Job Applications',
        'manage_options',
        'job_applications_list',
        'display_job_applications_page'
    );
    
    // View applications page (hidden from menu)
    add_submenu_page(
        'job_applications_list',
        'View Applications',
        '',
        'manage_options',
        'view_job_applications',
        'view_job_applications_page'
    );
    
    // Remove the duplicate submenu item
    add_action('admin_head', function() {
        remove_submenu_page('job_applications_list', 'view_job_applications');
    });
    
    // Add admin styles
    add_action('admin_print_styles-' . $main_page_hook, 'job_applications_admin_styles');
}

// Admin page styles
function job_applications_admin_styles() {
    echo '<style>
        .job-applications-table { width: 100%; }
        .job-applications-table th { text-align: left; }
        .resume-data { max-width: 300px; max-height: 100px; overflow: auto; }
    </style>';
}

// Main applications list page
function display_job_applications_page() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    global $wpdb;
    $table = $wpdb->prefix . 'job_applications';

    // Get all jobs
    $jobs = get_posts(array(
        'post_type' => 'job',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ));

    echo '<div class="wrap">';
    echo '<h1>' . esc_html__('Job Applications', 'text-domain') . '</h1>';
    
    if (empty($jobs)) {
        echo '<p>' . esc_html__('No jobs found.', 'text-domain') . '</p>';
        echo '</div>';
        return;
    }

    echo '<table class="wp-list-table widefat fixed striped job-applications-table">';
    echo '<thead>
            <tr>
                <th>' . esc_html__('Job ID', 'text-domain') . '</th>
                <th>' . esc_html__('Job Title', 'text-domain') . '</th>
                <th>' . esc_html__('Applications', 'text-domain') . '</th>
                <th>' . esc_html__('Status', 'text-domain') . '</th>
                <th>' . esc_html__('Action', 'text-domain') . '</th>
            </tr>
          </thead><tbody>';

    foreach ($jobs as $job) {
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE job_id = %d",
            $job->ID
        ));

        $view_link = add_query_arg(
            array(
                'page' => 'view_job_applications',
                'job_id' => $job->ID
            ),
            admin_url('admin.php')
        );
        $deadline = get_post_meta($job->ID, '_job_deadline', true);
        if($deadline >= date('Y-m-d')){
            $job_status = 'Active';
        } else {
            $job_status = 'Expired';
        }      
        echo '<tr>
                <td>' . esc_html($job->ID) . '</td>
                <td>' . esc_html($job->post_title) . '</td>
                <td>' . esc_html($count) . '</td>
                <td>' . esc_html($job_status) . '</td>
                <td><a class="button button-primary" href="' . esc_url($view_link) . '">' . esc_html__('View Applications', 'text-domain') . '</a></td>
              </tr>';
    }

    echo '</tbody></table></div>';
}




// Define helper functions outside of the main function
if (!function_exists('parse_user_skills')) {
    function parse_user_skills($skills_data) {
        $skills = array();
        
        // If it's already an array, use it directly
        if (is_array($skills_data)) {
            // If it's a multi-dimensional array, flatten it
            foreach ($skills_data as $item) {
                if (is_array($item)) {
                    // If it's an array of strings, add each string
                    foreach ($item as $sub_item) {
                        if (is_string($sub_item)) {
                            $individual_skills = array_map('trim', explode(',', $sub_item));
                            $skills = array_merge($skills, $individual_skills);
                        }
                    }
                } elseif (is_string($item)) {
                    $individual_skills = array_map('trim', explode(',', $item));
                    $skills = array_merge($skills, $individual_skills);
                }
            }
        } elseif (is_string($skills_data)) {
            // Check if it's a serialized array
            $unserialized_data = @unserialize($skills_data);
            if ($unserialized_data !== false && is_array($unserialized_data)) {
                // It's a serialized array, process it
                foreach ($unserialized_data as $item) {
                    if (is_array($item)) {
                        foreach ($item as $sub_item) {
                            if (is_string($sub_item)) {
                                $individual_skills = array_map('trim', explode(',', $sub_item));
                                $skills = array_merge($skills, $individual_skills);
                            }
                        }
                    } elseif (is_string($item)) {
                        $individual_skills = array_map('trim', explode(',', $item));
                        $skills = array_merge($skills, $individual_skills);
                    }
                }
            } else {
                // It's a simple string, not serialized
                $skills = array_map('trim', explode(',', $skills_data));
            }
        }
        
        // Remove empty values and return
        return array_filter($skills);
    }
}

if (!function_exists('calculate_age')) {
    function calculate_age($date_of_birth) {
        if (empty($date_of_birth)) {
            return 0;
        }
        
        try {
            // Try to create DateTime object from various possible formats
            $birth_date = new DateTime($date_of_birth);
            $today = new DateTime();
            return $birth_date->diff($today)->y;
        } catch (Exception $e) {
            // If DateTime fails, try to parse common formats manually
            $formats = array(
                'Y-m-d', 'm/d/Y', 'd/m/Y', 'Y/m/d', 'd-m-Y', 
                'F j, Y', 'M j, Y', 'j F, Y', 'j M, Y'
            );
            
            foreach ($formats as $format) {
                try {
                    $birth_date = DateTime::createFromFormat($format, $date_of_birth);
                    if ($birth_date) {
                        $today = new DateTime();
                        return $birth_date->diff($today)->y;
                    }
                } catch (Exception $e) {
                    continue;
                }
            }
            
            return 0; // Return 0 if all parsing attempts fail
        }
    }
}

if (!function_exists('calculate_total_experience_months')) {
    function calculate_total_experience_months($work_experience) {
        if (!is_array($work_experience)) {
            $work_experience = maybe_unserialize($work_experience);
        }
        
        if (!is_array($work_experience)) {
            return 0;
        }
        
        $total_months = 0;
        foreach ($work_experience as $entry) {
            if (!isset($entry['start_date']) || empty($entry['start_date'])) {
                continue;
            }
            
            try {
                $start_date = new DateTime($entry['start_date']);
                
                if (isset($entry['end_date']) && !empty($entry['end_date']) && strtolower($entry['end_date']) !== 'present') {
                    $end_date = new DateTime($entry['end_date']);
                } else {
                    $end_date = new DateTime(); // current date for ongoing jobs
                }
                
                $interval = $start_date->diff($end_date);
                $months = $interval->y * 12 + $interval->m;
                $total_months += $months;
            } catch (Exception $e) {
                // Skip invalid dates
                continue;
            }
        }
        
        return $total_months;
    }
}

function view_job_applications_page() {
    // Add this right after wp_localize_script
    wp_add_inline_script('job-applications-admin', 'console.log("Job applications script loaded"); console.log(job_applications_vars);');
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
    
    // Create nonce for AJAX requests
    $nonce = wp_create_nonce('job_applications_nonce');
    
    // Enqueue our custom script and pass variables to it
    
    global $wpdb;
    $job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;
    if (!$job_id) {
        echo '<div class="wrap"><p>' . esc_html__('Invalid Job ID', 'text-domain') . '</p></div>';
        return;
    }
    // Add the interview scheduling modal at the end of the function, before the closing div
    ?>
    <!-- Interview Scheduling Modal -->
    <div id="interview-modal" class="interview-modal" style="display:none;">
        <div class="interview-modal-content">
            <div class="interview-modal-header">
                <h2>Schedule Interview</h2>
                <span class="interview-modal-close">&times;</span>
            </div>
            <div class="interview-modal-body">
                <input type="hidden" id="application-ids" name="application_ids">
                <div class="form-group">
                    <label for="interview-date">Date & Time</label>
                    <input type="datetime-local" id="interview-date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="interview-location">Location</label>
                    <input type="text" id="interview-location" class="form-control" placeholder="Office address" required>
                </div>
                <div class="form-group">
                    <label for="interview-notes">Notes (Optional)</label>
                    <textarea id="interview-notes" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="interview-modal-footer">
                <button type="button" class="button button-secondary" id="cancel-interview">Cancel</button>
                <button type="button" class="button button-primary" id="save-interview">Schedule Interview</button>
            </div>
        </div>
    </div>
   <?php 
    // Pagination setup
    $per_page = 50;
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $per_page;
    
    // Get filter values
    $user_present_district = isset($_GET['present_district']) ? sanitize_text_field($_GET['present_district']) : '';
    $user_permanent_district = isset($_GET['permanent_district']) ? sanitize_text_field($_GET['permanent_district']) : '';
    $highest_education = isset($_GET['highest_education']) ? sanitize_text_field($_GET['highest_education']) : '';
    $experience_years = isset($_GET['experience_years']) ? intval($_GET['experience_years']) : 0;
    $current_status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : 'all';
    
    // New filter values
    $min_age = isset($_GET['min_age']) ? intval($_GET['min_age']) : '';
    $max_age = isset($_GET['max_age']) ? intval($_GET['max_age']) : '';
    $gender = isset($_GET['gender']) ? sanitize_text_field($_GET['gender']) : '';
    
    // Get experience range values
    $min_experience = isset($_GET['min_experience']) ? intval($_GET['min_experience']) : 0;
    $max_experience = isset($_GET['max_experience']) ? intval($_GET['max_experience']) : 20;
    
    // Get date range filter values
    $application_date_from = isset($_GET['application_date_from']) ? sanitize_text_field($_GET['application_date_from']) : '';
    $application_date_to = isset($_GET['application_date_to']) ? sanitize_text_field($_GET['application_date_to']) : '';
    
    // Get skills filter values
    $skills = isset($_GET['skills']) ? array_map('sanitize_text_field', (array)$_GET['skills']) : array();
    
    // Get status counts
    $applications_table = $wpdb->prefix . 'job_applications';
    $status_counts = $wpdb->get_results($wpdb->prepare(
        "SELECT status, COUNT(*) as count FROM {$applications_table} 
        WHERE job_id = %d GROUP BY status", 
        $job_id
    ));
    $shortlisted_count = 0;
    $rejected_count = 0;
    $new_count = 0;
    $interview_scheduled_count = 0;
    foreach ($status_counts as $count) {
        if ($count->status === 'shortlisted') {
            $shortlisted_count = $count->count;
        } elseif ($count->status === 'rejected') {
            $rejected_count = $count->count;
        } elseif ($count->status === 'new' || $count->status === 'pending') {
            $new_count += $count->count;
        } elseif ($count->status === 'interview_scheduled') {
            $interview_scheduled_count = $count->count;
        }
    }
    
    // Build the base query
    $usermeta_table = $wpdb->prefix . 'usermeta';
    
    // Start with the base query joining applications with users
    $where = "WHERE ja.job_id = %d";
    $prepare_values = array($job_id);
    
    // Join clauses for usermeta
    $joins = array();
    
    // Add filters to the query (basic ones that can be done in SQL)
    if (!empty($user_present_district)) {
        $joins[] = "LEFT JOIN {$usermeta_table} pd ON pd.user_id = ja.user_id AND pd.meta_key = 'presentcity'";
        $where .= " AND pd.meta_value = %s";
        $prepare_values[] = $user_present_district;
    }
    
    if (!empty($user_permanent_district)) {
        $joins[] = "LEFT JOIN {$usermeta_table} pmd ON pmd.user_id = ja.user_id AND pmd.meta_key = 'placeofbirth'";
        $where .= " AND pmd.meta_value = %s";
        $prepare_values[] = $user_permanent_district;
    }
    
    if (!empty($highest_education)) {
        // For education, we need to check if any of the user's education entries match
        $joins[] = "LEFT JOIN {$usermeta_table} edu ON edu.user_id = ja.user_id AND edu.meta_key = 'education'";
        $where .= " AND edu.meta_value LIKE %s";
        $prepare_values[] = '%' . $wpdb->esc_like($highest_education) . '%';
    }
    
    // Add gender filter
    if (!empty($gender)) {
        $joins[] = "LEFT JOIN {$usermeta_table} g ON g.user_id = ja.user_id AND g.meta_key = 'gender'";
        $where .= " AND g.meta_value = %s";
        $prepare_values[] = $gender;
    }
    
    // Add status filter
    if ($current_status !== 'all') {
        $where .= " AND ja.status = %s";
        $prepare_values[] = $current_status;
    }
    
    // Add application date range filter
    if (!empty($application_date_from) || !empty($application_date_to)) {
        if (!empty($application_date_from) && !empty($application_date_to)) {
            $where .= " AND DATE(ja.applied_at) BETWEEN %s AND %s";
            $prepare_values[] = $application_date_from;
            $prepare_values[] = $application_date_to;
        } elseif (!empty($application_date_from)) {
            $where .= " AND DATE(ja.applied_at) >= %s";
            $prepare_values[] = $application_date_from;
        } elseif (!empty($application_date_to)) {
            $where .= " AND DATE(ja.applied_at) <= %s";
            $prepare_values[] = $application_date_to;
        }
    }
    
    // Combine all joins
    $join_clause = implode(' ', $joins);
    
    // Get all application IDs that match the basic filters first
    $query_ids = "SELECT DISTINCT ja.id FROM {$applications_table} ja {$join_clause} {$where}";
    $all_app_ids = $wpdb->get_col($wpdb->prepare($query_ids, $prepare_values));
    
    // Filter by skills, experience and age in PHP
    $filtered_app_ids = array();
    $filter_by_skills = !empty($skills);
    $filter_by_experience = ($min_experience > 0 || $max_experience < 20);
    $filter_by_age = (!empty($min_age) || !empty($max_age));
    
    if ($filter_by_skills || $filter_by_experience || $filter_by_age) {
        foreach ($all_app_ids as $app_id) {
            // Get user ID for this application
            $user_id = $wpdb->get_var($wpdb->prepare(
                "SELECT user_id FROM {$applications_table} WHERE id = %d", 
                $app_id
            ));
            
            if (!$user_id) {
                continue;
            }
            
            // Check skills filter
            $include_by_skills = true;
            if ($filter_by_skills) {
                $user_skills_raw = get_user_meta($user_id, 'skills', true);
                $user_skills = parse_user_skills($user_skills_raw);
                
                // Check if any of the selected skills are in the user's skills
                $match_found = false;
                foreach ($skills as $skill) {
                    if (in_array($skill, $user_skills)) {
                        $match_found = true;
                        break;
                    }
                }
                $include_by_skills = $match_found;
            }
            
            // Check experience filter
            $include_by_experience = true;
            if ($filter_by_experience) {
                $work_experience = get_user_meta($user_id, 'work_experience', true);
                $total_months = calculate_total_experience_months($work_experience);
                $include_by_experience = ($total_months >= ($min_experience * 12) && $total_months <= ($max_experience * 12));
            }
            
            // Check age filter
            $include_by_age = true;
            if ($filter_by_age) {
                $date_of_birth = get_user_meta($user_id, 'date_of_birth', true);
                $age = calculate_age($date_of_birth);
                
                if (!empty($min_age) && $age < $min_age) {
                    $include_by_age = false;
                }
                if (!empty($max_age) && $age > $max_age) {
                    $include_by_age = false;
                }
            }
            
            if ($include_by_skills && $include_by_experience && $include_by_age) {
                $filtered_app_ids[] = $app_id;
            }
        }
        $all_app_ids = $filtered_app_ids;
    }
    
    // Now get the total count for pagination
    $total_applications = count($all_app_ids);
    $total_pages = ceil($total_applications / $per_page);
    
    // Get the current page of application IDs
    $paged_app_ids = array_slice($all_app_ids, $offset, $per_page);
    
    // Now fetch the full application data for these IDs
    if (!empty($paged_app_ids)) {
        $placeholders = implode(',', array_fill(0, count($paged_app_ids), '%d'));
        $query = "SELECT * FROM {$applications_table} WHERE id IN ($placeholders) ORDER BY applied_at DESC";
        $results = $wpdb->get_results($wpdb->prepare($query, $paged_app_ids));
    } else {
        $results = array();
    }
    
    $job = get_post($job_id);
    $job_title = $job ? esc_html($job->post_title) : esc_html__('Unknown Job', 'text-domain');
    
    echo '<div class="wrap">';
    echo '<h1>' . sprintf(esc_html__('Applications for: %s', 'text-domain'), $job_title) . '</h1>';
    
    // Add back link
    $back_link = add_query_arg(
        array(
            'page' => 'job_applications_list'
        ),
        admin_url('edit.php?post_type=job')
    );
    echo '<p><a href="' . esc_url($back_link) . '" class="button">&larr; ' . esc_html__('Back to Applications', 'text-domain') . '</a></p>';
    
    // Get filter options
    $present_districts = get_present_city_options();
    $permanent_districts = get_birth_place_options();
    $education_levels = get_education_options();
    $gender_options = array('Male', 'Female', 'Other');
    
    // Build the URL for filters without resetting other parameters
    $base_url = admin_url('admin.php?page=view_job_applications&job_id=' . $job_id);
    ?>
    
    <div class="applications-layout">
        <!-- Sidebar with filters -->
        <div class="filters-sidebar">
            <h3>Filter Applications</h3>
            
            <form method="get" action="<?php echo esc_url($base_url); ?>">
                <input type="hidden" name="post_type" value="job">
                <input type="hidden" name="page" value="view_job_applications">
                <input type="hidden" name="job_id" value="<?php echo esc_attr($job_id); ?>">
                
                <!-- Preserve status parameter -->
                <?php if (!empty($current_status) && $current_status !== 'all'): ?>
                    <input type="hidden" name="status" value="<?php echo esc_attr($current_status); ?>">
                <?php endif; ?>
                
                <div class="filter-group">
                    <label for="present_district"><?php _e('Present District', 'text-domain'); ?></label>
                    <select name="present_district" id="present_district">
                        <option value=""><?php _e('All Districts', 'text-domain'); ?></option>
                        <?php foreach ($present_districts as $district): ?>
                            <option value="<?php echo esc_attr($district); ?>" <?php selected($user_present_district, $district); ?>>
                                <?php echo esc_html($district); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="permanent_district"><?php _e('Permanent District', 'text-domain'); ?></label>
                    <select name="permanent_district" id="permanent_district">
                        <option value=""><?php _e('All Districts', 'text-domain'); ?></option>
                        <?php foreach ($permanent_districts as $district): ?>
                            <option value="<?php echo esc_attr($district); ?>" <?php selected($user_permanent_district, $district); ?>>
                                <?php echo esc_html($district); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="highest_education"><?php _e('Highest Education', 'text-domain'); ?></label>
                    <select name="highest_education" id="highest_education">
                        <option value=""><?php _e('All Education Levels', 'text-domain'); ?></option>
                        <?php foreach ($education_levels as $level): ?>
                            <option value="<?php echo esc_attr($level); ?>" <?php selected($highest_education, $level); ?>>
                                <?php echo esc_html($level); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="gender"><?php _e('Gender', 'text-domain'); ?></label>
                    <select name="gender" id="gender">
                        <option value=""><?php _e('All Genders', 'text-domain'); ?></option>
                        <?php foreach ($gender_options as $option): ?>
                            <option value="<?php echo esc_attr($option); ?>" <?php selected($gender, $option); ?>>
                                <?php echo esc_html($option); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label><?php _e('Age Range', 'text-domain'); ?></label>
                    <div class="age-range-container">
                        <div class="age-slider">
                            <div class="age-slider-track">
                                <div class="age-slider-range"></div>
                                <div class="age-slider-thumb" data-thumb="min"></div>
                                <div class="age-slider-thumb" data-thumb="max"></div>
                            </div>
                            <div class="age-slider-labels">
                                <span>10</span>
                                <span>20</span>
                                <span>30</span>
                                <span>40</span>
                                <span>50</span>
                                <span>60</span>
                                <span>70</span>
                                <span>80</span>
                            </div>
                        </div>
                        <div class="age-range-display">
                            <div class="age-value-min"><?php echo esc_attr($min_age); ?></div>
                            <div class="age-value-max"><?php echo esc_attr($max_age); ?></div>
                        </div>
                        <input type="hidden" id="min_age" name="min_age" value="<?php echo esc_attr($min_age); ?>">
                        <input type="hidden" id="max_age" name="max_age" value="<?php echo esc_attr($max_age); ?>">
                    </div>
                </div>
                
                <div class="filter-group">
                    <label><?php _e('Work Experience', 'text-domain'); ?></label>
                    <div class="experience-range-container">
                        <div class="experience-slider">
                            <div class="experience-slider-track">
                                <div class="experience-slider-range"></div>
                                <div class="experience-slider-thumb" data-thumb="min"></div>
                                <div class="experience-slider-thumb" data-thumb="max"></div>
                            </div>
                            <div class="experience-slider-labels">
                                <span>0</span>
                                <span>5</span>
                                <span>10</span>
                                <span>15</span>
                                <span>20+</span>
                            </div>
                        </div>
                        <div class="experience-range-display">
                            <div class="experience-value-min"><?php echo esc_attr($min_experience); ?> years</div>
                            <div class="experience-value-max"><?php echo esc_attr($max_experience); ?>+ years</div>
                        </div>
                        <input type="hidden" id="min_experience" name="min_experience" value="<?php echo esc_attr($min_experience); ?>">
                        <input type="hidden" id="max_experience" name="max_experience" value="<?php echo esc_attr($max_experience); ?>">
                    </div>
                </div>
                
                <!-- Application Date Range Filter -->
                <div class="filter-group">
                    <label class="filter-label"><?php _e('Application Date Range', 'text-domain'); ?></label>
                    <div class="date-range-simple">
                        <div class="date-input-row">
                            <label for="application_date_from"><?php _e('From', 'text-domain'); ?></label>
                            <input type="date" id="application_date_from" name="application_date_from" 
                                   value="<?php echo isset($_GET['application_date_from']) ? esc_attr($_GET['application_date_from']) : ''; ?>">
                        </div>
                        <div class="date-input-row">
                            <label for="application_date_to"><?php _e('To', 'text-domain'); ?></label>
                            <input type="date" id="application_date_to" name="application_date_to" 
                                   value="<?php echo isset($_GET['application_date_to']) ? esc_attr($_GET['application_date_to']) : ''; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="filter-group">
                    <label for="skills"><?php _e('Skills', 'text-domain'); ?></label>
                    <div class="skills-filter-container">
                        <select name="skills[]" id="skills" multiple>
                            <option value=""><?php _e('Select Skills', 'text-domain'); ?></option>
                            <?php
                            global $wpdb;
                            $all_skills = array();
                            // Get all skills meta values directly from the database
                            $skills_meta = $wpdb->get_results(
                                "SELECT meta_value FROM {$wpdb->usermeta} WHERE meta_key = 'skills'"
                            );
                            foreach ($skills_meta as $meta) {
                                $user_skills = parse_user_skills($meta->meta_value);
                                
                                // Add to our master list
                                $all_skills = array_merge($all_skills, $user_skills);
                            }
                            // Clean up and deduplicate
                            $all_skills = array_unique($all_skills);
                            $all_skills = array_filter($all_skills); // Remove any empty values
                            sort($all_skills);
                            $selected_skills = isset($_GET['skills']) ? (array)$_GET['skills'] : array();
                            foreach ($all_skills as $skill): ?>
                                <option value="<?php echo esc_attr($skill); ?>"
                                    <?php echo in_array($skill, $selected_skills) ? 'selected' : ''; ?>>
                                    <?php echo esc_html($skill); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="button button-primary btn-apply"><?php _e('Apply Filters', 'text-domain'); ?></button>
                    <a href="<?php echo esc_url($base_url); ?>" class="button btn-reset" style="text-align: center;"><?php _e('Reset Filters', 'text-domain'); ?></a>
                </div>
            </form>
        </div>
        
        <!-- Main content area -->
        <div class="applications-content">
            <!-- Status Filter -->
            <div class="status-filter-container">
                <div class="job-info-section">
                    <div class="bulk-actions" style="display: none;">
                        <select id="bulk-action-select">
                            <option value="">Bulk Actions</option>
                            <option value="shortlist">Shortlist</option>
                            <option value="reject">Reject</option>
                            <option value="schedule">Schedule Interview</option>
                        </select>
                        <button id="do-bulk-action" class="button">Apply</button>
                    </div>
                    <div class="job-status">
                        <span class="status-label">Job Status:</span>
                        <?php
                        $deadline = get_post_meta($job_id, '_job_deadline', true);
                        if($deadline >= date('Y-m-d')){
                            $job_status = 'Active';
                        } else {
                            $job_status = 'Expired';
                        }        
                        ?>
                        <span class="status-value job-<?php echo $job_status; ?>">
                            <?php echo $job_status; ?>
                        </span>
                    </div>
                    
                    <div class="job-actions">
                        <a href="<?php echo esc_url(admin_url('post.php?post='.$job_id.'&action=edit')); ?>" class="button edit-button">
                            <?php _e('Edit', 'text-domain'); ?>
                        </a>
                        <a href="<?php echo get_permalink($job_id); ?>" target="_blank" class="button preview-button">
                            <?php _e('Preview', 'text-domain'); ?>
                        </a>
                    </div>
                    
                    <div class="job-deadline">
                        <span class="deadline-label">Deadline:</span>
                        <span class="deadline-value"><?php echo date('F j, Y', strtotime($deadline)); ?></span>
                    </div>
                    
                    <div class="job-share">
                        <span class="share-label">Share:</span>
                        <?php 
                        $current_url = urlencode(get_permalink($job_id));
                        $title = urlencode(get_the_title($job_id));
                        ?>
                        <div class="share-icons">
                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $current_url; ?>" 
                            class="share-icon facebook" title="Share on Facebook" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <!-- Twitter -->
                            <a href="https://twitter.com/intent/tweet?url=<?php echo $current_url; ?>&text=<?php echo $title; ?>" 
                            class="share-icon twitter" title="Share on Twitter" target="_blank">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <!-- LinkedIn -->
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $current_url; ?>&title=<?php echo $title; ?>" 
                            class="share-icon linkedin" title="Share on LinkedIn" target="_blank">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <!-- Email -->
                            <a href="mailto:?subject=<?php echo $title; ?>&body=Check this out: <?php echo $current_url; ?>" 
                            class="share-icon email" title="Share via Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <!-- Copy Link -->
                            <a href="javascript:void(0);" 
                            class="share-icon copy" 
                            title="Copy Link" 
                            onclick="copyLink('<?php echo get_permalink($job_id); ?>')">
                                <i class="fas fa-link"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <form method="get" action="<?php echo esc_url($base_url); ?>" id="statusFilterForm">
                    <input type="hidden" name="post_type" value="job">
                    <input type="hidden" name="page" value="view_job_applications">
                    <input type="hidden" name="job_id" value="<?php echo esc_attr($job_id); ?>">
                    
                    <!-- Preserve other filter parameters -->
                    <?php if (!empty($user_present_district)): ?>
                        <input type="hidden" name="present_district" value="<?php echo esc_attr($user_present_district); ?>">
                    <?php endif; ?>
                    <?php if (!empty($user_permanent_district)): ?>
                        <input type="hidden" name="permanent_district" value="<?php echo esc_attr($user_permanent_district); ?>">
                    <?php endif; ?>
                    <?php if (!empty($highest_education)): ?>
                        <input type="hidden" name="highest_education" value="<?php echo esc_attr($highest_education); ?>">
                    <?php endif; ?>
                    <?php if (!empty($experience_years)): ?>
                        <input type="hidden" name="experience_years" value="<?php echo esc_attr($experience_years); ?>">
                    <?php endif; ?>
                    <?php if (!empty($min_age)): ?>
                        <input type="hidden" name="min_age" value="<?php echo esc_attr($min_age); ?>">
                    <?php endif; ?>
                    <?php if (!empty($max_age)): ?>
                        <input type="hidden" name="max_age" value="<?php echo esc_attr($max_age); ?>">
                    <?php endif; ?>
                    <?php if (!empty($gender)): ?>
                        <input type="hidden" name="gender" value="<?php echo esc_attr($gender); ?>">
                    <?php endif; ?>
                    <?php if (!empty($min_experience)): ?>
                        <input type="hidden" name="min_experience" value="<?php echo esc_attr($min_experience); ?>">
                    <?php endif; ?>
                    <?php if (!empty($max_experience)): ?>
                        <input type="hidden" name="max_experience" value="<?php echo esc_attr($max_experience); ?>">
                    <?php endif; ?>
                    <?php if (!empty($application_date_from)): ?>
                        <input type="hidden" name="application_date_from" value="<?php echo esc_attr($application_date_from); ?>">
                    <?php endif; ?>
                    <?php if (!empty($application_date_to)): ?>
                        <input type="hidden" name="application_date_to" value="<?php echo esc_attr($application_date_to); ?>">
                    <?php endif; ?>
                    <?php if (!empty($skills)): ?>
                        <?php foreach ($skills as $skill): ?>
                            <input type="hidden" name="skills[]" value="<?php echo esc_attr($skill); ?>">
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <div class="filter-group">
                        <label for="status_filter"><?php _e('Application Status', 'text-domain'); ?></label>
                        <select name="status" id="status_filter">
                            <option value="all" <?php selected($current_status, 'all'); ?>>
                                <?php printf(__('All Applications (%d)', 'text-domain'), $total_applications); ?>
                            </option>
                            <option value="shortlisted" <?php selected($current_status, 'shortlisted'); ?>>
                                <?php printf(__('Shortlisted (%d)', 'text-domain'), $shortlisted_count); ?>
                            </option>
                            <option value="interview_scheduled" <?php selected($current_status, 'interview_scheduled'); ?>>
                                <?php printf(__('Interview Scheduled (%d)', 'text-domain'), $interview_scheduled_count); ?>
                            </option>
                            <option value="rejected" <?php selected($current_status, 'rejected'); ?>>
                                <?php printf(__('Rejected (%d)', 'text-domain'), $rejected_count); ?>
                            </option>
                            <option value="new" <?php selected($current_status, 'new'); ?>>
                                <?php printf(__('No Action (%d)', 'text-domain'), $new_count); ?>
                            </option>
                        </select>
                    </div>
                </form>
            </div>
            
            <!-- Bulk Actions Bar -->
            
            <?php if (empty($results)): ?>
                <p><?php esc_html_e('No applications found with the current filters.', 'text-domain'); ?></p>
            <?php else: ?>
                <!-- CV Preview Modal -->
                <div id="cvModal" class="modal">
                    <div class="modal-content">
                        <span class="close-modal">&times;</span>
                        <h2 id="modalTitle">Applicant CV</h2>
                        <div id="cvContent">
                            <!-- CV content will be loaded here -->
                        </div>
                    </div>
                </div>
                
                <!-- Message Container -->
                <div id="messageContainer"></div>
                
                <div class="container">
                <?php
                foreach ($results as $app) {
                    $resume = maybe_unserialize($app->resume_data);
                    
                    // Format resume data for display
                    if (is_array($resume) || is_object($resume)) {
                        $resume_display = '<div class="experience-item">';
                        foreach ((array)$resume as $key => $value) {
                            $resume_display .= '<strong>' . esc_html($key) . ':</strong> ' . esc_html(print_r($value, true)) . '<br>';
                        }
                        $resume_display .= '</div>';
                    } else {
                        $resume_display = '<div class="experience-item">' . esc_html($app->resume_data) . '</div>';
                    }
                    
                    // Generate initials for avatar placeholder
                    $name_parts = explode(' ', $app->full_name);
                    $initials = '';
                    if (count($name_parts) >= 2) {
                        $initials = substr($name_parts[0], 0, 1) . substr($name_parts[1], 0, 1);
                    } else {
                        $initials = substr($name_parts[0], 0, 2);
                    }
                    $initials = strtoupper($initials);
                    
                    // Format application date
                    $applied_date = date('F j, Y', strtotime($app->applied_at));
                    
                    // Get user ID and experience data
                    $user_id = $app->user_id;
                    $user_info = get_userdata($user_id);
                    $hometown = get_user_meta($user_id, 'placeofbirth', true);
                    $experience_entries = get_user_meta($user_id, 'work_experience', true);
                    if (!is_array($experience_entries)) {
                        $experience_entries = array();
                    }
                    
                    // Get additional user meta for new filters
                    $date_of_birth = get_user_meta($user_id, 'date_of_birth', true);
                    $gender = get_user_meta($user_id, 'gender', true);
                    
                    // Calculate age
                    $age = '';
                    if (!empty($date_of_birth)) {
                        $birth_date = new DateTime($date_of_birth);
                        $today = new DateTime();
                        $age = $birth_date->diff($today)->y;
                    }
                    
                    // Calculate total experience
                    $totalexperiance = 'No experience';
                    $total_months = 0;
                    if (!empty($experience_entries)) {
                        foreach ($experience_entries as $entry) {
                            $start_date = isset($entry['start_date']) ? $entry['start_date'] : '';
                            $end_date = isset($entry['end_date']) ? $entry['end_date'] : '';
                            
                            if (!empty($start_date)) {
                                $start = new DateTime($start_date);
                                if (empty($end_date) || strtolower($end_date) === 'present') {
                                    $end = new DateTime(); // current date for ongoing jobs
                                } else {
                                    $end = new DateTime($end_date);
                                }
                                $interval = $start->diff($end);
                                $months = $interval->y * 12 + $interval->m;
                                $total_months += $months;
                            }
                        }
                        
                        if ($total_months > 0) {
                            $years = floor($total_months / 12);
                            $months = $total_months % 12;
                            
                            $totalexperiance = '';
                            if ($years > 0) {
                                $totalexperiance .= $years . ' year' . ($years > 1 ? 's' : '');
                            }
                            if ($months > 0) {
                                if (!empty($totalexperiance)) {
                                    $totalexperiance .= ', ';
                                }
                                $totalexperiance .= $months . ' month' . ($months > 1 ? 's' : '');
                            }
                        }
                    }
                    
                    // Store total months in a data attribute for filtering
                    $data_experience = $total_months > 0 ? $total_months : 0;
                    
                    // Get application status and check if interview date is passed
                    $status = isset($app->status) ? $app->status : 'new';
                    $interview_passed = !empty($app->interview_date) && strtotime($app->interview_date) < current_time('timestamp');
                    $app_profile_picture = get_user_meta($app->user_id, 'profile_picture', true);
                    if (empty($app_profile_picture)) {
                        $app_profile_picture = get_avatar_url($app->user_id, array('size' => 150));
                    }
                ?>
                <div class="applicant-card" id="applicant-<?php echo esc_attr($app->id); ?>" data-application-id="<?php echo esc_attr($app->id); ?>" data-experience-months="<?php echo esc_attr($data_experience); ?>">
                    <!-- Checkbox for bulk selection -->
                    <div class="applicant-checkbox">
                        <input type="checkbox" class="application-checkbox" value="<?php echo esc_attr($app->id); ?>">
                    </div>
                    
                    <div class="applicant-avatar">
                        <img src="<?php echo esc_url($app_profile_picture); ?>" alt="<?php echo esc_attr($app->full_name); ?>" class="avatar-image">
                    </div>
                    
                    <div class="applicant-info">
                        <a href="#" class="applicant-name" onclick="showCV(<?php echo esc_attr($app->id); ?>)"><?php echo esc_html($app->full_name); ?></a>
                        
                        <div class="applicant-details">
                            <div class="detail-item">
                                <svg class="detail-icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                                <?php echo esc_html($app->email); ?>
                            </div>
                            <div class="detail-item">
                                <svg class="detail-icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                </svg>
                                <?php echo esc_html($app->contact_number); ?>
                            </div>
                            <div class="detail-item">
                                <svg class="detail-icon" viewBox="0 0 16 16" fill="currentColor">
                                    <path d="M4 2a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v1h1a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h1V2zm1 2h6V3H5v1zm5 7V8H6v3h4z"/>
                                </svg>
                                <?php echo esc_html($totalexperiance); ?>
                            </div>
                            <div class="detail-item">
                                <i class="fa-solid fa-house-user"></i>
                                <?php echo esc_html($hometown); ?>
                            </div>
                            <?php if (!empty($age)): ?>
                            <div class="detail-item">
                                <i class="fa-solid fa-cake-candles"></i>
                                <?php echo esc_html($age) ?> years old
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($gender)): ?>
                            <div class="detail-item">
                                <i class="fa-solid fa-venus-mars"></i>
                                <?php echo esc_html($gender); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="application-date">Applied: <?php echo esc_html($applied_date); ?></div>
                        
                        <?php if (!empty($app->interview_date)): ?>
                        <div class="interview-info">
                            <strong>Interview:</strong> 
                            <span id="interview_date"><?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($app->interview_date)); ?></span>
                            <br>
                            <strong>Location:</strong> <span id="location"><?php echo esc_html($app->interview_location); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="education-section">
                        <div class="section-title">Education</div>
                        <?php 
                        $education_entries = get_user_meta($user_id, 'education', true);
                        if (!is_array($education_entries)) {
                            $education_entries = array();
                        } 
                        if(!empty($education_entries)){
                            foreach ($education_entries as $entry) {
                                $degree = isset($entry['degree']) ? esc_html($entry['degree']) : 'Not provided';
                                $institution = isset($entry['institution']) ? esc_html($entry['institution']) : 'Not provided';
                                $description = '';
                                if (!empty($entry['major'])) {
                                    $description = 'Specialized in ' . esc_html($entry['major']) . '. ';
                                }
                                ?>
                                <div class="education-item">
                                    <div class="item-degree"><strong><?php echo $degree; ?></strong></div>
                                    <div class="item-school"><?php echo $institution; ?></div>
                                    <div class="item-duration">
                                        <i class="fas fa-graduation-cap"></i>
                                        <span><?php echo $description; ?></span>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<div class="education-item">No education details provided</div>';
                        }
                        ?>
                    </div>
                    
                    <div class="experience-section">
                        <div class="section-title">Work Experience</div>
                        <?php 
                        if(!empty($experience_entries)){
                            foreach ($experience_entries as $entry) {
                                $job_title = isset($entry['job_title']) ? esc_html($entry['job_title']) : 'Not provided';
                                $company = isset($entry['company']) ? esc_html($entry['company']) : 'Not provided';
                                
                                // Format dates for display
                                $start_date = isset($entry['start_date']) ? $entry['start_date'] : '';
                                $end_date = isset($entry['end_date']) ? $entry['end_date'] : '';
                                $duration = '';
                                if (!empty($start_date)) {
                                    $start = new DateTime($start_date);
                                    
                                    // Format start date
                                    $formatted_start_date = $start->format('M d, Y');
                                    
                                    // Handle end date
                                    if (empty($end_date) || strtolower($end_date) === 'present') {
                                        $end = new DateTime(); // current date for ongoing jobs
                                        $formatted_end_date = 'Present';
                                    } else {
                                        $end = new DateTime($end_date);
                                        $formatted_end_date = $end->format('M d, Y');
                                    }
                                    
                                    // Calculate duration
                                    $interval = $start->diff($end);
                                    $years = $interval->y;
                                    $months = $interval->m;
                                    
                                    // Build duration string
                                    $duration_text = '';
                                    if ($years > 0) {
                                        $duration_text .= $years . ' year' . ($years > 1 ? 's' : '');
                                    }
                                    if ($months > 0) {
                                        if (!empty($duration_text)) {
                                            $duration_text .= ', ';
                                        }
                                        $duration_text .= $months . ' month' . ($months > 1 ? 's' : '');
                                    }
                                    
                                    // Combine formatted dates with duration
                                    $duration = $formatted_start_date . ' - ' . $formatted_end_date;
                                    if (!empty($duration_text)) {
                                        $duration .= ' (' . $duration_text . ')';
                                    }
                                } else {
                                    $duration = 'Duration not available';
                                }
                                ?>
                                <div class="experience-item">
                                    <div class="item-title"><strong><?php echo $company; ?></strong></div>
                                    <div class="item-duration"><?php echo $job_title; ?></div>
                                    <div class="item-duration"><?php echo $duration; ?></div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<div class="experience-item">No work experience details provided</div>';
                        } ?>
                    </div>
                    
                    <div class="applicant-actions">
                        <?php 
                        // Set badge class and text based on status
                        switch ($status) {
                            case 'shortlisted':
                                $badge_class = 'status-shortlisted';
                                $badge_text = 'Shortlisted';
                                break;
                            case 'rejected':
                                $badge_class = 'status-rejected';
                                $badge_text = 'Rejected';
                                break;
                            case 'interview_scheduled':
                                $badge_class = 'status-interview_scheduled';
                                $badge_text = 'Interview Scheduled';
                                break;
                            default:
                                $badge_class = 'status-new';
                                $badge_text = 'New Application';
                        }
                        
                        // Output the dynamic status badge
                        echo '<span class="status-badge ' . esc_attr($badge_class) . '">' . esc_html($badge_text) . '</span>';
                        
                        // Add buttons based on status
                        if ($status === 'pending' || $status === 'new'):
                            echo '<button class="action-btn btn-shortlist" data-applicant-id="' . esc_attr($app->id) . '">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Shortlist
                            </button>
                            <button class="action-btn btn-reject" data-applicant-id="' . esc_attr($app->id) . '">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Reject
                            </button>';
                        elseif ($status === 'shortlisted'):
                            echo '<button class="action-btn btn-schedule" data-applicant-id="' . esc_attr($app->id) . '">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                Schedule Interview
                            </button>
                            <button class="action-btn btn-reject" data-applicant-id="' . esc_attr($app->id) . '">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Reject
                            </button>';
                        elseif ($status === 'rejected'):
                            echo '<button class="action-btn btn-reject" data-applicant-id="' . esc_attr($app->id) . '" disabled style="opacity: 0.5;">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Rejected
                            </button>';
                        elseif ($status === 'interview_scheduled'):
                            if ($interview_passed):
                                echo '<button class="action-btn btn-reschedule" data-applicant-id="' . esc_attr($app->id) . '">
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                    </svg>
                                    Reschedule
                                </button>';
                            else:
                                echo '<button class="action-btn" disabled>
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    Scheduled
                                </button>';
                            endif;
                            echo '<button class="action-btn btn-reject" data-applicant-id="' . esc_attr($app->id) . '">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Reject
                            </button>';
                        endif;
                        
                        echo '<button class="action-btn btn-download" data-applicant-id="' . esc_attr($app->id) . '">
                            <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Download CV
                        </button>';
                        ?>
                    </div>
                </div>
                <?php
                }
                ?>
                </div>
                
                <!-- Pagination -->
                <div class="pagination-container">
                    <?php
                    $pagination_args = array(
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '',
                        'prev_text' => __('&laquo; Previous', 'text-domain'),
                        'next_text' => __('Next &raquo;', 'text-domain'),
                        'total' => $total_pages,
                        'current' => $current_page
                    );
                    
                    echo paginate_links($pagination_args);
                    ?>
                </div>
                
                <div class="applications-count">
                    <?php 
                    $start = ($current_page - 1) * $per_page + 1;
                    $end = min($current_page * $per_page, $total_applications);
                    printf(
                        esc_html__('Showing %d-%d of %d applications', 'text-domain'),
                        $start,
                        $end,
                        $total_applications
                    );
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Age slider functionality
        const ageSlider = document.querySelector('.age-slider');
        if (ageSlider) {
            const minThumb = ageSlider.querySelector('[data-thumb="min"]');
            const maxThumb = ageSlider.querySelector('[data-thumb="max"]');
            const minInput = document.getElementById('min_age');
            const maxInput = document.getElementById('max_age');
            const minDisplay = document.querySelector('.age-value-min');
            const maxDisplay = document.querySelector('.age-value-max');
            
            // Initialize values
            let minVal = parseInt(minInput.value) || 18;
            let maxVal = parseInt(maxInput.value) || 65;
            
            // Update display
            minDisplay.textContent = minVal;
            maxDisplay.textContent = maxVal;
            
            // Add event listeners for thumbs
            minThumb.addEventListener('input', function() {
                minVal = parseInt(this.value);
                if (minVal > maxVal) minVal = maxVal;
                minInput.value = minVal;
                minDisplay.textContent = minVal;
            });
            
            maxThumb.addEventListener('input', function() {
                maxVal = parseInt(this.value);
                if (maxVal < minVal) maxVal = minVal;
                maxInput.value = maxVal;
                maxDisplay.textContent = maxVal;
            });
        }
        
        // Experience slider functionality
        const expSlider = document.querySelector('.experience-slider');
        if (expSlider) {
            const minThumb = expSlider.querySelector('[data-thumb="min"]');
            const maxThumb = expSlider.querySelector('[data-thumb="max"]');
            const minInput = document.getElementById('min_experience');
            const maxInput = document.getElementById('max_experience');
            const minDisplay = document.querySelector('.experience-value-min');
            const maxDisplay = document.querySelector('.experience-value-max');
            
            // Initialize values
            let minVal = parseInt(minInput.value) || 0;
            let maxVal = parseInt(maxInput.value) || 20;
            
            // Update display
            minDisplay.textContent = minVal + ' years';
            maxDisplay.textContent = maxVal + '+ years';
            
            // Add event listeners for thumbs
            minThumb.addEventListener('input', function() {
                minVal = parseInt(this.value);
                if (minVal > maxVal) minVal = maxVal;
                minInput.value = minVal;
                minDisplay.textContent = minVal + ' years';
            });
            
            maxThumb.addEventListener('input', function() {
                maxVal = parseInt(this.value);
                if (maxVal < minVal) maxVal = minVal;
                maxInput.value = maxVal;
                maxDisplay.textContent = maxVal + '+ years';
            });
        }
        
        // Auto-submit status filter when changed
        $('#status_filter').on('change', function() {
            $('#statusFilterForm').submit();
        });
    });
    </script>
    
    <?php
    echo '</div>';
}
// Helper functions to get filter options
function get_district_options() {
    global $wpdb;
    
    $usermeta_table = $wpdb->prefix . 'usermeta';
    $applications_table = $wpdb->prefix . 'job_applications';
    
    // Get unique districts from usermeta table by joining with job_applications
    $districts = $wpdb->get_col("
        SELECT DISTINCT um.meta_value
        FROM $usermeta_table um
        JOIN $applications_table ja ON um.user_id = ja.user_id
        WHERE um.meta_key IN ('presentcity', 'placeofbirth')
        AND um.meta_value != ''
        ORDER BY um.meta_value
    ");
    
    // Extract district names from addresses
    $district_names = array();
    foreach ($districts as $address) {
        // Split address by comma to extract district (assuming format: "District, Division, Country")
        $address_parts = explode(',', $address);
        if (!empty($address_parts)) {
            // Trim whitespace and add to array
            $district = trim($address_parts[0]);
            if (!empty($district)) {
                $district_names[] = $district;
            }
        }
    }
    
    // Remove duplicates and sort alphabetically
    $district_names = array_unique($district_names);
    sort($district_names);
    
    return $district_names;
}


// Function to get all unique present cities from usermeta
function get_present_city_options() {
    global $wpdb;
    
    $usermeta_table = $wpdb->prefix . 'usermeta';
    $applications_table = $wpdb->prefix . 'job_applications';
    
    // Get unique present cities from usermeta table for job applicants
    $cities = $wpdb->get_col("
        SELECT DISTINCT um.meta_value
        FROM $usermeta_table um
        JOIN $applications_table ja ON um.user_id = ja.user_id
        WHERE um.meta_key = 'presentcity'
        AND um.meta_value != ''
        ORDER BY um.meta_value
    ");
    
    // Remove any empty values and sort
    $cities = array_filter($cities);
    sort($cities);
    
    return $cities;
}

// Function to get all unique birth places from usermeta
function get_birth_place_options() {
    global $wpdb;
    
    $usermeta_table = $wpdb->prefix . 'usermeta';
    $applications_table = $wpdb->prefix . 'job_applications';
    
    // Get unique birth places from usermeta table for job applicants
    $places = $wpdb->get_col("
        SELECT DISTINCT um.meta_value
        FROM $usermeta_table um
        JOIN $applications_table ja ON um.user_id = ja.user_id
        WHERE um.meta_key = 'placeofbirth'
        AND um.meta_value != ''
        ORDER BY um.meta_value
    ");
    
    // Remove any empty values and sort
    $places = array_filter($places);
    sort($places);
    
    return $places;
}


function get_education_options() {
    global $wpdb;
    
    // Get unique education levels from job applicants
    $table = $wpdb->prefix . 'job_applications';
    $education_levels = array();
    
    // Get all users who have applied for jobs
    $user_ids = $wpdb->get_col("SELECT DISTINCT user_id FROM $table WHERE user_id > 0");
    
    if (!empty($user_ids)) {
        // Convert array to comma-separated string for the query
        $user_ids_str = implode(',', array_map('intval', $user_ids));
        
        // Get education meta for these users
        $education_meta = $wpdb->get_results(
            "SELECT user_id, meta_value FROM {$wpdb->usermeta} 
             WHERE meta_key = 'education' AND user_id IN ($user_ids_str)"
        );
        
        foreach ($education_meta as $meta) {
            $education_data = maybe_unserialize($meta->meta_value);
            if (is_array($education_data)) {
                foreach ($education_data as $edu) {
                    // Check if 'level' key exists and is not empty
                    if (isset($edu['level']) && !empty($edu['level'])) {
                        $education_levels[] = $edu['level'];
                    }
                }
            }
        }
    }
    
    // Remove duplicates and sort alphabetically
    $education_levels = array_unique($education_levels);
    sort($education_levels);
    
    return $education_levels;
}

// functions.php or plugin file
add_action('template_redirect', 'mark_all_notifications_read');
function mark_all_notifications_read() {
    if (isset($_POST['mark_all_read'])) {
        global $wpdb;
        $current_user_id = get_current_user_id();
        $wpdb->update(
            "{$wpdb->prefix}user_notifications",
            [ 'status' => 'read' ],
            [ 'user_id' => $current_user_id ]
        );
        wp_safe_redirect($_SERVER['REQUEST_URI']);
        exit;
    }
}



// Add settings submenu
function add_job_applications_email_settings_menu() {
    add_submenu_page(
        'edit.php?post_type=job',          // Parent slug
        'Email Settings',      // Page title
        'Email Templates',                  // Menu title
        'manage_options',            // Capability
        'job_applications_email_settings', // Menu slug
        'job_applications_email_settings_page' // Callback function
    );
}
add_action('admin_menu', 'add_job_applications_email_settings_menu');



function job_applications_register_settings() {
    // Register each option with its own group
    register_setting('interview_scheduled_group', 'interview_scheduled_email');
    register_setting('interview_rescheduled_group', 'interview_rescheduled_email');
    register_setting('application_shortlisted_group', 'application_shortlisted_email');
    register_setting('application_rejected_group', 'application_rejected_email');
}
add_action('admin_init', 'job_applications_register_settings');


// Settings page callback with navigation tabs
function job_applications_email_settings_page() {
    // Get the current tab
    $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'interview-scheduled';
    ?>
    <div class="wrap">
        <h1>Job Application Settings</h1>
        
        <?php
        // Navigation tabs
        $tabs = array(
            'interview-scheduled'   => 'Interview Scheduled',
            'interview-rescheduled' => 'Interview Rescheduled',
            'application-shortlisted' => 'Application Shortlisted',
            'application-rejected'  => 'Application Rejected'
        );
        
        echo '<nav class="nav-tab-wrapper">';
        foreach ($tabs as $tab_id => $tab_name) {
            $url = add_query_arg(
                array(
                    'page' => 'job_applications_settings',
                    'tab'  => $tab_id
                ),
                admin_url('admin.php')
            );
            
            $active_class = ($current_tab === $tab_id) ? 'nav-tab-active' : '';
            echo '<a href="' . esc_url($url) . '" class="nav-tab ' . esc_attr($active_class) . '">' . esc_html($tab_name) . '</a>';
        }
        echo '</nav>';
        
        // Display the appropriate tab content
        switch ($current_tab) {
            case 'interview-scheduled':
                ?>
                <div class="tab-content">
                    <form method="post" action="options.php">
                        <?php settings_fields('interview_scheduled_group'); ?>
                        <h2>Interview Scheduled Email</h2>
                        <p>Customize the email sent to applicants when an interview is scheduled.</p>
                        <table class="form-table">
                            <tr>
                                <th scope="row">Email Body</th>
                                <td>
                                    <textarea name="interview_scheduled_email" rows="10" class="large-text"><?php 
                                        echo esc_textarea( get_option('interview_scheduled_email', get_default_interview_scheduled_email()) ); 
                                    ?></textarea>
                                    <p class="description">Available placeholders: {name}, {job_title}, {interview_date}, {location}, {notes}</p>
                                </td>
                            </tr>
                        </table>
                        <?php submit_button(); ?>
                    </form>
                </div>
                <?php
                break;
                
            case 'interview-rescheduled':
                ?>
                <div class="tab-content">
                    <form method="post" action="options.php">
                        <?php settings_fields('interview_rescheduled_group'); ?>
                        <h2>Interview Rescheduled Email</h2>
                        <p>Customize the email sent to applicants when an interview is rescheduled.</p>
                        <table class="form-table">
                            <tr>
                                <th scope="row">Email Body</th>
                                <td>
                                    <textarea name="interview_rescheduled_email" rows="10" class="large-text"><?php 
                                        echo esc_textarea( get_option('interview_rescheduled_email', get_default_interview_rescheduled_email()) ); 
                                    ?></textarea>
                                    <p class="description">Available placeholders: {name}, {job_title}, {interview_date}, {location}, {notes}</p>
                                </td>
                            </tr>
                        </table>
                        <?php submit_button(); ?>
                    </form>
                </div>
                <?php
                break;
                
            case 'application-shortlisted':
                ?>
                <div class="tab-content">
                    <form method="post" action="options.php">
                        <?php settings_fields('application_shortlisted_group'); ?>
                        <h2>Application Shortlisted Email</h2>
                        <p>Customize the email sent to applicants when their application is shortlisted.</p>
                        <table class="form-table">
                            <tr>
                                <th scope="row">Email Body</th>
                                <td>
                                    <textarea name="application_shortlisted_email" rows="10" class="large-text"><?php 
                                        echo esc_textarea( get_option('application_shortlisted_email', get_default_application_shortlisted_email()) ); 
                                    ?></textarea>
                                    <p class="description">Available placeholders: {name}, {job_title}</p>
                                </td>
                            </tr>
                        </table>
                        <?php submit_button(); ?>
                    </form>
                </div>
                <?php
                break;
                
            case 'application-rejected':
                ?>
                <div class="tab-content">
                    <form method="post" action="options.php">
                        <?php settings_fields('application_rejected_group'); ?>
                        <h2>Application Rejected Email</h2>
                        <p>Customize the email sent to applicants when their application is rejected.</p>
                        <table class="form-table">
                            <tr>
                                <th scope="row">Email Body</th>
                                <td>
                                    <textarea name="application_rejected_email" rows="10" class="large-text"><?php 
                                        echo esc_textarea( get_option('application_rejected_email', get_default_application_rejected_email()) ); 
                                    ?></textarea>
                                    <p class="description">Available placeholders: {name}, {job_title}</p>
                                </td>
                            </tr>
                        </table>
                        <?php submit_button(); ?>
                    </form>
                </div>
                <?php
                break;
        }
        ?>
    </div>
    
    <style>
    .nav-tab-wrapper {
        margin: 1em 0;
    }
    .tab-content {
        background: #fff;
        padding: 20px;
        border: 1px solid #ccd0d4;
        border-top: none;
        margin-top: -1px;
    }
    </style>
    <?php
}







// // Send application shortlisted email
// function send_application_shortlisted_email($application_id) {
//     global $wpdb;
//     $table = $wpdb->prefix . 'job_applications';
    
//     // Get application and user details
//     $application = $wpdb->get_row($wpdb->prepare(
//         "SELECT a.*, u.user_email, u.display_name 
//         FROM $table a 
//         JOIN {$wpdb->users} u ON a.user_id = u.ID 
//         WHERE a.id = %d",
//         $application_id
//     ));
    
//     if (!$application) {
//         return false;
//     }
    
//     $job_title = get_the_title($application->job_id);
    
//     $subject = sprintf('Application Shortlisted for %s', $job_title);
    
//     // Get custom email template
//     $email_template = get_option('application_shortlisted_email', get_default_application_shortlisted_email());
    
//     // Replace placeholders
//     $message = str_replace(
//         array('{name}', '{job_title}'),
//         array($application->display_name, $job_title),
//         $email_template
//     );
    
//     error_log('Sending shortlisted email to: ' . $application->user_email);
//     return wp_mail($application->user_email, $subject, $message);
// }



// Helper function to calculate total experience in months
function calculate_total_experience_months($work_experience) {
    if (!is_array($work_experience)) {
        $work_experience = maybe_unserialize($work_experience);
    }
    
    if (!is_array($work_experience)) {
        return 0;
    }
    
    $total_months = 0;
    foreach ($work_experience as $entry) {
        if (!isset($entry['start_date']) || empty($entry['start_date'])) {
            continue;
        }
        
        try {
            $start_date = new DateTime($entry['start_date']);
            
            if (isset($entry['end_date']) && !empty($entry['end_date']) && strtolower($entry['end_date']) !== 'present') {
                $end_date = new DateTime($entry['end_date']);
            } else {
                $end_date = new DateTime(); // current date for ongoing jobs
            }
            
            $interval = $start_date->diff($end_date);
            $months = $interval->y * 12 + $interval->m;
            $total_months += $months;
        } catch (Exception $e) {
            // Skip invalid dates
            continue;
        }
    }
    
    return $total_months;
}


// Helper function to calculate age
function calculate_age($date_of_birth) {
    if (empty($date_of_birth)) {
        return 0;
    }
    
    try {
        // Try to create DateTime object from various possible formats
        $birth_date = new DateTime($date_of_birth);
        $today = new DateTime();
        return $birth_date->diff($today)->y;
    } catch (Exception $e) {
        // If DateTime fails, try to parse common formats manually
        $formats = array(
            'Y-m-d', 'm/d/Y', 'd/m/Y', 'Y/m/d', 'd-m-Y', 
            'F j, Y', 'M j, Y', 'j F, Y', 'j M, Y'
        );
        
        foreach ($formats as $format) {
            try {
                $birth_date = DateTime::createFromFormat($format, $date_of_birth);
                if ($birth_date) {
                    $today = new DateTime();
                    return $birth_date->diff($today)->y;
                }
            } catch (Exception $e) {
                continue;
            }
        }
        
        return 0; // Return 0 if all parsing attempts fail
    }
}

// Create a custom table to track search terms
function create_search_tracking_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'job_search_terms';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        term varchar(255) NOT NULL,
        count int(11) NOT NULL,
        last_searched datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY term (term)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_setup_theme', 'create_search_tracking_table');

// Track search terms
function track_job_search_term($query) {
    // Only track searches for job post type
    if ($query->is_main_query() && isset($_GET['keywords']) && !empty($_GET['keywords'])) {
        $search_term = sanitize_text_field($_GET['keywords']);
        
        if (!empty($search_term)) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'job_search_terms';
            
            // Check if term already exists
            $term_exists = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE term = %s", 
                $search_term
            ));
            
            if ($term_exists) {
                // Update existing term
                $wpdb->query($wpdb->prepare(
                    "UPDATE $table_name SET count = count + 1, last_searched = NOW() WHERE term = %s", 
                    $search_term
                ));
            } else {
                // Insert new term
                $wpdb->insert(
                    $table_name,
                    array(
                        'term' => $search_term,
                        'count' => 1,
                        'last_searched' => current_time('mysql')
                    )
                );
            }
        }
    }
}
add_action('pre_get_posts', 'track_job_search_term');

// Get most searched terms
function get_most_searched_terms($limit = 5) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'job_search_terms';
    
    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT term, count FROM $table_name ORDER BY count DESC LIMIT %d", 
        $limit
    ));
    
    return $results;
}

function get_job_listing_page_link() {
    $args = array(
        'post_type'      => 'page',
        'title'          => 'Job Listing',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $query->the_post();
        $link = get_permalink(get_the_ID());
        wp_reset_postdata();
        return $link;
    }

    return ''; // return empty if not found
}


// Get job listing page URL - Updated version
function get_job_listing_page_url() {
    // Try to get the page by its template name
    $pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-job-listing.php' // Updated to match your template filename
    ));
    
    if ($pages) {
        return get_permalink($pages[0]->ID);
    }
    
    // Try alternative template name
    $pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'job-listing.php'
    ));
    
    if ($pages) {
        return get_permalink($pages[0]->ID);
    }
    
    // Try to get page by title if template name doesn't work
    $page = get_job_listing_page_link();
    if ($page) {
        return get_permalink($page->ID);
    }
    
    // Try to get page by slug
    $page = get_page_by_path('job-listing');
    if ($page) {
        return get_permalink($page->ID);
    }
    
    // Fallback to current URL if we're on the job listing page
    global $wp;
    $current_url = home_url(add_query_arg(array(), $wp->request));
    
    // Check if current page is using the job listing template
    if (is_page_template('template-job-listing.php') || is_page_template('job-listing.php')) {
        return $current_url;
    }
    
    // Last resort: return home URL
    return home_url('/');
}

// Display popular search badges - Updated version
function display_popular_search_badges() {
    $popular_terms = get_most_searched_terms(5);
    
    // Get the base job listing page URL without any query parameters
    $base_url = get_job_listing_page_url();
    
    if (empty($popular_terms)) {
        // If no tracked searches, display default terms
        $default_terms = array(
            'Software Engineer',
            'Marketing',
            'UX Designer',
            'Remote',
            'Full-time'
        );
        
        foreach ($default_terms as $term) {
            // Use 'keywords' parameter instead of 's' to match your form
            $search_url = add_query_arg('keywords', $term, $base_url);
            echo '<a href="' . esc_url($search_url) . '" class="badge bg-warning text-dark">' . esc_html($term) . '</a>';
        }
    } else {
        foreach ($popular_terms as $term) {
            // Use 'keywords' parameter instead of 's' to match your form
            $search_url = add_query_arg('keywords', $term->term, $base_url);
            echo '<a href="' . esc_url($search_url) . '" class="badge bg-warning text-dark">' . esc_html($term->term) . '</a>';
        }
    }
}

// Fix pagination for job listings
function job_listing_pagination_with_query($query = null, $range = 2) {
    global $wp_query;
    
    // Use provided query or fall back to global query
    $query = $query ? $query : $wp_query;
    
    // Don't print empty markup if there's only one page
    if ($query->max_num_pages <= 1) {
        return;
    }
    
    $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
    $max   = intval($query->max_num_pages);
    
    // Get current URL without pagination parameters
    $current_url = get_job_listing_page_url();
    
    // Build query string from current search parameters
    $query_params = array();
    
    // Add keywords if present
    if (isset($_GET['keywords']) && !empty($_GET['keywords'])) {
        $query_params['keywords'] = sanitize_text_field($_GET['keywords']);
    }
    
    // Add location if present
    if (isset($_GET['location']) && !empty($_GET['location'])) {
        $query_params['location'] = sanitize_text_field($_GET['location']);
    }
    
    // Add job_type if present
    if (isset($_GET['job_type']) && !empty($_GET['job_type'])) {
        $query_params['job_type'] = sanitize_text_field($_GET['job_type']);
    }
    
    // Add experience if present
    if (isset($_GET['experience']) && !empty($_GET['experience'])) {
        $query_params['experience'] = sanitize_text_field($_GET['experience']);
    }
    
    // Add industry if present
    if (isset($_GET['industry']) && !empty($_GET['industry'])) {
        $query_params['industry'] = sanitize_text_field($_GET['industry']);
    }
    
    // Start building pagination HTML
    $pagination = '<nav aria-label="Job listings pagination">';
    $pagination .= '<ul class="pagination justify-content-center mt-4">';
    
    // Previous button
    $prev_disabled = ($paged <= 1) ? 'disabled' : '';
    $prev_page = $paged - 1;
    $prev_query_params = $query_params;
    if ($prev_page > 1) {
        $prev_query_params['paged'] = $prev_page;
    }
    $prev_link = add_query_arg($prev_query_params, $current_url);
    $pagination .= '<li class="page-item ' . $prev_disabled . '">';
    $pagination .= '<a class="page-link" href="' . esc_url($prev_link) . '" tabindex="-1" aria-disabled="' . ($paged <= 1 ? 'true' : 'false') . '">Previous</a>';
    $pagination .= '</li>';
    
    // Page numbers
    for ($i = 1; $i <= $max; $i++) {
        // Show limited page numbers with range
        if ($i == 1 || $i == $max || ($i >= $paged - $range && $i <= $paged + $range)) {
            $active = ($i == $paged) ? 'active' : '';
            $page_query_params = $query_params;
            if ($i > 1) {
                $page_query_params['paged'] = $i;
            }
            $link = add_query_arg($page_query_params, $current_url);
            $pagination .= '<li class="page-item ' . $active . '">';
            $pagination .= '<a class="page-link" href="' . esc_url($link) . '">' . $i . '</a>';
            $pagination .= '</li>';
        } elseif ($i == $paged - $range - 1 || $i == $paged + $range + 1) {
            // Add ellipsis for skipped pages
            $pagination .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }
    
    // Next button
    $next_disabled = ($paged >= $max) ? 'disabled' : '';
    $next_page = $paged + 1;
    $next_query_params = $query_params;
    $next_query_params['paged'] = $next_page;
    $next_link = add_query_arg($next_query_params, $current_url);
    $pagination .= '<li class="page-item ' . $next_disabled . '">';
    $pagination .= '<a class="page-link" href="' . esc_url($next_link) . '">Next</a>';
    $pagination .= '</li>';
    
    $pagination .= '</ul>';
    $pagination .= '</nav>';
    
    echo $pagination;
}


/**
 * Redirect after login
 */
function custom_login_redirect($redirect_to, $request, $user) {
    // Check if user has a specific role
    if (isset($user->roles) && is_array($user->roles)) {
        // Redirect to user profile page for all roles
        return home_url('/user-profile');
    }
    
    // Default redirect
    return $redirect_to;
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);


/**
 * Redirect default WordPress login to custom login page
 */
function redirect_to_custom_login() {
    // Check if we're on the default login page
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        return; // Don't redirect logout requests
    }
    
    // Check if we're on the default login page
    if ($GLOBALS['pagenow'] === 'wp-login.php' && !is_user_logged_in()) {
        // Get the URL of your custom login page
        $custom_login_url = get_permalink(get_page_by_path('login'));
        
        // If we can't find the page by path, try to find it by template
        if (!$custom_login_url) {
            $login_page = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'template-login.php'
            ));
            
            if ($login_page) {
                $custom_login_url = get_permalink($login_page[0]->ID);
            } else {
                $custom_login_url = home_url('/login'); // Fallback
            }
        }
        
        // Preserve any redirect_to parameter
        $redirect_to = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : '';
        
        // Build the redirect URL
        $redirect_url = $redirect_to ? add_query_arg('redirect_to', $redirect_to, $custom_login_url) : $custom_login_url;
        
        wp_redirect($redirect_url);
        exit;
    }
}
add_action('init', 'redirect_to_custom_login');


// /**
//  * Custom logout redirect
//  */
// function custom_logout_redirect() {
//     $custom_login_url = get_permalink(get_page_by_path('login'));
    
//     // If we can't find the page by path, try to find it by template
//     if (!$custom_login_url) {
//         $login_page = get_pages(array(
//             'meta_key' => '_wp_page_template',
//             'meta_value' => 'template-login.php'
//         ));
        
//         if ($login_page) {
//             $custom_login_url = get_permalink($login_page[0]->ID);
//         } else {
//             $custom_login_url = home_url('/login'); // Fallback
//         }
//     }
    
//     wp_redirect($custom_login_url . '?logged_out=true');
//     exit;
// }
// add_action('wp_logout', 'custom_logout_redirect');


// /**
//  * Replace default login URL with custom login URL
//  */
// function custom_login_url($login_url, $redirect) {
//     $custom_login_url = get_permalink(get_page_by_path('login'));
    
//     // If we can't find the page by path, try to find it by template
//     if (!$custom_login_url) {
//         $login_page = get_pages(array(
//             'meta_key' => '_wp_page_template',
//             'meta_value' => 'template-login.php'
//         ));
        
//         if ($login_page) {
//             $custom_login_url = get_permalink($login_page[0]->ID);
//         } else {
//             $custom_login_url = home_url('/login'); // Fallback
//         }
//     }
    
//     // Add redirect parameter if needed
//     if (!empty($redirect)) {
//         $custom_login_url = add_query_arg('redirect_to', urlencode($redirect), $custom_login_url);
//     }
    
//     return $custom_login_url;
// }
// add_filter('login_url', 'custom_login_url', 10, 2);

// /**
//  * Replace logout URL
//  */
// function custom_logout_url($logout_url, $redirect) {
//     $custom_login_url = get_permalink(get_page_by_path('login'));
    
//     // If we can't find the page by path, try to find it by template
//     if (!$custom_login_url) {
//         $login_page = get_pages(array(
//             'meta_key' => '_wp_page_template',
//             'meta_value' => 'template-login.php'
//         ));
        
//         if ($login_page) {
//             $custom_login_url = get_permalink($login_page[0]->ID);
//         } else {
//             $custom_login_url = home_url('/login'); // Fallback
//         }
//     }
    
//     // Build logout URL
//     $args = array('action' => 'logout');
//     if (!empty($redirect)) {
//         $args['redirect_to'] = urlencode($redirect);
//     } else {
//         $args['redirect_to'] = urlencode($custom_login_url);
//     }
    
//     $logout_url = add_query_arg($args, wp_nonce_url($custom_login_url, 'log-out'));
    
//     return $logout_url;
// }
// add_filter('logout_url', 'custom_logout_url', 10, 2);

// /**
//  * Replace register URL
//  */
// function custom_register_url($register_url) {
//     // If you have a custom registration page, you can redirect there
//     // Otherwise, use the default WordPress registration
//     return wp_registration_url();
// }
// add_filter('register_url', 'custom_register_url');

// /**
//  * Replace lost password URL
//  */
// function custom_lostpassword_url($lostpassword_url) {
//     // You can create a custom lost password page if you want
//     // For now, we'll use the default WordPress lost password page
//     return wp_lostpassword_url();
// }
// add_filter('lostpassword_url', 'custom_lostpassword_url');


// /**
//  * Block direct access to wp-login.php
//  */
// function block_wp_login() {
//     // Check if we're on the default login page
//     if ($GLOBALS['pagenow'] === 'wp-login.php' && !is_user_logged_in()) {
//         // Get the URL of your custom login page
//         $custom_login_url = get_permalink(get_page_by_path('login'));
        
//         // If we can't find the page by path, try to find it by template
//         if (!$custom_login_url) {
//             $login_page = get_pages(array(
//                 'meta_key' => '_wp_page_template',
//                 'meta_value' => 'template-login.php'
//             ));
            
//             if ($login_page) {
//                 $custom_login_url = get_permalink($login_page[0]->ID);
//             } else {
//                 $custom_login_url = home_url('/login'); // Fallback
//             }
//         }
        
//         // Set status header
//         status_header(403);
        
//         // Display an error message
//         wp_die(
//             __('For security reasons, direct access to the login page is disabled. Please use the <a href="' . esc_url($custom_login_url) . '">custom login page</a> to log in.', 'job-listing'),
//             __('Access Denied', 'job-listing'),
//             403
//         );
//     }
// }
// // Use a higher priority to make sure this runs after other functions
// add_action('init', 'block_wp_login', 999);



// Redirect wp-login.php to custom login page
function jl_redirect_to_custom_login() {
    $login_page  = home_url('/login/');
    $page_viewed = basename($_SERVER['REQUEST_URI']);

    // Redirect if user tries to access wp-login.php directly
    if ($page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
        wp_redirect($login_page);
        exit;
    }
}
add_action('init', 'jl_redirect_to_custom_login');


function jl_login_url($login_url, $redirect, $force_reauth) {
    $login_page = home_url('/login/');
    if (!empty($redirect)) {
        $login_page = add_query_arg('redirect_to', urlencode($redirect), $login_page);
    }
    return $login_page;
}
add_filter('login_url', 'jl_login_url', 10, 3);


/**
 * Register navigation menus
 */
function job_listing_theme_register_menus() {
    register_nav_menus(
        array(
            'user-profile-menu' => __('User Profile Menu', 'job-listing')
        )
    );
}
add_action('init', 'job_listing_theme_register_menus');


/**
 * Custom Walker for User Profile Dropdown Menu
 */
class User_Profile_Menu_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = array()) {
        // Don't output the <ul> wrapper for submenus since we don't have any
    }
    
    function end_lvl(&$output, $depth = 0, $args = array()) {
        // Don't close the <ul> wrapper for submenus
    }
    
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        // Get the original classes
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        
        // Remove the dropdown-item class if it exists
        $classes = array_diff($classes, array('dropdown-item'));
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= '<li' . $id . $class_names .'>';
        
        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';
        
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
        
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        
        // Add dropdown-item class to the link
        $link_class = 'dropdown-item';
        
        // Add text-danger class to logout link
        if (strpos($item->url, 'wp-login.php?action=logout') !== false) {
            $link_class .= ' text-danger';
        }
        
        $item_output = $args->before;
        $item_output .= '<a'. $attributes .' class="' . esc_attr($link_class) . '">';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    function end_el(&$output, $item, $depth = 0, $args = array()) {
        $output .= "</li>\n";
    }
    
    // Add a custom method to add the logout link
    function add_logout_link(&$output, $args) {
        $output .= '<li class="menu-item menu-item-type-custom menu-item-object-custom">';
        $output .= '<a class="dropdown-item text-danger" href="' . esc_url(wp_logout_url(home_url())) . '">' . __('Logout', 'job-listing') . '</a>';
        $output .= '</li>';
    }
}


/**
 * Generate user profile dropdown menu with automatic logout link
 */
function generate_user_profile_menu_with_logout() {
    $output = '';
    
    // Check if the menu exists in the location
    if (has_nav_menu('user-profile-menu')) {
        $locations = get_nav_menu_locations();
        $menu = wp_get_nav_menu_object($locations['user-profile-menu']);
        $menu_items = wp_get_nav_menu_items($menu->term_id);
        
        if ($menu_items) {
            // Create a walker instance
            $walker = new User_Profile_Menu_Walker();
            
            // Process menu items
            foreach ($menu_items as $item) {
                // Skip if this is a logout link (we'll add it manually)
                if (strpos($item->url, 'wp-login.php?action=logout') !== false) {
                    continue;
                }
                
                // Get the original classes
                $classes = empty($item->classes) ? array() : (array) $item->classes;
                
                // Remove the dropdown-item class if it exists
                $classes = array_diff($classes, array('dropdown-item'));
                
                $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, array(), 0));
                $class_attr = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
                
                $id = 'menu-item-' . $item->ID;
                $id_attr = ' id="' . esc_attr($id) . '"';
                
                // Add dropdown-item class to the link
                $link_class = 'dropdown-item';
                
                $output .= '<li' . $id_attr . $class_attr . '>';
                $output .= '<a class="' . esc_attr($link_class) . '" href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
                $output .= '</li>';
            }
        }
    } else {
        // Fallback to default menu items (without logout)
        $output .= '<li id="menu-item-profile" class="menu-item menu-item-type-post_type menu-item-object-page dropdown-item">';
        $output .= '<a class="dropdown-item" href="' . esc_url(get_permalink(get_page_by_path('user-profile'))) . '">' . __('My Profile', 'job-listing') . '</a>';
        $output .= '</li>';
        
        $output .= '<li id="menu-item-jobs" class="menu-item menu-item-type-post_type menu-item-object-page dropdown-item">';
        $output .= '<a class="dropdown-item" href="' . esc_url(get_permalink(get_page_by_path('applied-jobs'))) . '">' . __('Applied Jobs', 'job-listing') . '</a>';
        $output .= '</li>';
        
        $output .= '<li id="menu-item-notifications" class="menu-item menu-item-type-post_type menu-item-object-page dropdown-item">';
        $output .= '<a class="dropdown-item" href="' . esc_url(get_permalink(get_page_by_path('notification'))) . '">' . __('Notifications', 'job-listing') . '</a>';
        $output .= '</li>';
        
        $output .= '<li id="menu-item-settings" class="menu-item menu-item-type-post_type menu-item-object-page dropdown-item">';
        $output .= '<a class="dropdown-item" href="' . esc_url(get_permalink(get_page_by_path('settings'))) . '">' . __('Settings', 'job-listing') . '</a>';
        $output .= '</li>';
    }
    
    // Add the logout link at the end
    $output .= '<li id="menu-item-logout" class="menu-item menu-item-type-custom menu-item-object-custom">';
    $output .= '<a class="dropdown-item text-danger" href="' . esc_url(wp_logout_url(home_url())) . '">' . __('Logout', 'job-listing') . '</a>';
    $output .= '</li>';
    
    return $output;
}


/**
 * Fallback function for User Profile Menu
 */
function user_profile_menu_fallback() {
    // Get the current user
    $current_user = wp_get_current_user();
    
    // Create default menu items
    $menu_items = array(
        array(
            'title' => __('My Profile', 'job-listing'),
            'url'   => esc_url(get_permalink(get_page_by_path('user-profile')))
        ),
        array(
            'title' => __('Applied Jobs', 'job-listing'),
            'url'   => esc_url(get_permalink(get_page_by_path('applied-jobs')))
        ),
        array(
            'title' => __('Notifications', 'job-listing'),
            'url'   => esc_url(get_permalink(get_page_by_path('notification')))
        ),
        array(
            'title' => __('Settings', 'job-listing'),
            'url'   => esc_url(get_permalink(get_page_by_path('settings')))
        ),
        array(
            'title' => __('Logout', 'job-listing'),
            'url'   => wp_logout_url(home_url()),
            'class' => 'text-danger'
        )
    );
    
    // Output menu items
    foreach ($menu_items as $item) {
        $class = 'dropdown-item';
        if (isset($item['class']) && $item['class']) {
            $class .= ' ' . $item['class'];
        }
        echo '<li><a class="' . esc_attr($class) . '" href="' . esc_url($item['url']) . '">' . esc_html($item['title']) . '</a></li>';
    }
}



/**
 * Custom title function for the job portal site
 *
 * @param string $title Default title text
 * @param string $sep Optional separator
 * @return string The formatted title
 */
function job_portal_title($title, $sep = '|') {
    global $paged, $page;
    
    // Get the site name
    $site_name = get_bloginfo('name');
    
    // Get the site description
    $site_description = get_bloginfo('description', 'display');
    
    // Check if we're on the home page
    if (is_home() || is_front_page()) {
        // Home page title
        if ($site_description) {
            $title = "$site_name $sep $site_description";
        } else {
            $title = "$site_name $sep " . __('Find Your Dream Job', 'job-listing');
        }
    } 
    // Check if we're on a job listing page
    elseif (is_post_type_archive('job')) {
        $title = __('Job Listings', 'job-listing') . " $sep $site_name";
    }
    // Check if we're viewing a single job
    elseif (is_singular('job')) {
        $job_title = get_the_title();
        $job_location = get_post_meta(get_the_ID(), '_job_location', true);
        
        if ($job_location) {
            $title = "$job_title in $job_location $sep $site_name";
        } else {
            $title = "$job_title $sep $site_name";
        }
    }
    // Check if we're on a job category archive
    elseif (is_tax('job_category')) {
        $term = get_queried_object();
        $title = sprintf(__('%s Jobs', 'job-listing'), $term->name) . " $sep $site_name";
    }
    // Check if we're on a job type archive
    elseif (is_tax('job_type')) {
        $term = get_queried_object();
        $title = sprintf(__('%s Jobs', 'job-listing'), $term->name) . " $sep $site_name";
    }
    // Check if we're on a search results page
    elseif (is_search()) {
        $search_query = get_search_query();
        $title = sprintf(__('Search Results for: %s', 'job-listing'), $search_query) . " $sep $site_name";
    }
    // Check if we're on the author archive
    elseif (is_author()) {
        $author = get_queried_object();
        $title = sprintf(__('Jobs Posted by %s', 'job-listing'), $author->display_name) . " $sep $site_name";
    }
    // Check if we're on a 404 page
    elseif (is_404()) {
        $title = __('Page Not Found', 'job-listing') . " $sep $site_name";
    }
    // Check if we're on the login page
    elseif (is_page_template('template-login.php')) {
        $title = __('Login', 'job-listing') . " $sep $site_name";
    }
    // Check if we're on the signup page
    elseif (is_page_template('template-signup.php')) {
        $title = __('Create Account', 'job-listing') . " $sep $site_name";
    }
    // Check if we're on the forgot password page
    elseif (is_page_template('template-forgot-password.php')) {
        $title = __('Forgot Password', 'job-listing') . " $sep $site_name";
    }
    // Check if we're on the password reset page
    elseif (is_page_template('template-password-reset.php')) {
        $title = __('Reset Password', 'job-listing') . " $sep $site_name";
    }
    // Check if we're on the user profile page
    elseif (is_page_template('template-user-profile.php')) {
        $title = __('My Profile', 'job-listing') . " $sep $site_name";
    }
    // Check if we're on the settings page
    elseif (is_page_template('template-settings.php')) {
        $title = __('Account Settings', 'job-listing') . " $sep $site_name";
    }
    // Check if we're on the applied jobs page
    elseif (is_page_template('template-applied-jobs.php')) {
        $title = __('Applied Jobs', 'job-listing') . " $sep $site_name";
    }
    // Check if we're on the notifications page
    elseif (is_page_template('template-notifications.php')) {
        $title = __('Notifications', 'job-listing') . " $sep $site_name";
    }
    // Check if we're on a blog archive or single post
    elseif (is_home() || is_single() || is_category() || is_tag() || is_date()) {
        // For blog-related pages, use the default WordPress title
        return $title;
    }
    // For any other page
    else {
        $page_title = get_the_title();
        $title = "$page_title $sep $site_name";
    }
    
    // Add pagination if needed
    if ($paged >= 2 || $page >= 2) {
        $title .= " $sep " . sprintf(__('Page %s', 'job-listing'), max($paged, $page));
    }
    
    return $title;
}
add_filter('wp_title', 'job_portal_title', 10, 2);

/**
 * Add theme support for title tag
 */
function job_portal_title_tag_support() {
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'job_portal_title_tag_support');

/**
 * Remove default WordPress title and use our custom function
 */
function remove_default_title() {
    // Remove the default title
    remove_action('wp_head', '_wp_render_title_tag', 1);
    
    // Add our custom title
    add_action('wp_head', 'job_portal_render_title', 1);
}
add_action('init', 'remove_default_title');

/**
 * Render the custom title tag
 */
function job_portal_render_title() {
    $title = job_portal_title('');
    echo '<title>' . esc_html($title) . '</title>' . "\n";
}


/**
 * Generate meta description for the job portal
 */
function job_portal_meta_description() {
    $description = '';
    
    // Home page
    if (is_home() || is_front_page()) {
        $description = get_bloginfo('description');
        if (empty($description)) {
            $description = __('Find your dream job from thousands of job listings. Search by location, industry, and job type. Apply today and take the next step in your career.', 'job-listing');
        }
    }
    // Job listings page
    elseif (is_page('job-listing')) {
        $description = __('Browse all available job listings. Find the perfect job that matches your skills and experience.', 'job-listing');
    }
    // Single job
    elseif (is_singular('job')) {
        $job_title = get_the_title();
        $job_location = get_post_meta(get_the_ID(), '_job_location', true);
        $job_excerpt = get_the_excerpt();
        
        if (!empty($job_excerpt)) {
            $description = $job_excerpt;
        } else {
            $description = sprintf(__('Apply for %s position in %s. View job details and submit your application today.', 'job-listing'), $job_title, $job_location);
        }
    }
    // Job category
    elseif (is_tax('job_category')) {
        $term = get_queried_object();
        $description = sprintf(__('Browse all %s jobs. Find opportunities in this field and apply for positions that match your skills.', 'job-listing'), $term->name);
    }
    // Job type
    elseif (is_tax('job_type')) {
        $term = get_queried_object();
        $description = sprintf(__('Browse all %s positions. Find the perfect job with your preferred work arrangement.', 'job-listing'), $term->name);
    }
    // Search results
    elseif (is_search()) {
        $search_query = get_search_query();
        $description = sprintf(__('Search results for "%s". Find jobs that match your search criteria.', 'job-listing'), $search_query);
    }
    // Login page
    elseif (is_page_template('template-login.php')) {
        $description = __('Log in to your account to access your profile, saved jobs, and application history.', 'job-listing');
    }
    // Signup page
    elseif (is_page_template('template-signup.php')) {
        $description = __('Create an account to apply for jobs, save listings, and receive job notifications.', 'job-listing');
    }
    // User profile page
    elseif (is_page_template('template-user-profile.php')) {
        $description = __('Manage your profile, update your resume, and track your job applications.', 'job-listing');
    }
    // Settings page
    elseif (is_page_template('template-settings.php')) {
        $description = __('Manage your account settings, notification preferences, and security options.', 'job-listing');
    }
    // Applied jobs page
    elseif (is_page_template('template-applied-jobs.php')) {
        $description = __('View and track the status of all your job applications in one place.', 'job-listing');
    }
    // Notifications page
    elseif (is_page_template('template-notifications.php')) {
        $description = __('Manage your job alerts and application notifications.', 'job-listing');
    }
    // Default description
    else {
        $description = get_bloginfo('description');
    }
    
    // Output the meta description
    if (!empty($description)) {
        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    }
}
add_action('wp_head', 'job_portal_meta_description', 2);

/**
 * Add Open Graph meta tags for social sharing
 */
function job_portal_og_tags() {
    // Only output on pages we want to share
    if (is_home() || is_front_page() || is_singular('job') || is_page()) {
        $title = job_portal_title('');
        $url = get_permalink();
        $image = '';
        
        // Get featured image for posts and pages
        if (is_singular() && has_post_thumbnail()) {
            $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
        }
        
        // Default image if no featured image is set
        if (empty($image)) {
            $image = get_template_directory_uri() . '/images/default-og-image.jpg';
        }
        
        // Get description
        $description = '';
        if (is_singular('job')) {
            $excerpt = get_the_excerpt();
            $description = !empty($excerpt) ? $excerpt : get_bloginfo('description');
        } else {
            $description = get_bloginfo('description');
        }
        
        // Output Open Graph tags
        echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
        echo '<meta property="og:type" content="' . (is_singular('job') ? 'article' : 'website') . '">' . "\n";
        echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        
        // Twitter Card tags
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
    }
}
add_action('wp_head', 'job_portal_og_tags', 3);


/**
 * Add JobPosting schema markup for single job pages
 */
function job_portal_job_schema() {
    // Only output on single job pages
    if (!is_singular('job')) {
        return;
    }
    
    $job_id = get_the_ID();
    $job_title = get_the_title();
    $job_description = get_the_content();
    $job_excerpt = get_the_excerpt();
    $job_location = get_post_meta($job_id, '_job_location', true);
    $job_type = get_post_meta($job_id, '_job_type', true);
    $job_salary = get_post_meta($job_id, '_job_salary', true);
    $job_deadline = get_post_meta($job_id, '_job_deadline', true);
    $company_name = get_post_meta($job_id, '_company_name', true);
    $company_url = get_post_meta($job_id, '_company_url', true);
    
    // Get job types
    $employment_type = array();
    if (!empty($job_type)) {
        if (is_array($job_type)) {
            foreach ($job_type as $type) {
                $employment_type[] = ucfirst($type);
            }
        } else {
            $employment_type[] = ucfirst($job_type);
        }
    } else {
        $employment_type[] = 'FULL_TIME';
    }
    
    // Format date
    $date_posted = get_the_date('Y-m-d');
    $valid_through = !empty($job_deadline) ? date('Y-m-d', strtotime($job_deadline)) : '';
    
    // Build schema data
    $schema = array(
        '@context' => 'https://schema.org/',
        '@type' => 'JobPosting',
        'title' => $job_title,
        'description' => !empty($job_excerpt) ? $job_excerpt : $job_description,
        'datePosted' => $date_posted,
        'validThrough' => $valid_through,
        'employmentType' => $employment_type,
        'hiringOrganization' => array(
            '@type' => 'Organization',
            'name' => !empty($company_name) ? $company_name : get_bloginfo('name'),
            'sameAs' => !empty($company_url) ? $company_url : get_home_url()
        ),
        'jobLocation' => array(
            '@type' => 'Place',
            'address' => array(
                '@type' => 'PostalAddress',
                'addressLocality' => $job_location,
                'addressCountry' => 'BD' // Change to your country code
            )
        )
    );
    
    // Add salary if available
    if (!empty($job_salary)) {
        $schema['baseSalary'] = array(
            '@type' => 'MonetaryAmount',
            'currency' => 'BDT', // Change to your currency code
            'value' => array(
                '@type' => 'QuantitativeValue',
                'value' => $job_salary,
                'unitText' => 'MONTH' // Change as needed
            )
        );
    }
    
    // Output schema
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'job_portal_job_schema', 4);



/**
 * Generate breadcrumbs for the job portal
 */
function job_portal_breadcrumbs() {
    // Don't show breadcrumbs on the home page
    if (is_front_page()) {
        return;
    }
    
    $breadcrumbs = array();
    
    // Add home page
    $breadcrumbs[] = array(
        'title' => __('Home', 'job-listing'),
        'link' => home_url('/')
    );
    
    // Get the job listings page URL
    $job_listings_page = get_page_by_path('job-listing');
    $job_listings_url = $job_listings_page ? get_permalink($job_listings_page->ID) : get_post_type_archive_link('job');
    
    // Job listings page
    if (is_page('job-listing')) {
        $breadcrumbs[] = array(
            'title' => __('Job Listings', 'job-listing'),
            'link' => ''
        );
    }
    // Single job
    elseif (is_singular('job')) {
        $breadcrumbs[] = array(
            'title' => __('Job Listings', 'job-listing'),
            'link' => $job_listings_url
        );
        $breadcrumbs[] = array(
            'title' => get_the_title(),
            'link' => ''
        );
    }
    // Job category
    elseif (is_tax('job_category')) {
        $breadcrumbs[] = array(
            'title' => __('Job Listings', 'job-listing'),
            'link' => $job_listings_url
        );
        $term = get_queried_object();
        $breadcrumbs[] = array(
            'title' => $term->name,
            'link' => ''
        );
    }
    // Job type
    elseif (is_tax('job_type')) {
        $breadcrumbs[] = array(
            'title' => __('Job Listings', 'job-listing'),
            'link' => $job_listings_url
        );
        $term = get_queried_object();
        $breadcrumbs[] = array(
            'title' => $term->name,
            'link' => ''
        );
    }
    // Search results
    elseif (is_search()) {
        $breadcrumbs[] = array(
            'title' => __('Search Results', 'job-listing'),
            'link' => ''
        );
    }
    // Author archive
    elseif (is_author()) {
        $breadcrumbs[] = array(
            'title' => __('Jobs Posted by', 'job-listing') . ' ' . get_queried_object()->display_name,
            'link' => ''
        );
    }
    // User profile page
    elseif (is_page_template('template-user-profile.php')) {
        $breadcrumbs[] = array(
            'title' => __('My Profile', 'job-listing'),
            'link' => ''
        );
    }
    // Settings page
    elseif (is_page_template('template-settings.php')) {
        $breadcrumbs[] = array(
            'title' => __('Account Settings', 'job-listing'),
            'link' => ''
        );
    }
    // Applied jobs page
    elseif (is_page_template('template-applied-jobs.php')) {
        $breadcrumbs[] = array(
            'title' => __('Applied Jobs', 'job-listing'),
            'link' => ''
        );
    }
    // Notifications page
    elseif (is_page_template('template-notifications.php')) {
        $breadcrumbs[] = array(
            'title' => __('Notifications', 'job-listing'),
            'link' => ''
        );
    }
    // Login page
    elseif (is_page_template('template-login.php')) {
        $breadcrumbs[] = array(
            'title' => __('Login', 'job-listing'),
            'link' => ''
        );
    }
    // Signup page
    elseif (is_page_template('template-signup.php')) {
        $breadcrumbs[] = array(
            'title' => __('Create Account', 'job-listing'),
            'link' => ''
        );
    }
    // Forgot password page
    elseif (is_page_template('template-forgot-password.php')) {
        $breadcrumbs[] = array(
            'title' => __('Forgot Password', 'job-listing'),
            'link' => ''
        );
    }
    // Password reset page
    elseif (is_page_template('template-password-reset.php')) {
        $breadcrumbs[] = array(
            'title' => __('Reset Password', 'job-listing'),
            'link' => ''
        );
    }
    // Other pages
    elseif (is_page()) {
        $ancestors = get_post_ancestors(get_the_ID());
        
        if ($ancestors) {
            $ancestors = array_reverse($ancestors);
            
            foreach ($ancestors as $ancestor) {
                $breadcrumbs[] = array(
                    'title' => get_the_title($ancestor),
                    'link' => get_permalink($ancestor)
                );
            }
        }
        
        $breadcrumbs[] = array(
            'title' => get_the_title(),
            'link' => ''
        );
    }
    // Blog archive
    elseif (is_home() && !is_front_page()) {
        $blog_page = get_option('page_for_posts');
        $breadcrumbs[] = array(
            'title' => get_the_title($blog_page),
            'link' => ''
        );
    }
    // Single post
    elseif (is_single() && !is_singular('job')) {
        $categories = get_the_category();
        
        if ($categories) {
            $category = $categories[0];
            $breadcrumbs[] = array(
                'title' => $category->name,
                'link' => get_category_link($category->term_id)
            );
        }
        
        $breadcrumbs[] = array(
            'title' => get_the_title(),
            'link' => ''
        );
    }
    // Category archive
    elseif (is_category()) {
        $breadcrumbs[] = array(
            'title' => single_cat_title('', false),
            'link' => ''
        );
    }
    // Tag archive
    elseif (is_tag()) {
        $breadcrumbs[] = array(
            'title' => single_tag_title('', false),
            'link' => ''
        );
    }
    // Date archive
    elseif (is_day()) {
        $breadcrumbs[] = array(
            'title' => get_the_date('Y'),
            'link' => get_year_link(get_the_date('Y'))
        );
        $breadcrumbs[] = array(
            'title' => get_the_date('F'),
            'link' => get_month_link(get_the_date('Y'), get_the_date('m'))
        );
        $breadcrumbs[] = array(
            'title' => get_the_date('d'),
            'link' => ''
        );
    } elseif (is_month()) {
        $breadcrumbs[] = array(
            'title' => get_the_date('Y'),
            'link' => get_year_link(get_the_date('Y'))
        );
        $breadcrumbs[] = array(
            'title' => get_the_date('F'),
            'link' => ''
        );
    } elseif (is_year()) {
        $breadcrumbs[] = array(
            'title' => get_the_date('Y'),
            'link' => ''
        );
    }
    // 404 page
    elseif (is_404()) {
        $breadcrumbs[] = array(
            'title' => __('Page Not Found', 'job-listing'),
            'link' => ''
        );
    }
    
    // Output breadcrumbs
    echo '<nav aria-label="breadcrumb" class="breadcrumb-container mb-4">';
    echo '<ol class="breadcrumb">';
    
    $count = count($breadcrumbs);
    foreach ($breadcrumbs as $index => $breadcrumb) {
        $active = ($index === $count - 1) ? ' active' : '';
        $aria_current = ($index === $count - 1) ? ' aria-current="page"' : '';
        
        echo '<li class="breadcrumb-item' . $active . '"' . $aria_current . '>';
        
        if (!empty($breadcrumb['link'])) {
            echo '<a href="' . esc_url($breadcrumb['link']) . '">' . esc_html($breadcrumb['title']) . '</a>';
        } else {
            echo esc_html($breadcrumb['title']);
        }
        
        echo '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}

// 🔒 Disable all default WP new user notifications
function disable_default_new_user_emails() {
    // Remove the default notifications early enough
    remove_action('register_new_user', 'wp_send_new_user_notifications');
    remove_action('edit_user_created_user', 'wp_send_new_user_notifications', 10, 2);
    
    // Also disable the admin notification email
    //add_filter('wp_send_new_user_notification_to_admin', '__return_false');
    add_filter('wp_send_new_user_notification_to_user', '__return_false');
}
add_action('plugins_loaded', 'disable_default_new_user_emails');
add_action('init', 'disable_default_new_user_emails');

// Send ONLY our custom welcome email
function send_custom_welcome_email( $user_id ) {
    $user = get_userdata( $user_id );
    if ( ! $user ) {
        return;
    }
    
    $site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

    $message  = "Hi " . $user->user_login . ",\n\n";
    $message .= "Welcome to " . $site_name . "! 🎉\n\n";
    $message .= "You can log in to your account here:\n";
    $message .= home_url( '/login/' ) . "\n\n";
    $message .= "Thanks for joining us!\n\n";
    $message .= "– The " . $site_name . " Team";

    wp_mail(
        $user->user_email,
        'Welcome to ' . $site_name,
        $message,
        array( 'Content-Type: text/plain; charset=UTF-8' )
    );
}
add_action( 'user_register', 'send_custom_welcome_email' );


// Set default email notifications when user registers
function set_default_user_meta($user_id) {
    if (empty(get_user_meta($user_id, 'email_notifications', true))) {
        update_user_meta($user_id, 'email_notifications', '1');
    }
}
add_action('user_register', 'set_default_user_meta');


// Add settings submenu
function add_job_applications_settings_menu() {
    add_submenu_page(
        'edit.php?post_type=job',          // Parent slug
        'Jobs Settings',      // Page title
        'Settings',                  // Menu title
        'manage_options',            // Capability
        'job_applications_settings', // Menu slug
        'job_applications_settings_page' // Callback function
    );
}
add_action('admin_menu', 'add_job_applications_settings_menu');





//=======================

function job_applications_settings_page() {
    // Check if user has permission
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Process form submission
    if (isset($_POST['submit'])) {
        // Verify nonce
        if (!isset($_POST['job_applications_settings_nonce']) || !wp_verify_nonce($_POST['job_applications_settings_nonce'], 'job_applications_settings_update')) {
            wp_die('Security check failed');
        }
        
        // Save the notification method
        $notification_method = sanitize_text_field($_POST['job_applications_notification_method']);
        update_option('job_applications_notification_method', $notification_method);
        
        // Save SMS settings
        $sms_api_key = sanitize_text_field($_POST['job_applications_sms_api_key']);
        $sms_api_secret = sanitize_text_field($_POST['job_applications_sms_api_secret']);
        $sms_from_number = sanitize_text_field($_POST['job_applications_sms_from_number']);
        
        update_option('job_applications_sms_api_key', $sms_api_key);
        update_option('job_applications_sms_api_secret', $sms_api_secret);
        update_option('job_applications_sms_from_number', $sms_from_number);
        
        // Save SMS templates
        update_option('job_applications_sms_shortlist_template', sanitize_textarea_field($_POST['job_applications_sms_shortlist_template']));
        update_option('job_applications_sms_interview_template', sanitize_textarea_field($_POST['job_applications_sms_interview_template']));
        update_option('job_applications_sms_reschedule_template', sanitize_textarea_field($_POST['job_applications_sms_reschedule_template']));
        
        // Display success message
        echo '<div class="notice notice-success is-dismissible"><p>Settings saved successfully.</p></div>';
    }
    
    // Get current saved values
    $notification_method = get_option('job_applications_notification_method', 'email');
    $sms_api_key = get_option('job_applications_sms_api_key', '');
    $sms_api_secret = get_option('job_applications_sms_api_secret', '');
    $sms_from_number = get_option('job_applications_sms_from_number', '');
    $shortlist_template = get_option('job_applications_sms_shortlist_template', 'Hello {candidate_name}, your application for {job_title} has been shortlisted. We will contact you soon for further steps.');
    $interview_template = get_option('job_applications_sms_interview_template', 'Hello {candidate_name}, you have been selected for an interview for {job_title}. Your interview is scheduled on {interview_date} at {interview_time}. Please reply CONFIRM to confirm your attendance.');
    $reschedule_template = get_option('job_applications_sms_reschedule_template', 'Hello {candidate_name}, your interview for {job_title} has been rescheduled to {new_interview_date} at {new_interview_time}. Please reply CONFIRM if you can attend.');
    ?>
    <div class="wrap">
        <h1>Job Application Settings</h1>
        
        <form method="post" action="">
            <?php wp_nonce_field('job_applications_settings_update', 'job_applications_settings_nonce'); ?>
            
            <h2 class="title">Notification Method</h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Notification Method</th>
                    <td>
                        <select name="job_applications_notification_method" id="job_applications_notification_method">
                            <option value="email" <?php selected($notification_method, 'email'); ?>>Email</option>
                            <option value="sms" <?php selected($notification_method, 'sms'); ?>>SMS</option>
                            <option value="both" <?php selected($notification_method, 'both'); ?>>Both Email and SMS</option>
                        </select>
                        <p class="description">Choose how you want to notify candidates about their application status.</p>
                    </td>
                </tr>
            </table>
            
            <h2 class="title">SMS Configuration</h2>
            <p class="description">Configure SMS settings. We recommend using Twilio or another SMS service provider.</p>
            
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">SMS API Key</th>
                    <td>
                        <input type="text" name="job_applications_sms_api_key" value="<?php echo esc_attr($sms_api_key); ?>" class="regular-text" />
                        <p class="description">Enter your SMS service provider API key.</p>
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">SMS API Secret</th>
                    <td>
                        <input type="password" name="job_applications_sms_api_secret" value="<?php echo esc_attr($sms_api_secret); ?>" class="regular-text" />
                        <p class="description">Enter your SMS service provider API secret.</p>
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">Sender Number</th>
                    <td>
                        <input type="text" name="job_applications_sms_from_number" value="<?php echo esc_attr($sms_from_number); ?>" class="regular-text" />
                        <p class="description">Enter the phone number that will send the SMS messages.</p>
                    </td>
                </tr>
            </table>
            
            <h2 class="title">SMS Templates</h2>
            <p class="description">Customize SMS templates for different events. Available placeholders: {candidate_name}, {job_title}, {company_name}, {interview_date}, {interview_time}, {new_interview_date}, {new_interview_time}</p>
            
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Shortlist Template</th>
                    <td>
                        <textarea name="job_applications_sms_shortlist_template" rows="3" class="large-text"><?php echo esc_textarea($shortlist_template); ?></textarea>
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">Interview Schedule Template</th>
                    <td>
                        <textarea name="job_applications_sms_interview_template" rows="3" class="large-text"><?php echo esc_textarea($interview_template); ?></textarea>
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">Interview Reschedule Template</th>
                    <td>
                        <textarea name="job_applications_sms_reschedule_template" rows="3" class="large-text"><?php echo esc_textarea($reschedule_template); ?></textarea>
                    </td>
                </tr>
            </table>
            
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}

// Register the setting
function job_applications_register_sms_settings() {
    register_setting('job_applications_settings_group', 'job_applications_notification_method');
    register_setting('job_applications_settings_group', 'job_applications_sms_api_key');
    register_setting('job_applications_settings_group', 'job_applications_sms_api_secret');
    register_setting('job_applications_settings_group', 'job_applications_sms_from_number');
    register_setting('job_applications_settings_group', 'job_applications_sms_shortlist_template');
    register_setting('job_applications_settings_group', 'job_applications_sms_interview_template');
    register_setting('job_applications_settings_group', 'job_applications_sms_reschedule_template');
}
add_action('admin_init', 'job_applications_register_sms_settings');

