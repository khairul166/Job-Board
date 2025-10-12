<?php

/**
 * Template Name: Password Reset
 *
 * @package Job_Listing_Theme
 */
get_header();

// Check if user is already logged in
if (is_user_logged_in()) {
    wp_redirect(home_url('/user-profile'));
    exit;
}

// Get the key and login from the URL
$key = isset($_GET['key']) ? $_GET['key'] : '';
$login = isset($_GET['login']) ? $_GET['login'] : '';

// Verify the key
$user = check_password_reset_key($key, $login);

if (is_wp_error($user)) {
   // echo '<div class="container py-5"><div class="row justify-content-center"><div class="col-md-6 col-lg-5"><div class="alert alert-danger" role="alert">' . __('Invalid or expired password reset link. Please request a new one.', 'job-listing') . '</div><div class="text-center"><a href="' . esc_url(home_url('/forgot-password')) . '" class="btn btn-primary">' . __('Go to Forgot Password', 'job-listing') . '</a></div></div></div></div>';?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="password-reset-container card shadow-sm">
                    <div class="card-body">
                        <div class="alert alert-danger" role="alert">
                            <?php _e('Invalid or expired password reset link. Please request a new one.', 'job-listing'); ?>
                        </div>
                        <div class="text-center"><a href=" <?php echo esc_url(home_url('/forgot-password')) ?>" class="btn btn-success">Go to Forgot Password</a></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
} else {
    // Set redirect URL to login page instead of user profile
    $redirect_to = home_url('/login');
?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="password-reset-container card shadow-sm">
                    <div class="card-body">
                        <div class="password-reset-header text-center mb-4">
                            <i class="bi bi-shield-lock-fill fa-3x text-success mb-3"></i>
                            <h2 class="text-success"><?php _e('Reset Password', 'job-listing'); ?></h2>
                            <p class="text-muted"><?php _e('Enter your new password below.', 'job-listing'); ?></p>
                        </div>

                        <!-- Password Reset Form -->
                        <form id="ajax-password-reset-form" method="post">
                            <?php wp_nonce_field('ajax-password-reset-nonce', 'security'); ?>
                            <input type="hidden" name="key" value="<?php echo esc_attr($key); ?>">
                            <input type="hidden" name="login" value="<?php echo esc_attr($login); ?>">
                            <input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to); ?>">

                            <!-- Rest of your form remains the same -->

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="<?php _e('New Password', 'job-listing'); ?>" required>
                                <label for="floatingPassword"><?php _e('New Password', 'job-listing'); ?></label>
                                <div class="password-strength mt-2">
                                    <div class="password-strength-bar" id="passwordStrengthBar"></div>
                                </div>
                                <small class="text-muted"><?php _e('At least 8 characters with a mix of letters, numbers and symbols', 'job-listing'); ?></small>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="floatingConfirmPassword" name="confirm_password" placeholder="<?php _e('Confirm New Password', 'job-listing'); ?>" required>
                                <label for="floatingConfirmPassword"><?php _e('Confirm New Password', 'job-listing'); ?></label>
                                <div id="passwordMatch" class="text-danger small"></div>
                            </div>

                            <button type="submit" class="btn btn-reset-password btn-success w-100 py-2 mb-4" id="password-reset-submit">
                                <span class="button-text"><?php _e('Reset Password', 'job-listing'); ?></span>
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>

                            <div id="password-reset-message" class="alert d-none" role="alert"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Fallback for ajax_password_reset_object -->
<script type="text/javascript">
    if (typeof ajax_password_reset_object === 'undefined') {
        var ajax_password_reset_object = {
            ajaxurl: '<?php echo esc_js(admin_url('admin-ajax.php')); ?>',
            redirecturl: '<?php echo esc_js(home_url('/login')); ?>',
            loadingmessage: '<?php echo esc_js(__('Resetting your password, please wait...', 'job-listing')); ?>',
            passwords_do_not_match: '<?php echo esc_js(__('Passwords do not match.', 'job-listing')); ?>',
            password_too_short: '<?php echo esc_js(__('Password must be at least 8 characters long.', 'job-listing')); ?>',
            password_reset_success: '<?php echo esc_js(__('Your password has been reset successfully. Please log in with your new password.', 'job-listing')); ?>',
            error_occurred: '<?php echo esc_js(__('An error occurred. Please try again.', 'job-listing')); ?>'
        };
    }
</script>

<?php
} // This closes the else block
get_footer(); 
?>