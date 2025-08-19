<?php
/**
 * Add Job Metaboxes
 */
function job_add_metaboxes() {
    add_meta_box(
        'job_details',
        __('Job Details', 'job-listing'),
        'job_details_callback',
        'job',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'job_add_metaboxes');

/**
 * Job Details Metabox Callback
 */
function job_details_callback($post) {
    wp_nonce_field('job_save_meta_data', 'job_meta_nonce');
    
    // Get existing values
    $full_description = get_post_meta($post->ID, '_job_full_description', true);
    $responsibilities = get_post_meta($post->ID, '_job_responsibilities', true);
    $requirements = get_post_meta($post->ID, '_job_requirements', true);
    $benefits = get_post_meta($post->ID, '_job_benefits', true);
    $deadline = get_post_meta($post->ID, '_job_deadline', true);
    $job_type = get_post_meta($post->ID, '_job_type', true) ? get_post_meta($post->ID, '_job_type', true) : array();
    $salary_type = get_post_meta($post->ID, '_job_salary_type', true);
    $fixed_salary = get_post_meta($post->ID, '_job_fixed_salary', true);
    $fixed_salary_period = get_post_meta($post->ID, '_job_fixed_salary_period', true);
    $min_salary = get_post_meta($post->ID, '_job_min_salary', true);
    $max_salary = get_post_meta($post->ID, '_job_max_salary', true);
    $salary_range_period = get_post_meta($post->ID, '_job_salary_range_period', true);
    $location = get_post_meta($post->ID, '_job_location', true);
    $apply_method = get_post_meta($post->ID, '_job_apply_method', true);
    $external_link = get_post_meta($post->ID, '_job_external_link', true);
    $preferred_skills = get_post_meta($post->ID, '_job_preferred_skills', true) ? get_post_meta($post->ID, '_job_preferred_skills', true) : array();
    
    // Job types
    $job_types = array(
        'full-time' => __('Full Time', 'job-listing'),
        'part-time' => __('Part Time', 'job-listing'),
        'contract' => __('Contract', 'job-listing'),
        'temporary' => __('Temporary', 'job-listing'),
        'internship' => __('Internship', 'job-listing'),
        'remote' => __('Remote', 'job-listing'),
    );
    
    // Salary types
    $salary_types = array(
        'negotiable' => __('Negotiable', 'job-listing'),
        'fixed' => __('Fixed', 'job-listing'),
        'range' => __('Salary Range', 'job-listing'),
    );
    
    // Salary periods
    $salary_periods = array(
        'monthly' => __('Monthly', 'job-listing'),
        'quarterly' => __('Quarterly', 'job-listing'),
        'half-yearly' => __('Half Yearly', 'job-listing'),
        'yearly' => __('Yearly', 'job-listing'),
    );
    
    // Apply methods
    $apply_methods = array(
        'onsite' => __('On Site Application', 'job-listing'),
        'external' => __('External Application', 'job-listing'),
    );
    
    ?>
    <div class="job-metabox">
        <div class="form-field">
            <h4><?php _e('Full Job Description', 'job-listing'); ?></h4>
            <?php 
            $content = $full_description;
            $editor_id = '_job_full_description';
            $settings = array(
                'textarea_name' => '_job_full_description',
                'media_buttons' => true,
                'textarea_rows' => 10,
            );
            wp_editor($content, $editor_id, $settings);
            ?>
        </div>
        
        <div class="form-field">
            <h4><?php _e('Responsibilities', 'job-listing'); ?></h4>
            <?php 
            $content = $responsibilities;
            $editor_id = '_job_responsibilities';
            $settings = array(
                'textarea_name' => '_job_responsibilities',
                'media_buttons' => false,
                'textarea_rows' => 8,
            );
            wp_editor($content, $editor_id, $settings);
            ?>
        </div>
        
        <div class="form-field">
            <h4><?php _e('Requirements', 'job-listing'); ?></h4>
            <?php 
            $content = $requirements;
            $editor_id = '_job_requirements';
            $settings = array(
                'textarea_name' => '_job_requirements',
                'media_buttons' => false,
                'textarea_rows' => 8,
            );
            wp_editor($content, $editor_id, $settings);
            ?>
        </div>
        
        <div class="form-field">
            <h4><?php _e('Benefits', 'job-listing'); ?></h4>
            <?php 
            $content = $benefits;
            $editor_id = '_job_benefits';
            $settings = array(
                'textarea_name' => '_job_benefits',
                'media_buttons' => false,
                'textarea_rows' => 8,
            );
            wp_editor($content, $editor_id, $settings);
            ?>
        </div>
        
        
        <div class="form-field">
            <label for="job-deadline"><?php _e('Application Deadline', 'job-listing'); ?></label>
            <input type="date" id="job-deadline" name="_job_deadline" value="<?php echo esc_attr($deadline); ?>" />
        </div>
        
        <div class="form-field">
            <h4><?php _e('Job Type', 'job-listing'); ?></h4>
            <?php foreach ($job_types as $value => $label) : ?>
                <div class="job-type-item">
                    <input type="checkbox" id="job-type-<?php echo esc_attr($value); ?>" name="_job_type[]" value="<?php echo esc_attr($value); ?>" <?php checked(in_array($value, $job_type)); ?> />
                    <label for="job-type-<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="form-field">
    <label for="job-experience-level"><?php _e('Experience Level', 'job-listing'); ?></label>
    <select id="job-experience-level" name="_job_experience_level">
        <option value=""><?php _e('Select Experience Level', 'job-listing'); ?></option>
        <option value="entry" <?php selected(get_post_meta($post->ID, '_job_experience_level', true), 'entry'); ?>><?php _e('Entry Level', 'job-listing'); ?></option>
        <option value="mid" <?php selected(get_post_meta($post->ID, '_job_experience_level', true), 'mid'); ?>><?php _e('Mid Level', 'job-listing'); ?></option>
        <option value="senior" <?php selected(get_post_meta($post->ID, '_job_experience_level', true), 'senior'); ?>><?php _e('Senior Level', 'job-listing'); ?></option>
        <option value="executive" <?php selected(get_post_meta($post->ID, '_job_experience_level', true), 'executive'); ?>><?php _e('Executive Level', 'job-listing'); ?></option>
    </select>
</div>
        
        <div class="form-field">
            <label for="job-salary-type"><?php _e('Salary Type', 'job-listing'); ?></label>
            <select id="job-salary-type" name="_job_salary_type">
                <option value=""><?php _e('Select Salary Type', 'job-listing'); ?></option>
                <?php foreach ($salary_types as $value => $label) : ?>
                    <option value="<?php echo esc_attr($value); ?>" <?php selected($salary_type, $value); ?>><?php echo esc_html($label); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div id="fixed-salary-fields" class="conditional-fields" style="display: none;">
            <div class="form-field">
                <label for="job-fixed-salary"><?php _e('Fixed Salary', 'job-listing'); ?></label>
                <input type="text" id="job-fixed-salary" name="_job_fixed_salary" value="<?php echo esc_attr($fixed_salary); ?>" />
            </div>
            
            <div class="form-field">
                <label for="job-fixed-salary-period"><?php _e('Fixed Salary Period', 'job-listing'); ?></label>
                <select id="job-fixed-salary-period" name="_job_fixed_salary_period">
                    <option value=""><?php _e('Select Period', 'job-listing'); ?></option>
                    <?php foreach ($salary_periods as $value => $label) : ?>
                        <option value="<?php echo esc_attr($value); ?>" <?php selected($fixed_salary_period, $value); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div id="salary-range-fields" class="conditional-fields" style="display: none;">
            <div class="form-field">
                <label for="job-min-salary"><?php _e('Minimum Salary', 'job-listing'); ?></label>
                <input type="text" id="job-min-salary" name="_job_min_salary" value="<?php echo esc_attr($min_salary); ?>" />
            </div>
            
            <div class="form-field">
                <label for="job-max-salary"><?php _e('Maximum Salary', 'job-listing'); ?></label>
                <input type="text" id="job-max-salary" name="_job_max_salary" value="<?php echo esc_attr($max_salary); ?>" />
            </div>
            
            <div class="form-field">
                <label for="job-salary-range-period"><?php _e('Salary Range Period', 'job-listing'); ?></label>
                <select id="job-salary-range-period" name="_job_salary_range_period">
                    <option value=""><?php _e('Select Period', 'job-listing'); ?></option>
                    <?php foreach ($salary_periods as $value => $label) : ?>
                        <option value="<?php echo esc_attr($value); ?>" <?php selected($salary_range_period, $value); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="form-field">
            <label for="job-location"><?php _e('Location', 'job-listing'); ?></label>
            <input type="text" id="job-location" name="_job_location" value="<?php echo esc_attr($location); ?>" />
        </div>
        
        <div class="form-field">
            <label for="job-apply-method"><?php _e('Apply Method', 'job-listing'); ?></label>
            <select id="job-apply-method" name="_job_apply_method">
                <option value=""><?php _e('Select Apply Method', 'job-listing'); ?></option>
                <?php foreach ($apply_methods as $value => $label) : ?>
                    <option value="<?php echo esc_attr($value); ?>" <?php selected($apply_method, $value); ?>><?php echo esc_html($label); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div id="external-link-field" class="conditional-fields" style="display: none;">
            <div class="form-field">
                <label for="job-external-link"><?php _e('External Application Link', 'job-listing'); ?></label>
                <input type="url" id="job-external-link" name="_job_external_link" value="<?php echo esc_url($external_link); ?>" />
            </div>
        </div>
        <div class="form-field">
    <label>
        <input type="checkbox" name="_job_featured" value="1" <?php checked(get_post_meta($post->ID, '_job_featured', true), '1'); ?> />
        <?php _e('Featured Job', 'job-listing'); ?>
    </label>
    <p class="description"><?php _e('Check this box to feature this job on the homepage.', 'job-listing'); ?></p>
</div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Toggle salary fields based on salary type
        $('#job-salary-type').change(function() {
            var value = $(this).val();
            $('.conditional-fields').hide();
            
            if (value === 'fixed') {
                $('#fixed-salary-fields').show();
            } else if (value === 'range') {
                $('#salary-range-fields').show();
            }
        });
        
        // Toggle external link field based on apply method
        $('#job-apply-method').change(function() {
            var value = $(this).val();
            if (value === 'external') {
                $('#external-link-field').show();
            } else {
                $('#external-link-field').hide();
            }
        });
        
        // Add/remove preferred skills
        $('#add-skill').click(function() {
            $('#preferred-skills-container').append('<div class="skill-item"><input type="text" name="_job_preferred_skills[]" value="" /> <span class="remove-skill">Remove</span></div>');
        });
        
        $(document).on('click', '.remove-skill', function() {
            if ($('#preferred-skills-container .skill-item').length > 1) {
                $(this).parent().remove();
            } else {
                $(this).parent().find('input').val('');
            }
        });
        
        // Show appropriate fields on page load
        $('#job-salary-type').trigger('change');
        $('#job-apply-method').trigger('change');
    });
    </script>
    
    <style>
    .job-metabox .form-field {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    
    .job-metabox .form-field:last-child {
        border-bottom: none;
    }
    
    .job-metabox h4 {
        margin: 0 0 10px 0;
        font-size: 14px;
        color: #23282d;
    }
    
    .job-metabox .job-type-item {
        margin-bottom: 5px;
    }
    

    
    .job-metabox .conditional-fields {
        background: #f9f9f9;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 3px;
        margin-top: 10px;
    }
    
    .job-metabox input[type="text"],
    .job-metabox input[type="url"],
    .job-metabox input[type="date"],
    .job-metabox select {
        width: 100%;
        max-width: 400px;
    }
    </style>
    <?php
}



