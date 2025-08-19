<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till the main content
 *
 * @package Job_Listing_Theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<nav class="navbar navbar-expand-lg navbar-light bg-white rounded">
    <div class="container">
        <!-- Logo on the left -->
        <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>">
            <?php endif; ?>
        </a>
        
        <!-- Mobile toggle button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navbar content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <?php
            wp_nav_menu(array(
                'theme_location'  => 'primary',
                'menu_class'      => 'navbar-nav ms-auto mb-2 mb-lg-0',
                'container'       => false,
                'walker'          => new WP_Bootstrap_Navwalker(),
                'fallback_cb'     => false,
            ));
            ?>
            
            <?php if (is_user_logged_in()) : ?>
                <!-- Profile dropdown -->
                <div class="nav-item dropdown profile-dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown">
                        <?php echo get_avatar(get_current_user_id(), 32, '', 'Profile', array('class' => 'profile-img')); ?>
                        <span class="d-none d-md-inline ms-2"><?php echo wp_get_current_user()->display_name; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="<?php echo esc_url(get_permalink(get_page_by_path('user-profile'))); ?>">My Profile</a></li>
                        <li><a class="dropdown-item" href="<?php echo esc_url(get_permalink(get_page_by_path('applied-jobs'))); ?>">Applied Jobs</a></li>
                        <li><a class="dropdown-item" href="<?php echo esc_url(get_permalink(get_page_by_path('notifications'))); ?>">Notifications</a></li>
                        <li><a class="dropdown-item" href="<?php echo esc_url(get_permalink(get_page_by_path('settings'))); ?>">Settings</a></li>
                        <li><a class="dropdown-item text-danger" href="<?php echo wp_logout_url(home_url()); ?>">Logout</a></li>
                    </ul>
                </div>
            <?php else : ?>
                <!-- Show login link if not logged in -->
                <div class="nav-item">
                    <a class="nav-link" href="<?php echo wp_login_url(); ?>">Login</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>