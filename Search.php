<?php
get_header();

// Get search query - handle both 's' and 'keywords' parameters
$search_query = isset($_GET['keywords']) ? sanitize_text_field($_GET['keywords']) : get_search_query();

// Get filters
$location   = isset($_GET['location']) ? sanitize_text_field($_GET['location']) : '';
$job_type   = isset($_GET['job_type']) ? sanitize_text_field($_GET['job_type']) : '';
$experience = isset($_GET['experience']) ? sanitize_text_field($_GET['experience']) : '';
$industry   = isset($_GET['industry']) ? sanitize_text_field($_GET['industry']) : '';

// Setup query
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$today = current_time('Y-m-d');
$args = array(
    'post_type'      => 'job',
    'posts_per_page' => 1,
    'paged'          => $paged,
    'meta_query'     => array(
        // Only show active jobs (deadline not passed)
        array(
            'key'     => '_job_deadline',
            'value'   => $today,
            'compare' => '>=',
            'type'    => 'DATE',
        ),
    ),
);

// Add search query if exists
if (!empty($search_query)) {
    $args['s'] = $search_query;
}

// Add location filter
if ($location) {
    $args['meta_query'][] = array(
        'key'     => '_job_location',
        'value'   => $location,
        'compare' => 'LIKE',
    );
}

// Add job type filter
if ($job_type) {
    $args['meta_query'][] = array(
        'key'     => '_job_type',
        'value'   => $job_type,
        'compare' => 'LIKE',
    );
}

// Add experience filter
if ($experience) {
    $args['meta_query'][] = array(
        'key'     => '_job_experience_level',
        'value'   => $experience,
        'compare' => '=',
    );
}

// Add industry filter (taxonomy)
if ($industry) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'job_category',
            'field'    => 'slug',
            'terms'    => $industry,
        ),
    );
}

$query = new WP_Query($args);
?>
<section class="job-header">
    <div class="container">
        <h1 class="display-5 fw-bold">
            <?php 
            if (!empty($search_query)) {
                printf(__('Search Results for "%s"', 'job-listing'), esc_html($search_query));
            } else {
                _e('All Job Listings', 'job-listing');
            }
            ?>
        </h1>
    </div>
</section>
<section class="search-results">
    <div class="container">
        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="filter-section">
                    <form method="get" action="">
                        <!-- Hidden fields to preserve search parameters -->
                        <?php if (!empty($search_query)): ?>
                            <input type="hidden" name="s" value="<?php echo esc_attr($search_query); ?>">
                        <?php endif; ?>
                        <?php if (!empty($location)): ?>
                            <input type="hidden" name="location" value="<?php echo esc_attr($location); ?>">
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <select class="form-select" name="job_type" id="job-type-filter">
                                    <option value=""><?php _e('All Job Types', 'job-listing'); ?></option>
                                    <?php
                                    $job_types = get_unique_job_types();
                                    if (!empty($job_types)) {
                                        foreach ($job_types as $type) {
                                            $selected = (isset($_GET['job_type']) && $_GET['job_type'] == strtolower($type)) ? 'selected' : '';
                                            echo '<option value="' . esc_attr(strtolower($type)) . '" ' . $selected . '>' . esc_html($type) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="" disabled>' . __('No job types available', 'job-listing') . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <select class="form-select" name="experience" id="experience-filter">
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
                                                $selected = (isset($_GET['experience']) && $_GET['experience'] == $level) ? 'selected' : '';
                                                echo '<option value="' . esc_attr($level) . '" ' . $selected . '>' . esc_html($experience_options[$level]) . '</option>';
                                            }
                                        }
                                    } else {
                                        echo '<option value="" disabled>' . __('No experience levels available', 'job-listing') . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <select class="form-select" name="industry" id="industry-filter">
                                    <option value=""><?php _e('All Industries', 'job-listing'); ?></option>
                                    <?php
                                    $industries = get_industries();
                                    if (!empty($industries)) {
                                        foreach ($industries as $industry) {
                                            $selected = (isset($_GET['industry']) && $_GET['industry'] == strtolower($industry->name)) ? 'selected' : '';
                                            echo '<option value="' . esc_attr(strtolower($industry->name)) . '" ' . $selected . '>' . esc_html($industry->name) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="" disabled>' . __('No industries available', 'job-listing') . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-filter me-2"></i><?php _e('Apply Filters', 'job-listing'); ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <?php if ($query->have_posts()) : ?>
            <p><?php printf(__('Found %d jobs', 'job-listing'), $query->found_posts); ?></p>
            <?php while ($query->have_posts()) : $query->the_post(); 
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
            <?php job_listing_pagination_with_query($query); ?>
        <?php else : ?>
            <div class="no-jobs-found">
                <h3><?php _e('No jobs found', 'job-listing'); ?></h3>
                <p><?php _e('Try adjusting your search criteria or check back later for new opportunities.', 'job-listing'); ?></p>
                <p><strong>Debug information:</strong></p>
                <ul>
                    <li>Search query: <?php echo esc_html($search_query); ?></li>
                    <li>Location filter: <?php echo esc_html($location); ?></li>
                    <li>Job type filter: <?php echo esc_html($job_type); ?></li>
                    <li>Experience filter: <?php echo esc_html($experience); ?></li>
                    <li>Industry filter: <?php echo esc_html($industry); ?></li>
                    <li>Found posts: <?php echo $query->found_posts; ?></li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
jQuery(document).ready(function($) {
    // Reset filters button
    $('#reset-filters').on('click', function(e) {
        e.preventDefault();
        // Get current URL without query parameters
        var url = window.location.href.split('?')[0];
        // Redirect to the clean URL
        window.location.href = url;
    });
});
</script>

<?php wp_reset_postdata(); ?>
<?php get_footer(); ?>