/**
 * Save Job Metabox Data
 */
function job_save_meta_data($post_id) {
    // Check if our nonce is set.
    if (!isset($_POST['job_meta_nonce'])) {
        return;
    }
    
    // Verify that the nonce is valid.
    if (!wp_verify_nonce($_POST['job_meta_nonce'], 'job_save_meta_data')) {
        return;
    }
    
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check the user's permissions.
    if (isset($_POST['post_type']) && 'job' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }
    
    // Sanitize and save the data
    if (isset($_POST['_job_full_description'])) {
        update_post_meta($post_id, '_job_full_description', wp_kses_post($_POST['_job_full_description']));
    }
    
    if (isset($_POST['_job_responsibilities'])) {
        update_post_meta($post_id, '_job_responsibilities', wp_kses_post($_POST['_job_responsibilities']));
    }
    
    if (isset($_POST['_job_requirements'])) {
        update_post_meta($post_id, '_job_requirements', wp_kses_post($_POST['_job_requirements']));
    }
    
    if (isset($_POST['_job_benefits'])) {
        update_post_meta($post_id, '_job_benefits', wp_kses_post($_POST['_job_benefits']));
    }
    
    if (isset($_POST['_job_deadline'])) {
        update_post_meta($post_id, '_job_deadline', sanitize_text_field($_POST['_job_deadline']));
    }
    
    if (isset($_POST['_job_type']) && is_array($_POST['_job_type'])) {
        $job_type = array_map('sanitize_text_field', $_POST['_job_type']);
        update_post_meta($post_id, '_job_type', $job_type);
    } else {
        delete_post_meta($post_id, '_job_type');
    }
    
    if (isset($_POST['_job_salary_type'])) {
        update_post_meta($post_id, '_job_salary_type', sanitize_text_field($_POST['_job_salary_type']));
    }
    
    if (isset($_POST['_job_fixed_salary'])) {
        update_post_meta($post_id, '_job_fixed_salary', sanitize_text_field($_POST['_job_fixed_salary']));
    }
    
    if (isset($_POST['_job_fixed_salary_period'])) {
        update_post_meta($post_id, '_job_fixed_salary_period', sanitize_text_field($_POST['_job_fixed_salary_period']));
    }
    
    if (isset($_POST['_job_min_salary'])) {
        update_post_meta($post_id, '_job_min_salary', sanitize_text_field($_POST['_job_min_salary']));
    }
    
    if (isset($_POST['_job_max_salary'])) {
        update_post_meta($post_id, '_job_max_salary', sanitize_text_field($_POST['_job_max_salary']));
    }
    
    if (isset($_POST['_job_salary_range_period'])) {
        update_post_meta($post_id, '_job_salary_range_period', sanitize_text_field($_POST['_job_salary_range_period']));
    }
    
    if (isset($_POST['_job_location'])) {
        update_post_meta($post_id, '_job_location', sanitize_text_field($_POST['_job_location']));
    }
    
    if (isset($_POST['_job_apply_method'])) {
        update_post_meta($post_id, '_job_apply_method', sanitize_text_field($_POST['_job_apply_method']));
    }
    
    if (isset($_POST['_job_external_link'])) {
        update_post_meta($post_id, '_job_external_link', esc_url_raw($_POST['_job_external_link']));
    }

    // Save experience level
if (isset($_POST['_job_experience_level'])) {
    update_post_meta($post_id, '_job_experience_level', sanitize_text_field($_POST['_job_experience_level']));
} else {
    delete_post_meta($post_id, '_job_experience_level');
}
    
    if (isset($_POST['_job_preferred_skills']) && is_array($_POST['_job_preferred_skills'])) {
        $preferred_skills = array_filter(array_map('sanitize_text_field', $_POST['_job_preferred_skills']));
        update_post_meta($post_id, '_job_preferred_skills', $preferred_skills);
    } else {
        delete_post_meta($post_id, '_job_preferred_skills');
    }
    // Save featured status
if (isset($_POST['_job_featured'])) {
    update_post_meta($post_id, '_job_featured', '1');
} else {
    delete_post_meta($post_id, '_job_featured');
}
}
add_action('save_post', 'job_save_meta_data');