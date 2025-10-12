<?php 
get_header();
?>
    <section class="search-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- <div class="error-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div> -->
                    <div class="text-center text-success error-code">4<i class="error-icon fa-solid fa-circle-exclamation"></i>4</div>
                    <h1 class="text-center error-title">Oops! Page Not Found</h1>
                    <p class="error-message text-center">
                        The page you are looking for might have been removed, had its name changed,
                        or is temporarily unavailable. Please check the URL and try again.
                    </p>
                    <!-- <div class="search-box">
                        <div class="input-group mb-4">
                            <input type="text" class="form-control" placeholder="What are you looking for?"
                                aria-label="Search">
                            <button class="btn btn-success" type="button">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div> -->

                    <div class="text-center action-buttons">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-success">
                            <i class="fas fa-home"></i> Go to Homepage
                        </a>
                        <a href="#" class="btn btn-outline-success" id="goBackBtn">
                            <i class="fas fa-arrow-left"></i> Go Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
<script>
document.getElementById('goBackBtn').addEventListener('click', function(e) {
    e.preventDefault(); // stop link from refreshing page
    history.back();
});
</script>
<?php
get_footer();
?>