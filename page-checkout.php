<?php 
get_header(); 
?>

<div class="container" style="padding: 60px 20px;">
    <h1 class="section-title" style="margin-bottom: 30px; text-align: center;">
        <?php the_title(); ?>
    </h1>

    <div class="checkout-wrapper" style="max-width: 1000px; margin: 0 auto;">
        <?php 
        while ( have_posts() ) : the_post();
            the_content();
        endwhile; 
        ?>
    </div>
</div>

<?php get_footer(); ?>