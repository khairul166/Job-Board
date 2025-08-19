<?php
/**
 * Template Name: Job Listing
 *
 * @package Job_Listing_Theme
 */

get_header();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3"><?php _e('Find Your Dream Job', 'job-listing'); ?></h1>
        <p class="lead mb-4"><?php _e('Browse thousands of job listings from top companies worldwide', 'job-listing'); ?></p>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-lg" id="search-keywords"
                        placeholder="<?php _e('Job title, keywords, or company', 'job-listing'); ?>">
                    <input type="text" class="form-control form-control-lg" id="search-location"
                        placeholder="<?php _e('Location', 'job-listing'); ?>">
                    <button class="btn btn-success btn-lg" type="button" id="search-jobs">
                        <i class="fas fa-search me-2"></i><?php _e('Search', 'job-listing'); ?>
                    </button>
                </div>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <span class="badge bg-warning text-dark"><?php _e('Software Engineer', 'job-listing'); ?></span>
                    <span class="badge bg-warning text-dark"><?php _e('Marketing', 'job-listing'); ?></span>
                    <span class="badge bg-warning text-dark"><?php _e('UX Designer', 'job-listing'); ?></span>
                    <span class="badge bg-warning text-dark"><?php _e('Remote', 'job-listing'); ?></span>
                    <span class="badge bg-warning text-dark"><?php _e('Full-time', 'job-listing'); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<div class="container my-5">
    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
