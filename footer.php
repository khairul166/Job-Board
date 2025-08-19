<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package Job_Listing_Theme
 */
?>
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
<div class="col-md-4 mb-4 mb-md-0">
    <h5 class="mb-3"><?php echo esc_html(get_bloginfo('name')); ?></h5>
    <p><?php echo esc_html(get_bloginfo('description')); ?></p>
    <div class="social-icons">
        <?php
        // Get social media links from Customizer
        $facebook_url = get_theme_mod('facebook_url', '#');
        $twitter_url = get_theme_mod('twitter_url', '#');
        $linkedin_url = get_theme_mod('linkedin_url', '#');
        $instagram_url = get_theme_mod('instagram_url', '#');
        $youtube_url = get_theme_mod('youtube_url', '#');
$pinterest_url = get_theme_mod('pinterest_url', '#');
        
        // Only show the icon if a URL is set (not the default '#')
        if ($facebook_url !== '#') : ?>
            <a href="<?php echo esc_url($facebook_url); ?>" class="text-white me-2" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-facebook-f"></i>
            </a>
        <?php endif; ?>
        
        <?php if ($twitter_url !== '#') : ?>
            <a href="<?php echo esc_url($twitter_url); ?>" class="text-white me-2" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-twitter"></i>
            </a>
        <?php endif; ?>
        
        <?php if ($linkedin_url !== '#') : ?>
            <a href="<?php echo esc_url($linkedin_url); ?>" class="text-white me-2" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-linkedin-in"></i>
            </a>
        <?php endif; ?>
        
        <?php if ($instagram_url !== '#') : ?>
            <a href="<?php echo esc_url($instagram_url); ?>" class="text-white me-2" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-instagram"></i>
            </a>
        <?php endif; 
if ($youtube_url !== '#') : ?>
    <a href="<?php echo esc_url($youtube_url); ?>" class="text-white me-2" target="_blank" rel="noopener noreferrer">
        <i class="fab fa-youtube"></i>
    </a>
<?php endif; 

if ($pinterest_url !== '#') : ?>
    <a href="<?php echo esc_url($pinterest_url); ?>" class="text-white me-2" target="_blank" rel="noopener noreferrer">
        <i class="fab fa-pinterest"></i>
    </a>
<?php endif; ?>
    </div>
</div>
                                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="mb-3"><?php _e('For Job Seekers', 'job-listing'); ?></h5>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer_job_seekers',
                        'menu_class'     => 'list-unstyled',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'depth'          => 1,
                        'walker'         => new Footer_Menu_Walker_Simple(),
                    ));
                    ?>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="mb-3"><?php _e('For Employers', 'job-listing'); ?></h5>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer_employers',
                        'menu_class'     => 'list-unstyled',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'depth'          => 1,
                        'walker'         => new Footer_Menu_Walker_Simple(),
                    ));
                    ?>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3"><?php _e('Stay Updated', 'job-listing'); ?></h5>
                    <p><?php _e('Subscribe to our newsletter for the latest jobs and career tips.', 'job-listing'); ?></p>
                    <?php echo do_shortcode('[newsletter_form]'); ?>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small mb-0">&copy; <?php echo date('Y'); ?> <?php echo esc_html(get_bloginfo('name')); ?>. <?php _e('All rights reserved.', 'job-listing'); ?></p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer_bottom',
                        'menu_class'     => 'list-inline mb-0',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'depth'          => 1,
                        'walker'        => new Footer_Menu_Walker(),
                    ));
                    ?>
                </div>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>