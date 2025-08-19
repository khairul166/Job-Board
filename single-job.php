<?php
/**
 * Template Name: Single job
 *
 * @package Job_Listing_Theme
 */

get_header();

// Get the current job post
if (have_posts()) :
    while (have_posts()) : the_post();
        
        // Get job meta data
        $location = get_post_meta(get_the_ID(), '_job_location', true);
        $deadline = get_post_meta(get_the_ID(), '_job_deadline', true);
        $job_types = get_post_meta(get_the_ID(), '_job_type', true);
        $salary_type = get_post_meta(get_the_ID(), '_job_salary_type', true);
        $apply_method = get_post_meta(get_the_ID(), '_job_apply_method', true);
        $external_link = get_post_meta(get_the_ID(), '_job_external_link', true);
        $is_featured = get_post_meta(get_the_ID(), '_job_featured', true);
        $experience_level = get_post_meta(get_the_ID(), '_job_experience_level', true);
        
        // Get custom fields with WP editors
        $full_description = get_post_meta(get_the_ID(), '_job_full_description', true);
        $responsibilities = get_post_meta(get_the_ID(), '_job_responsibilities', true);
        $requirements = get_post_meta(get_the_ID(), '_job_requirements', true);
        $benefits = get_post_meta(get_the_ID(), '_job_benefits', true);
        
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
        
        // Format experience level for display
        $experience_options = array(
            'entry' => __('Entry Level', 'job-listing'),
            'mid' => __('Mid Level', 'job-listing'),
            'senior' => __('Senior Level', 'job-listing'),
            'executive' => __('Executive Level', 'job-listing'),
        );
        $experience_display = isset($experience_options[$experience_level]) ? $experience_options[$experience_level] : '';
        
        // Calculate time ago
        $posted_date = get_the_date('U');
        $current_time = current_time('timestamp');
        $time_diff = human_time_diff($posted_date, $current_time);
        
        // Check if deadline has passed
        $deadline_passed = (strtotime($deadline) < current_time('timestamp'));
        
        // Get company info (assuming company info is stored in post meta or related post)
        $company_name = get_post_meta(get_the_ID(), '_company_name', true);
        $company_description = get_post_meta(get_the_ID(), '_company_description', true);
        $company_logo = get_post_meta(get_the_ID(), '_company_logo', true);
        
        // Get similar jobs
        $similar_jobs_args = array(
            'post_type' => 'job',
            'posts_per_page' => 3,
            'post__not_in' => array(get_the_ID()),
            'tax_query' => array(
                array(
                    'taxonomy' => 'job_category',
                    'field' => 'term_id',
                    'terms' => wp_list_pluck($categories, 'term_id'),
                ),
            ),
        );
        $similar_jobs = new WP_Query($similar_jobs_args);
?>
    <!-- Job Header -->
    <section class="job-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-3">
                        <?php if ($company_logo) : ?>
                            <div class="company-logo me-3">
                                <img src="<?php echo esc_url($company_logo); ?>" alt="<?php echo esc_attr($company_name); ?>" class="img-fluid">
                            </div>
                        <?php endif; ?>
                        <div>
                            <h1 class="display-5 fw-bold mb-2"><?php the_title(); ?></h1>
                            <p class="text-white">
                                <i class="fas fa-calendar-week me-2"></i><?php echo date_i18n('d F, Y', strtotime($deadline)); ?>
                                <span class="mx-2">|</span>
                                <i class="fas fa-map-marker-alt me-1"></i> <?php echo esc_html($location); ?>
                                <span class="mx-2">|</span>
                                <i class="fa-solid fa-bangladeshi-taka-sign"></i> <?php echo $salary_html; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Main Content -->
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8">
                <!-- Deadline Alert -->
                <div class="deadline-alert <?php echo $deadline_passed ? 'deadline-passed' : ''; ?>">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong><?php _e('Application Deadline:', 'job-listing'); ?></strong> <?php echo date_i18n('F d, Y', strtotime($deadline)); ?>
                    <span class="float-end">
                        <?php if ($deadline_passed) : ?>
                            <span class="status-badge status-closed"><?php _e('Closed', 'job-listing'); ?></span>
                        <?php else : ?>
                            <span class="status-badge status-open"><?php _e('Open', 'job-listing'); ?></span>
                        <?php endif; ?>
                    </span>
                </div>
                <!-- Job Overview -->
                <div class="section-card">
                    <h2 class="section-title"><?php _e('Job Overview', 'job-listing'); ?></h2>
                    <p class="lead"><?php echo wp_kses_post(get_the_excerpt()); ?></p>

                    <div class="mt-4">
                        <h5><?php _e('Job Type & Tags', 'job-listing'); ?></h5>
                        <?php if (!empty($job_types)) : ?>
                            <?php foreach ($job_types as $type) : ?>
                                <span class="job-type-badge <?php echo esc_attr(strtolower($type)); ?>"><?php echo esc_html($type); ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($experience_display) : ?>
                            <span class="job-type-badge <?php echo esc_attr($experience_level); ?>"><?php echo esc_html($experience_display); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Full Job Description -->
                <?php if ($full_description) : ?>
                <div class="section-card">
                    <h2 class="section-title"><?php _e('Full Job Description', 'job-listing'); ?></h2>
                    <?php echo wp_kses_post($full_description); ?>
                </div>
                <?php endif; ?>
                <!-- Responsibilities -->
                <?php if ($responsibilities) : ?>
                <div class="section-card">
                    <h2 class="section-title"><?php _e('Responsibilities', 'job-listing'); ?></h2>
                    <?php echo wp_kses_post($responsibilities); ?>
                </div>
                <?php endif; ?>
                <!-- Requirements -->
                <?php if ($requirements) : ?>
                <div class="section-card">
                    <h2 class="section-title"><?php _e('Requirements', 'job-listing'); ?></h2>
                    <?php echo wp_kses_post($requirements); ?>
                    
                    <?php if (!empty($skills)) : ?>
                    <h5 class="mt-4"><?php _e('Preferred Skills', 'job-listing'); ?></h5>
                    <div>
                        <?php foreach ($skills as $skill) : ?>
                            <span class="skill-tag"><?php echo esc_html($skill->name); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <!-- Benefits -->
                <?php if ($benefits) : ?>
                <div class="section-card">
                    <h2 class="section-title"><?php _e('Benefits', 'job-listing'); ?></h2>
                    <?php echo wp_kses_post($benefits); ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-4">
