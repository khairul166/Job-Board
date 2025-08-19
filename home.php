<?php 
/**
* Template Name: Home Page
*
* @package WordPress
* @subpackage Twenty_Fourteen
* @since Twenty Fourteen 1.0
*/
get_header(); ?>
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center"> <!-- This line updated -->
                <div class="col-md-4">
                    <img src="https://easyfashion.com.bd/wp-content/uploads/2023/12/512370388846Job_Offer.gif"
                        class="img-fluid" height="500" />
                </div>
                <div class="col-md-8">
                    <h3>Career With Easy Fashion Ltd.</h3>
                    <p>
                        Easy Fashion Ltd is one of the leading premium fashion and lifestyle companies in Bangladesh.
                        About 3000 employees work hard to ensure the quality of our products. But a fascination for
                        fashion
                        isn’t the only thing that our staff have in common: We also want to inspire people with our
                        work.
                        And this inspiration begins with our employees. That’s why we foster a working environment in
                        which
                        you can contribute your personality, ideas and creativity. Design your workplace and your
                        professional future in a way that suits you best. Only when we work together something unique
                        will
                        emerge.
                    </p>
                    <a href="Job-listing.html" class="btn btn-success">View All Jobs</a>
                </div>
            </div>
        </div>
    </section>
    <section class="">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3>Contact Us</h3>
                    <p>
                        Hotline: +8801894-442775
                    </p>
                    <p>
                        Send your CV: hreasyfashion@gmail.com
                    </p>
                    <p>
                        If you have any Queries please feel free to contact us.</br>
                        Address: 34/B, Malibagh Chowdhurypara, Dhaka-1219
                    </p>


                    <p>
                        Tel: 02-222225673 (Head Office)</br>
                        Tel: 02-48322317 (Garments)</br>
                        Tel: 09612009205 (Hotline)

                    </p>
                    <p>
                        Email: easyfashionwears@gmail.com
                    </p>
                </div>
                <div class="col-md-4">
                    <img src="https://easyfashion.com.bd/wp-content/uploads/2023/12/126946587067Candidate_Evaluation.gif"
                        class="img-fluid" height="500" />
                </div>
            </div>
        </div>
    </section>
<?php
/**
 * Template part for displaying recent jobs in hero section
 *
 * @package Job_Listing_Theme
 */

// Query for recent jobs
$args = array(
    'post_type'      => 'job',
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$recent_jobs = new WP_Query($args);

if ($recent_jobs->have_posts()) :
?>
<section class="hero-section" style="margin-bottom: 0px;">
    <h1 class="text-center py-4"><?php _e('Recent Jobs', 'job-listing'); ?></h1>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- Job Listings -->
                <div id="job-listings-container">
                <?php while ($recent_jobs->have_posts()) : $recent_jobs->the_post(); ?>
                    <?php
                    // Get job meta data
                    $location = get_post_meta(get_the_ID(), '_job_location', true);
                    $deadline = get_post_meta(get_the_ID(), '_job_deadline', true);
                    $job_types = get_post_meta(get_the_ID(), '_job_type', true);
                    $salary_type = get_post_meta(get_the_ID(), '_job_salary_type', true);
                    $apply_method = get_post_meta(get_the_ID(), '_job_apply_method', true);
                    $external_link = get_post_meta(get_the_ID(), '_job_external_link', true);
                    
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
                    
                    // Check if job is featured (using a tag or custom field)
                    // Check if job is featured (using custom field)
                    $is_featured = get_post_meta(get_the_ID(), '_job_featured', true);
                    
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
                </div>
                <div class="text-center">
                    <a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>" class="btn btn-success">
                        <?php _e('View All Jobs', 'job-listing'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
endif;
wp_reset_postdata();
?>
<?php get_footer();?>