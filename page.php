<?php

get_header(); 
if (have_posts()) :
    while (have_posts()) : the_post(); ?>
    <!-- Job Header -->
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

                    <?php the_content(); ?>

                </div>
            </div>
        </div>
    </section>
<?php endwhile;
else :
    echo '<p>No content found</p>';
endif; 

get_footer(); ?>