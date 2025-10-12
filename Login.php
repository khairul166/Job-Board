<?php
/**
 * Template Name: Login
 *
 * @package Job_Listing_Theme
 */
// Check if user is already logged in
if (is_user_logged_in()) {
    wp_redirect(home_url('/user-profile')); // Redirect to user profile page if already logged in
    exit;
}
get_header();



// Get redirect URL if provided
$redirect_to = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : home_url('/user-profile');
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="login-container card shadow-sm">
                <div class="card-body">
                    <div class="login-header text-center mb-4">
                        <i class="fa-solid fa-user-tie fa-3x text-success mb-3"></i>
                        <h2 class="text-success"><?php _e('Welcome Back', 'job-listing'); ?></h2>
                        <p class="text-muted"><?php _e('Please enter your credentials', 'job-listing'); ?></p>
                    </div>
                    
                    <!-- Display success message if password was reset -->
                    <?php if (isset($_GET['reset']) && $_GET['reset'] === 'success') : ?>
                        <div class="alert alert-success" role="alert">
                            <?php _e('Your password has been reset successfully. Please log in with your new password.', 'job-listing'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Display logged out message -->
                    <?php if (isset($_GET['logged_out']) && $_GET['logged_out'] === 'true') : ?>
                        <div class="alert alert-success" role="alert">
                            <?php _e('You have been successfully logged out.', 'job-listing'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Login Form -->
                    <form id="ajax-login-form" method="post">
                        <!-- Rest of your login form remains the same -->
                        <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
                        <input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to); ?>">
                        
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="user_login" name="log" placeholder="<?php _e('Username or Email', 'job-listing'); ?>" required>
                            <label for="user_login"><?php _e('Username or Email', 'job-listing'); ?></label>
                        </div>
                        
    
    <div class="form-floating mb-3">
        <input type="password" class="form-control password-input" id="user_password" name="pwd" placeholder="<?php _e('Password', 'job-listing'); ?>" required>
        <label for="user_password"><?php _e('Password', 'job-listing'); ?></label>
        <button type="button" class="btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2 p-0 border-0 bg-transparent" id="toggleLoginPassword" tabindex="-1">
            <i class="fas fa-eye text-muted"></i>
        </button>
    </div>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="rememberme" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">
                                    <?php _e('Remember me', 'job-listing'); ?>
                                </label>
                            </div>
                            <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="text-success-link"><?php _e('Forgot password?', 'job-listing'); ?></a>
                        </div>
                        
                        <button type="submit" class="btn btn-login btn-success w-100 py-2 mb-4" id="login-submit">
                            <span class="button-text"><?php _e('Login', 'job-listing'); ?></span>
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <span class="login-text d-none" id="login-loading-text"><?php _e('Logging in...', 'job-listing'); ?></span>
                        </button>
                        
                        <div id="login-message" class="alert d-none" role="alert"></div>
                    </form>
                    
                    <div class="additional-links text-center">
                        <p class="text-muted"><?php _e('Don\'t have an account?', 'job-listing'); ?> 
                        <a href="<?php echo esc_url(wp_registration_url()); ?>" class="text-success-link"><?php _e('Sign up', 'job-listing'); ?></a></p>
                        <hr>
                        <p class="text-muted"><?php _e('Or login with', 'job-listing'); ?></p>
                        
                        <!-- Social Login Buttons -->
                        <div class="social-login-container">
                            <?php if (function_exists('nsl_get_login_buttons')) : ?>
                                <!-- Nextend Social Login Integration -->
                                <div class="d-flex justify-content-center gap-3 mb-3">
                                    <?php echo do_shortcode('[nextend_social_login]'); ?>
                                </div>
                            <?php else : ?>
                                <!-- Default Social Login Buttons (will be replaced by actual social login) -->
                                <div class="d-flex justify-content-center gap-3">
                                    <button class="btn btn-outline-success google-login" data-provider="google">
                                        <i class="fa-brands fa-google"></i>
                                    </button>
                                    <button class="btn btn-outline-success facebook-login" data-provider="facebook">
                                        <i class="fa-brands fa-facebook"></i>
                                    </button>
                                    <button class="btn btn-outline-success linkedin-login" data-provider="linkedin">
                                        <i class="fa-brands fa-linkedin"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fallback for ajax_login_object -->
<script type="text/javascript">
    if (typeof ajax_login_object === 'undefined') {
        var ajax_login_object = {
            ajaxurl: '<?php echo admin_url('admin-ajax.php'); ?>',
            redirecturl: '<?php echo home_url('/user-profile'); ?>',
            loadingmessage: '<?php _e('Sending user info, please wait...', 'job-listing'); ?>'
        };
    }
</script>
<?php get_footer(); ?>