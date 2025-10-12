<?php
/**
 * Template Name: Forgot Password
 *
 * @package Job_Listing_Theme
 */
get_header();

// Check if user is already logged in
if (is_user_logged_in()) {
    wp_redirect(home_url('/user-profile'));
    exit;
}

// Get redirect URL if provided
$redirect_to = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : home_url('/login');
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="forgot-password-container card shadow-sm">
                <div class="card-body p-5">
                    <div class="forgot-password-header text-center mb-4">
                        <i class="bi bi-key-fill fa-3x text-success mb-3"></i>
                        <h2 class="text-success"><?php _e('Forgot Password', 'job-listing'); ?></h2>
                        <p class="text-muted"><?php _e('Enter your email address and we\'ll send you a link to reset your password.', 'job-listing'); ?></p>
                    </div>
                    
                    <!-- Display error messages -->
                    <?php if (isset($_GET['reset']) && $_GET['reset'] === 'invalidkey') : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php _e('Invalid or expired password reset link. Please request a new one.', 'job-listing'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Forgot Password Form -->
                    <form id="ajax-forgot-password-form" method="post">
                        <!-- Rest of the form remains the same -->
                        <?php wp_nonce_field('ajax-forgot-password-nonce', 'security'); ?>
                        <input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to); ?>">
                        
                        <div class="form-floating mb-4">
                            <input type="email" class="form-control" id="floatingEmail" name="email" placeholder="<?php _e('Email Address', 'job-listing'); ?>" required>
                            <label for="floatingEmail"><?php _e('Email Address', 'job-listing'); ?></label>
                        </div>
                        
                        <button type="submit" class="btn btn-reset-password btn-success w-100 py-2 mb-4" id="reset-password-submit">
                            <span class="button-text"><?php _e('Reset Password', 'job-listing'); ?></span>
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                        
                        <div id="forgot-password-message" class="alert d-none" role="alert"></div>
                    </form>
                    
                    <div class="additional-links text-center">
                        <p class="text-muted"><?php _e('Remember your password?', 'job-listing'); ?> 
                        <a href="<?php echo esc_url(wp_login_url()); ?>" class="text-success text-decoration-none"><?php _e('Log In', 'job-listing'); ?></a></p>
                        
                        <p class="text-muted"><?php _e('Don\'t have an account?', 'job-listing'); ?> 
                        <a href="<?php echo esc_url(wp_registration_url()); ?>" class="text-success text-decoration-none"><?php _e('Sign Up', 'job-listing'); ?></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fallback for ajax_forgot_password_object -->
<script type="text/javascript">
    if (typeof ajax_forgot_password_object === 'undefined') {
        var ajax_forgot_password_object = {
            ajaxurl: '<?php echo esc_js(admin_url('admin-ajax.php')); ?>',
            redirecturl: '<?php echo esc_js(home_url('/login')); ?>',
            loadingmessage: '<?php echo esc_js(__('Sending password reset email, please wait...', 'job-listing')); ?>',
            email_not_found: '<?php echo esc_js(__('No user found with that email address.', 'job-listing')); ?>',
            email_sent: '<?php echo esc_js(__('Password reset email has been sent. Please check your inbox.', 'job-listing')); ?>',
            error_occurred: '<?php echo esc_js(__('An error occurred. Please try again.', 'job-listing')); ?>'
        };
    }
</script>
<?php get_footer(); ?>