<?php 

// Schedule Interview AJAX Handler
function ajax_schedule_interview() {
    // Debug logging
    error_log('AJAX schedule_interview called');
    error_log('POST data: ' . print_r($_POST, true));
    
    // Verify nonce
    if (!check_ajax_referer('job_applications_nonce', 'nonce', false)) {
        error_log('Nonce verification failed');
        wp_send_json_error('Security check failed');
    }
    
    // Check permissions
    if (!current_user_can('manage_options')) {
        error_log('Permission denied for user: ' . get_current_user_id());
        wp_send_json_error('Permission denied');
    }
    
    $application_ids = isset($_POST['application_ids']) ? $_POST['application_ids'] : [];
    $interview_date = isset($_POST['interview_date']) ? sanitize_text_field($_POST['interview_date']) : '';
    $interview_location = isset($_POST['interview_location']) ? sanitize_text_field($_POST['interview_location']) : '';
    
    error_log('Application IDs: ' . print_r($application_ids, true));
    error_log('Interview Date: ' . $interview_date);
    error_log('Interview Location: ' . $interview_location);
    
    if (empty($application_ids) || empty($interview_date)) {
        error_log('Invalid input: application_ids or interview_date is empty');
        wp_send_json_error('Invalid input');
    }
    
    global $wpdb;
    $table = $wpdb->prefix . 'job_applications';
    
    // Convert datetime-local format to MySQL format
    $mysql_datetime = date('Y-m-d H:i:s', strtotime($interview_date));
    error_log('MySQL datetime: ' . $mysql_datetime);
    
    $success_count = 0;
    $errors = [];
    
    foreach ($application_ids as $application_id) {
        $application_id = intval($application_id);
        error_log('Processing application ID: ' . $application_id);
        
        // Update application record
        $result = $wpdb->update(
            $table,
            array(
                'status' => 'interview_scheduled',
                'interview_date' => $mysql_datetime,
                'interview_location' => $interview_location
            ),
            array('id' => $application_id),
            array('%s', '%s', '%s'),
            array('%d')
        );
        
        if ($result === false) {
            $error_msg = 'Failed to update application ID: ' . $application_id . ' - ' . $wpdb->last_error;
            error_log($error_msg);
            $errors[] = $error_msg;
        } else {
            $success_count++;
            error_log('Successfully updated application ID: ' . $application_id);
            
            // Get application details
            $application = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $application_id));
            
            if ($application) {
                $job_title = get_the_title($application->job_id);
                $formatted_date = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($mysql_datetime));
                
                // Create notification for applicant
                $message = sprintf(
                    'Your interview for %s has been scheduled on %s at %s.',
                    $job_title,
                    $formatted_date,
                    $interview_location
                );
                
                add_user_notification(
                    $application->user_id,
                    $message,
                    'interview_scheduled',
                    $application_id
                );
                
                // Check notification method preference
                $notification_method = get_option('job_applications_notification_method', 'email');
                
                // Send email notification if enabled
                $email_notifications = get_user_meta($application->user_id, 'email_notifications', true);
                if (($notification_method === 'email' || $notification_method === 'both') && $email_notifications !== '0') {
                    send_interview_scheduled_email($application_id);
                }
                
                // Send SMS notification if enabled
                if ($notification_method === 'sms' || $notification_method === 'both') {
                    send_interview_sms_notification($application, 'schedule', $formatted_date, $interview_location);
                }
            }
        }
    }
    
    if ($success_count > 0) {
        error_log('Successfully scheduled ' . $success_count . ' interviews');
        wp_send_json_success(array(
            'message' => "$success_count interview(s) scheduled successfully",
            'errors' => $errors,
            'new_date' => $formatted_date,
            'new_location' => $interview_location
        ));
    } else {
        error_log('Failed to schedule any interviews');
        wp_send_json_error('Failed to schedule interviews');
    }
}
add_action('wp_ajax_schedule_interview', 'ajax_schedule_interview');