<!-- Apply Form Card -->
<div class="apply-form">
    <?php
        global $wpdb;
    $table = $wpdb->prefix . 'job_applications';
    $user_id = get_current_user_id();
        $job_id     = get_the_ID(); 
        // Check if user already applied
    $already_applied = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM $table WHERE user_id = %d AND job_id = %d",
        $user_id, $job_id
    ));
     ?>
    <h4 class="mb-3"><?php _e('Apply for this Position', 'job-listing'); ?></h4>
    <?php if ($deadline_passed) : ?>
        <button class="btn btn-secondary w-100 btn-lg" disabled>
            <i class="fas fa-lock me-2"></i><?php _e('Application Closed', 'job-listing'); ?>
        </button>
    <?php elseif ($apply_method === 'onsite') : ?>
        <?php if (is_user_logged_in()) : 
            if($already_applied) :
            ?>
            <button class="btn btn-success w-100 btn-lg" data-bs-toggle="modal" data-bs-target="#applyModal" disabled>
                <i class="fas fa-paper-plane me-2"></i><?php _e('Already Applied', 'job-listing'); ?>
            </button>
            <?php else : ?>
                <button class="btn btn-success w-100 btn-lg" data-bs-toggle="modal" data-bs-target="#applyModal">
                <i class="fas fa-paper-plane me-2"></i><?php _e('Apply Now', 'job-listing'); ?>
            </button>
            <?php endif; ?>
        <?php else : ?>
            <a href="<?php echo wp_login_url(get_permalink()); ?>" class="btn btn-success w-100 btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i><?php _e('Login to Apply', 'job-listing'); ?>
            </a>
        <?php endif; ?>
    <?php elseif ($apply_method === 'external' && !empty($external_link)) : ?>
        <a href="<?php echo esc_url($external_link); ?>" class="btn btn-success w-100 btn-lg" target="_blank">
            <i class="fas fa-external-link-alt me-2"></i><?php _e('Apply Externally', 'job-listing'); ?>
        </a>
    <?php endif; ?>
    <hr>
    <div class="text-center">
        <small class="text-muted">
            <i class="fas fa-shield-alt me-1"></i>
            <?php _e('Your information is secure and will not be shared', 'job-listing'); ?>
        </small>
    </div>
