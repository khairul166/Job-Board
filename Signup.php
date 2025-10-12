<?php

/**
 * Template Name: Signup
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
$redirect_to = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : home_url('/user-profile');
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="signup-container my-md-5 my-3 card shadow-sm">
                <div class="card-body">
                    <div class="signup-header text-center mb-4">
                        <i class="fa-solid fa-user-plus fa-3x text-success mb-3"></i>
                        <h2 class="text-success"><?php _e('Create Account', 'job-listing'); ?></h2>
                        <p class="text-muted"><?php _e('Join us today!', 'job-listing'); ?></p>
                    </div>

                    <!-- Signup Form -->
                    <form id="ajax-signup-form" method="post">
                        <?php wp_nonce_field('ajax-signup-nonce', 'security'); ?>
                        <input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to); ?>">

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingUsername" name="username" placeholder="<?php _e('Username', 'job-listing'); ?>" required>
                            <label for="floatingUsername"><?php _e('Username', 'job-listing'); ?></label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="floatingEmail" name="email" placeholder="<?php _e('Email Address', 'job-listing'); ?>" required>
                            <label for="floatingEmail"><?php _e('Email Address', 'job-listing'); ?></label>
                        </div>


                        <div class="form-floating mb-3">
                            <input type="password" class="form-control password-input" id="floatingPassword" name="password" placeholder="<?php _e('Password', 'job-listing'); ?>" required>
                            <label for="floatingPassword"><?php _e('Password', 'job-listing'); ?></label>
                            <button type="button" class="btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2 p-0 border-0 bg-transparent" id="toggleSignupPassword" tabindex="-1">
                                <i class="fas fa-eye text-muted"></i>
                            </button>
                        </div>
                        <div class="form-floating mb-3">
                            <div class="password-strength mt-2">
                                <div class="password-strength-bar" id="passwordStrengthBar"></div>
                            </div>
                            <small class="text-muted"><?php _e('At least 8 characters with a mix of letters, numbers and symbols', 'job-listing'); ?></small>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control password-input" id="floatingConfirmPassword" name="confirm_password" placeholder="<?php _e('Confirm Password', 'job-listing'); ?>" required>
                            <label for="floatingConfirmPassword"><?php _e('Confirm Password', 'job-listing'); ?></label>
                            <button type="button" class="btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2 p-0 border-0 bg-transparent" id="toggleConfirmPassword" tabindex="-1">
                                <i class="fas fa-eye text-muted"></i>
                            </button>
                            <div id="passwordMatch" class="text-danger small"></div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="terms" id="termsCheck" required>
                            <label class="form-check-label" for="termsCheck">
                                <?php _e('I agree to the', 'job-listing'); ?> <a href="<?php echo esc_url(get_permalink(get_page_by_path('terms-conditions'))); ?>" class="text-success text-decoration-none"><?php _e('Terms and Conditions', 'job-listing'); ?></a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-signup btn-success w-100 py-2 mb-4" id="signup-submit">
                            <span class="button-text"><?php _e('Sign Up', 'job-listing'); ?></span>
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <span class="signup-text d-none" id="loading-text"><?php _e('Signing Up...', 'job-listing'); ?></span>
                        </button>

                        <div id="signup-message" class="alert d-none" role="alert"></div>
                    </form>

                    <div class="additional-links text-center">
                        <p class="text-muted"><?php _e('Already have an account?', 'job-listing'); ?>
                            <a href="<?php echo esc_url(wp_login_url()); ?>" class="text-success text-decoration-none"><?php _e('Sign In', 'job-listing'); ?></a>
                        </p>
                        <hr>
                        <p class="text-muted"><?php _e('Or sign up with', 'job-listing'); ?></p>
                        <div class="d-flex justify-content-center gap-3">
                            <?php if (function_exists('nsl_get_login_buttons')) : ?>
                                <!-- Nextend Social Login Integration -->
                                <?php echo do_shortcode('[nextend_social_login]'); ?>
                            <?php else : ?>
                                <!-- Default Social Login Buttons -->
                                <button class="btn btn-outline-success google-signup" data-provider="google">
                                    <i class="fa-brands fa-google"></i>
                                </button>
                                <button class="btn btn-outline-success facebook-signup" data-provider="facebook">
                                    <i class="fa-brands fa-facebook"></i>
                                </button>
                                <button class="btn btn-outline-success linkedin-signup" data-provider="linkedin">
                                    <i class="fa-brands fa-linkedin"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fallback for ajax_signup_object -->
<script type="text/javascript">
    if (typeof ajax_signup_object === 'undefined') {
        var ajax_signup_object = {
            ajaxurl: '<?php echo admin_url('admin-ajax.php'); ?>',
            redirecturl: '<?php echo home_url('/user-profile'); ?>',
            loadingmessage: '<?php _e('Creating your account, please wait...', 'job-listing'); ?>'
        };
    }
</script>
<?php get_footer(); ?>