<div class="filter-section">
    <div class="row">
        <div class="col-md-3 mb-2">
            <select class="form-select" id="job-type-filter">
                <option value=""><?php _e('All Job Types', 'job-listing'); ?></option>
                <?php
                $job_types = get_unique_job_types();
                if (!empty($job_types)) {
                    foreach ($job_types as $type) {
                        echo '<option value="' . esc_attr(strtolower($type)) . '">' . esc_html($type) . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>' . __('No job types available', 'job-listing') . '</option>';
                }
                ?>
            </select>
        </div>
<div class="col-md-3 mb-2">
    <select class="form-select" id="experience-filter">
        <option value=""><?php _e('All Experience Levels', 'job-listing'); ?></option>
        <?php
        $experience_levels = get_experience_levels();
        $experience_options = array(
            'entry' => __('Entry Level', 'job-listing'),
            'mid' => __('Mid Level', 'job-listing'),
            'senior' => __('Senior Level', 'job-listing'),
            'executive' => __('Executive Level', 'job-listing'),
        );
        
        if (!empty($experience_levels)) {
            foreach ($experience_levels as $level) {
                if (isset($experience_options[$level])) {
                    echo '<option value="' . esc_attr($level) . '">' . esc_html($experience_options[$level]) . '</option>';
                }
            }
        } else {
            echo '<option value="" disabled>' . __('No experience levels available', 'job-listing') . '</option>';
        }
        ?>
    </select>
</div>
        <div class="col-md-3 mb-2">
            <select class="form-select" id="industry-filter">
                <option value=""><?php _e('All Industries', 'job-listing'); ?></option>
                <?php
                $industries = get_industries();
                if (!empty($industries)) {
                    foreach ($industries as $industry) {
                        echo '<option value="' . esc_attr(strtolower($industry->name)) . '">' . esc_html($industry->name) . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>' . __('No industries available', 'job-listing') . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-success w-100" id="reset-filters">
                <i class="fas fa-sync-alt me-2"></i><?php _e('Reset Filters', 'job-listing'); ?>
            </button>
        </div>
    </div>
</div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <!-- Job Listings -->
            <div id="job-listings-container">
                <?php
                // Get current page for pagination
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                
                // Query arguments
                $args = array(
                    'post_type'      => 'job',
                    'posts_per_page' => 20,
                    'paged'          => $paged,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                );
                
                // Check if we have search parameters
                if (isset($_GET['keywords']) && !empty($_GET['keywords'])) {
                    $args['s'] = sanitize_text_field($_GET['keywords']);
                }
                
                if (isset($_GET['location']) && !empty($_GET['location'])) {
                    $args['meta_query'][] = array(
                        'key'     => '_job_location',
                        'value'   => sanitize_text_field($_GET['location']),
                        'compare' => 'LIKE'
                    );
                }
                
                if (isset($_GET['job_type']) && !empty($_GET['job_type'])) {
                    $args['meta_query'][] = array(
                        'key'     => '_job_type',
                        'value'   => sanitize_text_field($_GET['job_type']),
                        'compare' => 'LIKE'
                    );
                }
                
                $job_query = new WP_Query($args);
                
                if ($job_query->have_posts()) :
                    while ($job_query->have_posts()) : $job_query->the_post();
                        // Get job meta data
                        $location = get_post_meta(get_the_ID(), '_job_location', true);
                        $deadline = get_post_meta(get_the_ID(), '_job_deadline', true);
                        $job_types = get_post_meta(get_the_ID(), '_job_type', true);
                        $salary_type = get_post_meta(get_the_ID(), '_job_salary_type', true);
                        $apply_method = get_post_meta(get_the_ID(), '_job_apply_method', true);
                        $external_link = get_post_meta(get_the_ID(), '_job_external_link', true);
                        $is_featured = get_post_meta(get_the_ID(), '_job_featured', true);
                        
                        // Get taxonomies
                        $categories = get_the_terms(get_the_ID(), 'job_category');
                        $skills = get_the_terms(get_the_ID(), 'job_skill');
                        $tags = get_the_terms(get_the_ID(), 'job_tag');
                        
                        // Format salary information
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
                        
                        // Format job types for data attributes
                        $job_type_data = !empty($job_types) ? implode(' ', $job_types) : '';
                        
// Format experience level (from metabox)
$experience_level = get_post_meta(get_the_ID(), '_job_experience_level', true);

$experience_options = array(
    'entry' => __('Entry Level', 'job-listing'),
    'mid' => __('Mid Level', 'job-listing'),
    'senior' => __('Senior Level', 'job-listing'),
    'executive' => __('Executive Level', 'job-listing'),
);

$experience_display = isset($experience_options[$experience_level]) ? $experience_options[$experience_level] : '';
                        
                        // Format industry (from categories)
                        $industry = '';
                        if ($categories) {
                            $industry = strtolower($categories[0]->name);
                        }
                        
                        // Calculate time ago
                        $posted_date = get_the_date('U');
                        $current_time = current_time('timestamp');
                        $time_diff = human_time_diff($posted_date, $current_time);
                        ?>
                        <div class="card job-card" 
                             data-job-type="<?php echo esc_attr($job_type_data); ?>" 
                             data-experience="<?php echo esc_attr($experience_level); ?>"
                             data-industry="<?php echo esc_attr($industry); ?>">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4><a href="<?php the_permalink(); ?>" class="job-title"><?php the_title(); ?></a></h4>
                                        <p class="text-muted">
                                            <i class="fas fa-calendar-week me-2 text-muted"></i><?php echo date_i18n('d F, Y', strtotime($deadline)); ?>
                                            <span class="mx-2">|</span>
                                            <i class="fas fa-map-marker-alt me-1"></i> <?php echo esc_html($location); ?>
                                            <span class="mx-2">|</span>
                                            <i class="fa-solid fa-bangladeshi-taka-sign"></i> <?php echo $salary_html; ?>
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <?php if ($is_featured) : ?>
                                            <span class="status-badge status-featured"><?php _e('Featured', 'job-listing'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-8">
                                        <p><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php if (!empty($job_types)) : ?>
                                                <?php foreach ($job_types as $type) : ?>
                                                    <span class="job-type <?php echo esc_attr(strtolower($type)); ?>"><?php echo esc_html($type); ?></span>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end mt-md-0 mt-3">
                                        <small class="text-muted d-block mb-2"><i class="fas fa-clock me-1"></i><?php printf(__('Posted %s ago', 'job-listing'), $time_diff); ?></small>
                                        <?php if ($apply_method === 'onsite') : ?>
                                            <a href="<?php the_permalink(); ?>" class="btn btn-outline-success btn-sm"><?php _e('Apply Now', 'job-listing'); ?></a>
                                        <?php elseif ($apply_method === 'external' && !empty($external_link)) : ?>
                                            <a href="<?php echo esc_url($external_link); ?>" class="btn btn-outline-success btn-sm" target="_blank"><?php _e('Apply Now', 'job-listing'); ?></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    
<!-- Pagination -->
<?php job_listing_pagination_with_query($job_query); ?>
                <?php else : ?>
                    <div class="no-jobs-found">
                        <h3><?php _e('No jobs found', 'job-listing'); ?></h3>
                        <p><?php _e('Try adjusting your search criteria or check back later for new opportunities.', 'job-listing'); ?></p>
                    </div>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
        <div class="col-lg-4">
            <!-- Sidebar -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-bell me-2"></i><?php _e('Job Alerts', 'job-listing'); ?></h5>
                </div>
                <div class="card-body">
                    <p><?php _e('Get notified about new jobs matching your search criteria', 'job-listing'); ?></p>
                    <div class="mb-3">
                        <label for="job-alert-email" class="form-label"><?php _e('Email address', 'job-listing'); ?></label>
                        <input type="email" class="form-control" id="job-alert-email"
                            placeholder="<?php _e('name@example.com', 'job-listing'); ?>">
                    </div>
                    <button class="btn btn-success w-100"><?php _e('Create Job Alert', 'job-listing'); ?></button>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i><?php _e('Job Market Insights', 'job-listing'); ?></h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold"><?php _e('Software Engineer Demand', 'job-listing'); ?></h6>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-info" style="width: 85%">85%</div>
                        </div>
                        <small class="text-muted"><?php _e('↑ 15% from last quarter', 'job-listing'); ?></small>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold"><?php _e('Remote Jobs', 'job-listing'); ?></h6>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-warning" style="width: 65%">65%</div>
                        </div>
                        <small class="text-muted"><?php _e('↑ 22% from last year', 'job-listing'); ?></small>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold"><?php _e('Entry Level Positions', 'job-listing'); ?></h6>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success" style="width: 45%">45%</div>
                        </div>
                        <small class="text-muted"><?php _e('↓ 5% from last quarter', 'job-listing'); ?></small>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i><?php _e('Quick Tips', 'job-listing'); ?></h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold"><?php _e('Tailor Your Resume', 'job-listing'); ?></h6>
                        <p class="small"><?php _e('Customize your resume to match the job description keywords.', 'job-listing'); ?></p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold"><?php _e('Follow Up', 'job-listing'); ?></h6>
                        <p class="small"><?php _e('Send a thank you email within 24 hours after an interview.', 'job-listing'); ?></p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold"><?php _e('Network', 'job-listing'); ?></h6>
                        <p class="small"><?php _e('80% of jobs are filled through networking, not job boards.', 'job-listing'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>