</div>
                <!-- Job Summary -->
                <div class="section-card">
                    <h5 class="mb-3"><?php _e('Quick Summary', 'job-listing'); ?></h5>
                    <div class="job-meta-item">
                        <i class="fas fa-clock"></i>
                        <span><strong><?php _e('Job Type:', 'job-listing'); ?></strong> 
                            <?php echo !empty($job_types) ? implode(', ', $job_types) : __('N/A', 'job-listing'); ?>
                        </span>
                    </div>
                    <div class="job-meta-item">
                        <i class="fas fa-dollar-sign"></i>
                        <span><strong><?php _e('Salary:', 'job-listing'); ?></strong> <?php echo $salary_html; ?></span>
                    </div>
                    <div class="job-meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><strong><?php _e('Location:', 'job-listing'); ?></strong> <?php echo esc_html($location); ?></span>
                    </div>
                    <div class="job-meta-item">
                        <i class="fas fa-briefcase"></i>
                        <span><strong><?php _e('Experience:', 'job-listing'); ?></strong> <?php echo esc_html($experience_display); ?></span>
                    </div>
                    <div class="job-meta-item">
                        <i class="fas fa-calendar"></i>
                        <span><strong><?php _e('Posted:', 'job-listing'); ?></strong> <?php printf(__('%s ago', 'job-listing'), $time_diff); ?></span>
                    </div>
                    <div class="job-meta-item">
                        <i class="fas fa-clock"></i>
                        <span><strong><?php _e('Deadline:', 'job-listing'); ?></strong> <?php echo date_i18n('F d, Y', strtotime($deadline)); ?></span>
                    </div>
                </div>
                <!-- Company Info -->
                <?php if ($company_name || $company_description) : ?>
                <div class="section-card">
                    <h5 class="mb-3"><?php _e('About', 'job-listing'); ?> <?php echo esc_html($company_name); ?></h5>
                    <p class="small"><?php echo wp_kses_post($company_description); ?></p>
                    <div class="text-center">
                        <a href="#" class="btn btn-outline-success btn-sm"><?php _e('View Company Profile', 'job-listing'); ?></a>
                    </div>
                </div>
                <?php endif; ?>
                <!-- Similar Jobs -->
                <?php if ($similar_jobs->have_posts()) : ?>
                <div class="section-card">
                    <h5 class="mb-3"><?php _e('Similar Jobs', 'job-listing'); ?></h5>
                    <div class="list-group">
                        <?php while ($similar_jobs->have_posts()) : $similar_jobs->the_post(); ?>
                            <?php
                            // Get similar job meta
                            $similar_location = get_post_meta(get_the_ID(), '_job_location', true);
                            $similar_deadline = get_post_meta(get_the_ID(), '_job_deadline', true);
                            $similar_salary_type = get_post_meta(get_the_ID(), '_job_salary_type', true);
                            
                            // Format similar job salary
                            $similar_salary_html = '';
                            if ($similar_salary_type === 'negotiable') {
                                $similar_salary_html = __('Negotiable', 'job-listing');
                            } elseif ($similar_salary_type === 'fixed') {
                                $similar_fixed_salary = get_post_meta(get_the_ID(), '_job_fixed_salary', true);
                                $similar_fixed_salary_period = get_post_meta(get_the_ID(), '_job_fixed_salary_period', true);
                                $similar_salary_html = esc_html($similar_fixed_salary) . '/' . esc_html($similar_fixed_salary_period);
                            } elseif ($similar_salary_type === 'range') {
                                $similar_min_salary = get_post_meta(get_the_ID(), '_job_min_salary', true);
                                $similar_max_salary = get_post_meta(get_the_ID(), '_job_max_salary', true);
                                $similar_salary_range_period = get_post_meta(get_the_ID(), '_job_salary_range_period', true);
                                $similar_salary_html = esc_html($similar_min_salary) . ' - ' . esc_html($similar_max_salary) . '/' . esc_html($similar_salary_range_period);
                            }
                            ?>
                            <div class="list-group-item d-flex flex-column gap-2">
                                <h6 class="mb-1">
                                    <a href="<?php the_permalink(); ?>" class="job-title">
                                        <i class="fas fa-briefcase me-2"></i><?php the_title(); ?>
                                    </a>
                                </h6>
                                <div><i class="fas fa-dollar-sign me-2"></i><?php echo $similar_salary_html; ?></div>
                                <div><i class="fas fa-map-marker-alt me-2"></i><?php echo esc_html($similar_location); ?></div>
                                <div><i class="fas fa-calendar-alt me-2"></i><?php echo date_i18n('M d, Y', strtotime($similar_deadline)); ?></div>
                            </div>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
<!-- Apply Modal -->
<div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="applyModalLabel">Apply for Senior Frontend Developer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Success Message (Initially Hidden) -->
                <div id="successMessage" class="d-none text-center py-4">
                    <div class="success-animation mx-auto mb-3">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                        </svg>
                    </div>
                    <h4 class="alert-heading mb-3">Application Submitted Successfully!</h4>
                    <div class="px-4">
                        <p class="lead mb-3">Thank you for applying.</p>
                        <p class="mb-0">We've received your application. We'll review your details and get back to you shortly.</p>
                    </div>
                </div>
                
                <!-- Error Message (Initially Hidden) -->
                <div id="generalErrorText" class="alert alert-danger d-none">
                                        <div class="warning-massage text-center py-4">
                        <div class="warning-animation mx-auto mb-3">
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
                        <h4 class="alert-heading">Application Failed</h4>
                            <p id="errorMessageText" class="mb-0">There was an error submitting your application. Please try again.</p>
                    </div>
                </div>
                                <!-- Error Message (Initially Hidden) -->
                <div id="alreadyapplied" class="alert alert-danger d-none">
                    <div class="warning-massage text-center py-4">
                        <div class="warning-animation mx-auto mb-3">
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
                    </div>
                    <h4 class="alert-heading mb-3 text-center">You have already Applied to this Circular.</h4>
                </div>
                
                <div id="applicationForm">
                    <div class="warning-massage text-center py-4">
                        <div class="warning-animation mx-auto mb-3">
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
                    </div>
                    <h4 class="alert-heading mb-3 text-center">Are you sure you want to apply?</h4>
                </div>
            </div>
            <div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
<button type="button" class="btn btn-primary" id="applysubmitBtn">
    <i class="fas fa-paper-plane me-2"></i>Apply
</button>
            </div>
        </div>
    </div>
</div>
    <?php
    endwhile;
endif;




 get_footer(); ?>