<?php
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
    
    // New filter values
    $min_age = isset($_GET['min_age']) ? intval($_GET['min_age']) : '';
    $max_age = isset($_GET['max_age']) ? intval($_GET['max_age']) : '';
    $gender = isset($_GET['gender']) ? sanitize_text_field($_GET['gender']) : '';
    
    // Get experience range values
    $min_experience = isset($_GET['min_experience']) ? intval($_GET['min_experience']) : 0;
    $max_experience = isset($_GET['max_experience']) ? intval($_GET['max_experience']) : 20;
    
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
    
    // Add age filter
    if (!empty($min_age) || !empty($max_age)) {
        $joins[] = "LEFT JOIN {$usermeta_table} dob ON dob.user_id = ja.user_id AND dob.meta_key = 'date_of_birth'";
        
        if (!empty($min_age) && !empty($max_age)) {
            $where .= " AND TIMESTAMPDIFF(YEAR, dob.meta_value, CURDATE()) BETWEEN %d AND %d";
            $prepare_values[] = $min_age;
            $prepare_values[] = $max_age;
        } elseif (!empty($min_age)) {
            $where .= " AND TIMESTAMPDIFF(YEAR, dob.meta_value, CURDATE()) >= %d";
            $prepare_values[] = $min_age;
        } elseif (!empty($max_age)) {
            $where .= " AND TIMESTAMPDIFF(YEAR, dob.meta_value, CURDATE()) <= %d";
            $prepare_values[] = $max_age;
        }
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
    
    // Combine all joins
    $join_clause = implode(' ', $joins);
    
    // Get all application IDs that match the other filters first
    $query_ids = "SELECT DISTINCT ja.id FROM {$applications_table} ja {$join_clause} {$where}";
    $all_app_ids = $wpdb->get_col($wpdb->prepare($query_ids, $prepare_values));
    
    // Filter by experience in PHP
    $filtered_app_ids = array();
    if ($min_experience > 0 || $max_experience < 20) {
        foreach ($all_app_ids as $app_id) {
            // Get user ID for this application
            $user_id = $wpdb->get_var($wpdb->prepare(
                "SELECT user_id FROM {$applications_table} WHERE id = %d", 
                $app_id
            ));
            
            if ($user_id) {
                // Get work experience data
                $work_experience = get_user_meta($user_id, 'work_experience', true);
                $total_months = calculate_total_experience_months($work_experience);
                
                // Check if experience is within range
                if ($total_months >= ($min_experience * 12) && $total_months <= ($max_experience * 12)) {
                    $filtered_app_ids[] = $app_id;
                }
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
                        'current' => $current_page,
                        'add_args' => array(
                            'present_district' => $user_present_district,
                            'permanent_district' => $user_permanent_district,
                            'highest_education' => $highest_education,
                            'experience_years' => $experience_years,
                            'min_age' => $min_age,
                            'max_age' => $max_age,
                            'gender' => $gender,
                            'status' => $current_status,
                            'min_experience' => $min_experience,
                            'max_experience' => $max_experience
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
                updateSlider();
            });
            
            maxThumb.addEventListener('input', function() {
                maxVal = parseInt(this.value);
                if (maxVal < minVal) maxVal = minVal;
                maxInput.value = maxVal;
                maxDisplay.textContent = maxVal;
                updateSlider();
            });
            
            function updateSlider() {
                // Add your slider update logic here
            }
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
                updateSlider();
            });
            
            maxThumb.addEventListener('input', function() {
                maxVal = parseInt(this.value);
                if (maxVal < minVal) maxVal = minVal;
                maxInput.value = maxVal;
                maxDisplay.textContent = maxVal + '+ years';
                updateSlider();
            });
            
            function updateSlider() {
                // Add your slider update logic here
            }
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