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


function job_listing_pagination_with_query($custom_query = null) {
    global $wp_query, $wp_rewrite;
    
    // Use custom query if provided, otherwise use main query
    $query = $custom_query ? $custom_query : $wp_query;
    
    // Don't show pagination if there's only 1 page
    if ($query->max_num_pages <= 1) {
        return;
    }
    
    // Get the current URL without parameters
    $current_url = home_url($_SERVER['REQUEST_URI']);
    
    // Remove existing page parameter if it exists
    $base = preg_replace('~/page/\d+~', '', $current_url);
    $base = trailingslashit($base);
    
    // Get pagination base - FIXED THE UNDEFINED VARIABLE ISSUE HERE
    $pagination_base = $wp_rewrite->pagination_base ? $wp_rewrite->pagination_base : 'page';
    
    // Set up pagination args
    $big = 999999999; // need an unlikely integer
    $args = array(
        'base'    => str_replace($big, '%#%', esc_url(add_query_arg('paged', $big, $base))),
        'format'  => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total'   => $query->max_num_pages,
        'type'    => 'array',
        'prev_text' => __('Previous', 'job-listing'),
        'next_text' => __('Next', 'job-listing'),
    );
    
    // Use pretty URLs if permalinks are enabled
    if ($wp_rewrite->using_permalinks()) {
        $args['base'] = user_trailingslashit(trailingslashit($base) . $pagination_base . '/%#%', 'paged');
        $args['format'] = $pagination_base . '/%#%';
    }
    
    $pages = paginate_links($args);
    
    if (is_array($pages)) {
        $pagination = '<nav aria-label="Job listings pagination"><ul class="pagination justify-content-center mt-4">';
        
        foreach ($pages as $page) {
            $li_class = 'page-item';
            $link_class = 'page-link';
            
            if (strpos($page, 'current') !== false) {
                $li_class .= ' active';
                $page = str_replace(array('page-numbers', 'current'), $link_class, $page);
            } 
            elseif (strpos($page, 'prev') !== false) {
                if (strpos($page, 'disabled') !== false) {
                    $li_class .= ' disabled';
                }
                $page = str_replace(array('prev', 'page-numbers'), $link_class, $page);
            }
            elseif (strpos($page, 'next') !== false) {
                $page = str_replace(array('next', 'page-numbers'), $link_class, $page);
            }
            else {
                $page = str_replace('page-numbers', $link_class, $page);
            }
            
            $pagination .= '<li class="' . esc_attr($li_class) . '">' . $page . '</li>';
        }
        
        $pagination .= '</ul></nav>';
        
        echo $pagination;
    }
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

        echo '<tr>
                <td>' . esc_html($job->ID) . '</td>
                <td>' . esc_html($job->post_title) . '</td>
                <td>' . esc_html($count) . '</td>
                <td><a class="button button-primary" href="' . esc_url($view_link) . '">' . esc_html__('View Applications', 'text-domain') . '</a></td>
              </tr>';
    }

    echo '</tbody></table></div>';
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
    $per_page = 20;
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $per_page;
    
    // Get filter values
    $user_present_district = isset($_GET['present_district']) ? sanitize_text_field($_GET['present_district']) : '';
    $user_permanent_district = isset($_GET['permanent_district']) ? sanitize_text_field($_GET['permanent_district']) : '';
    $highest_education = isset($_GET['highest_education']) ? sanitize_text_field($_GET['highest_education']) : '';
    $experience_years = isset($_GET['experience_years']) ? intval($_GET['experience_years']) : 0;
    $current_status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : 'all';
    
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
    
    // Add filters to the query
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
    
    // Add status filter
    if ($current_status !== 'all') {
        $where .= " AND ja.status = %s";
        $prepare_values[] = $current_status;
    }
    
    // Combine all joins
    $join_clause = implode(' ', $joins);
    
    // Get total applications count for pagination
    $count_query = "SELECT COUNT(DISTINCT ja.id) FROM {$applications_table} ja {$join_clause} {$where}";
    $total_applications = $wpdb->get_var($wpdb->prepare($count_query, $prepare_values));
    $total_pages = ceil($total_applications / $per_page);
    
    // Get applications for current page
    $query = "SELECT DISTINCT ja.* FROM {$applications_table} ja {$join_clause} {$where} ORDER BY ja.applied_at DESC LIMIT %d OFFSET %d";
    $prepare_values[] = $per_page;
    $prepare_values[] = $offset;
    $results = $wpdb->get_results($wpdb->prepare($query, $prepare_values));
    
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
                    <label for="experience_years"><?php _e('Work Experience', 'text-domain'); ?></label>
                    
                    <div class="experience-slider-container">
                        <input type="range" 
                            id="experience_slider" 
                            class="experience-slider" 
                            min="0" 
                            max="20" 
                            step="1" 
                            value="<?php echo esc_attr($experience_years); ?>">
                        
                        <div class="slider-labels">
                            <span>0</span>
                            <span>5</span>
                            <span>10</span>
                            <span>15</span>
                            <span>20+</span>
                        </div>
                        
                        <div class="slider-value-display">
                            <span id="experience_display"><?php echo esc_html($experience_years); ?> years</span>
                        </div>
                    </div>
                    
                    <!-- Hidden input to store the actual value for form submission -->
                    <input type="hidden" 
                        id="experience_years" 
                        name="experience_years" 
                        value="<?php echo esc_attr($experience_years); ?>">
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="button button-primary"><?php _e('Apply Filters', 'text-domain'); ?></button>
                    <a href="<?php echo esc_url($base_url); ?>" class="button" style="text-align: center;"><?php _e('Reset Filters', 'text-domain'); ?></a>
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
                ?>
                <div class="applicant-card" id="applicant-<?php echo esc_attr($app->id); ?>" data-application-id="<?php echo esc_attr($app->id); ?>" data-experience-months="<?php echo esc_attr($data_experience); ?>">
                    <!-- Checkbox for bulk selection -->
                    <div class="applicant-checkbox">
                        <input type="checkbox" class="application-checkbox" value="<?php echo esc_attr($app->id); ?>">
                    </div>
                    
                    <div class="applicant-avatar">
                        <img src="<?php echo esc_url(get_avatar_url($app->user_id)); ?>" alt="<?php echo esc_attr($app->full_name); ?>" class="avatar-image">
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
                        'current' => $current_page,
                        'add_args' => array(
                            'present_district' => $user_present_district,
                            'permanent_district' => $user_permanent_district,
                            'highest_education' => $highest_education,
                            'experience_years' => $experience_years,
                            'status' => $current_status
                        )
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
function add_job_applications_settings_menu() {
    add_submenu_page(
        'edit.php?post_type=job',          // Parent slug
        'Application Settings',      // Page title
        'Settings',                  // Menu title
        'manage_options',            // Capability
        'job_applications_settings', // Menu slug
        'job_applications_settings_page' // Callback function
    );
}
add_action('admin_menu', 'add_job_applications_settings_menu');

