<?php
/**
 * Template Name: Test Page
 *
 * @package Job_Listing_Theme
 */

get_header();
?>
    <section class="job-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-3">
                        
                        <div>
                            <h1 class="display-5 fw-bold mb-2"><?php the_title(); ?></h1>          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <section class="search-section" style="margin-bottom: 0px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="test-ajax">
                    <input type="text" id="username-input" placeholder="Username or Email">
                    <input type="password" id="password-input" placeholder="Password">
                    <button id="test-button">Check Login</button>
                    <div id="results"></div>
                    </div>

                </div>
            </div>
        </div>
    </section>


<?php get_footer(); ?>