// Register settings
function job_applications_register_settings() {
    register_setting('job_applications_email_settings', 'interview_scheduled_email');
    register_setting('job_applications_email_settings', 'interview_rescheduled_email');
    register_setting('job_applications_email_settings', 'application_shortlisted_email');
    register_setting('job_applications_email_settings', 'application_rejected_email');
}
add_action('admin_init', 'job_applications_register_settings');

// Settings page callback with navigation tabs
function job_applications_settings_page() {
    // Get the current tab
    $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'interview-scheduled';
    
    ?>
    <div class="wrap">
        <h1>Job Application Settings</h1>
        
        <?php
        // Navigation tabs
        $tabs = array(
            'interview-scheduled' => 'Interview Scheduled',
            'interview-rescheduled' => 'Interview Rescheduled',
            'application-shortlisted' => 'Application Shortlisted',
            'application-rejected' => 'Application Rejected'
        );
        
        echo '<nav class="nav-tab-wrapper">';
        foreach ($tabs as $tab_id => $tab_name) {
            $url = add_query_arg(
                array(
                    'page' => 'job_applications_settings',
                    'tab' => $tab_id
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
                        <?php settings_fields('job_applications_email_settings'); ?>
                        
                        <h2>Interview Scheduled Email</h2>
                        <p>Customize the email sent to applicants when an interview is scheduled.</p>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">Email Body</th>
                                <td>
                                    <textarea name="interview_scheduled_email" rows="10" class="large-text"><?php echo esc_textarea(get_option('interview_scheduled_email', get_default_interview_scheduled_email())); ?></textarea>
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
                        <?php settings_fields('job_applications_email_settings'); ?>
                        
                        <h2>Interview Rescheduled Email</h2>
                        <p>Customize the email sent to applicants when an interview is rescheduled.</p>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">Email Body</th>
                                <td>
                                    <textarea name="interview_rescheduled_email" rows="10" class="large-text"><?php echo esc_textarea(get_option('interview_rescheduled_email', get_default_interview_rescheduled_email())); ?></textarea>
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
                        <?php settings_fields('job_applications_email_settings'); ?>
                        
                        <h2>Application Shortlisted Email</h2>
                        <p>Customize the email sent to applicants when their application is shortlisted.</p>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">Email Body</th>
                                <td>
                                    <textarea name="application_shortlisted_email" rows="10" class="large-text"><?php echo esc_textarea(get_option('application_shortlisted_email', get_default_application_shortlisted_email())); ?></textarea>
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
                        <?php settings_fields('job_applications_email_settings'); ?>
                        
                        <h2>Application Rejected Email</h2>
                        <p>Customize the email sent to applicants when their application is rejected.</p>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">Email Body</th>
                                <td>
                                    <textarea name="application_rejected_email" rows="10" class="large-text"><?php echo esc_textarea(get_option('application_rejected_email', get_default_application_rejected_email())); ?></textarea>
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


// Default email templates
function get_default_interview_scheduled_email() {
    return "Dear {name},

Your interview for the position of {job_title} has been scheduled on {interview_date}.

Location: {location}

{notes}

Please be prepared and on time.

Best regards,
" . get_bloginfo('name');
}

function get_default_interview_rescheduled_email() {
    return "Dear {name},

Your interview for the position of {job_title} has been rescheduled to {interview_date}.

Location: {location}

{notes}

Please be prepared and on time.

Best regards,
" . get_bloginfo('name');
}

function get_default_application_shortlisted_email() {
    return "Dear {name},

Your application for the position of {job_title} has been shortlisted.

We will contact you soon with further details about the next steps.

Best regards,
" . get_bloginfo('name');
}

function get_default_application_rejected_email() {
    return "Dear {name},

We regret to inform you that your application for the position of {job_title} was not successful.

We appreciate your interest in our organization and wish you the best in your job search.

Best regards,
" . get_bloginfo('name');
}

// Send interview scheduled email
function send_interview_scheduled_email($application_id, $notes = '') {
    global $wpdb;
    $table = $wpdb->prefix . 'job_applications';
    
    // Get application and user details
    $application = $wpdb->get_row($wpdb->prepare(
        "SELECT a.*, u.user_email, u.display_name 
        FROM $table a 
        JOIN {$wpdb->users} u ON a.user_id = u.ID 
        WHERE a.id = %d",
        $application_id
    ));
    
    if (!$application) {
        return false;
    }
    
    $job_title = get_the_title($application->job_id);
    $formatted_date = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($application->interview_date));
    
    $subject = sprintf('Interview Scheduled for %s', $job_title);
    
    // Get custom email template
    $email_template = get_option('interview_scheduled_email', get_default_interview_scheduled_email());
    
    // Replace placeholders
    $message = str_replace(
        array('{name}', '{job_title}', '{interview_date}', '{location}', '{notes}'),
        array($application->display_name, $job_title, $formatted_date, $application->interview_location, $notes),
        $email_template
    );
    
    error_log('Sending interview scheduled email to: ' . $application->user_email);
    return wp_mail($application->user_email, $subject, $message);
}

// Send interview rescheduled email
function send_interview_rescheduled_email($application_id, $notes = '') {
    global $wpdb;
    $table = $wpdb->prefix . 'job_applications';
    
    // Get application and user details
    $application = $wpdb->get_row($wpdb->prepare(
        "SELECT a.*, u.user_email, u.display_name 
        FROM $table a 
        JOIN {$wpdb->users} u ON a.user_id = u.ID 
        WHERE a.id = %d",
        $application_id
    ));
    
    if (!$application) {
        return false;
    }
    
    $job_title = get_the_title($application->job_id);
    $formatted_date = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($application->interview_date));
    
    $subject = sprintf('Interview Rescheduled for %s', $job_title);
    
    // Get custom email template
    $email_template = get_option('interview_rescheduled_email', get_default_interview_rescheduled_email());
    
    // Replace placeholders
    $message = str_replace(
        array('{name}', '{job_title}', '{interview_date}', '{location}', '{notes}'),
        array($application->display_name, $job_title, $formatted_date, $application->interview_location, $notes),
        $email_template
    );
    
    error_log('Sending interview rescheduled email to: ' . $application->user_email);
    return wp_mail($application->user_email, $subject, $message);
}

// Send application shortlisted email
function send_application_shortlisted_email($application_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'job_applications';
    
    // Get application and user details
    $application = $wpdb->get_row($wpdb->prepare(
        "SELECT a.*, u.user_email, u.display_name 
        FROM $table a 
        JOIN {$wpdb->users} u ON a.user_id = u.ID 
        WHERE a.id = %d",
        $application_id
    ));
    
    if (!$application) {
        return false;
    }
    
    $job_title = get_the_title($application->job_id);
    
    $subject = sprintf('Application Shortlisted for %s', $job_title);
    
    // Get custom email template
    $email_template = get_option('application_shortlisted_email', get_default_application_shortlisted_email());
    
    // Replace placeholders
    $message = str_replace(
        array('{name}', '{job_title}'),
        array($application->display_name, $job_title),
        $email_template
    );
    
    error_log('Sending shortlisted email to: ' . $application->user_email);
    return wp_mail($application->user_email, $subject, $message);
}

// Send application rejected email
function send_application_rejected_email($application_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'job_applications';
    
    // Get application and user details
    $application = $wpdb->get_row($wpdb->prepare(
        "SELECT a.*, u.user_email, u.display_name 
        FROM $table a 
        JOIN {$wpdb->users} u ON a.user_id = u.ID 
        WHERE a.id = %d",
        $application_id
    ));
    
    if (!$application) {
        return false;
    }
    
    $job_title = get_the_title($application->job_id);
    
    $subject = sprintf('Application Status for %s', $job_title);
    
    // Get custom email template
    $email_template = get_option('application_rejected_email', get_default_application_rejected_email());
    
    // Replace placeholders
    $message = str_replace(
        array('{name}', '{job_title}'),
        array($application->display_name, $job_title),
        $email_template
    );
    
    error_log('Sending rejected email to: ' . $application->user_email);
    return wp_mail($application->user_email, $subject, $message);
}