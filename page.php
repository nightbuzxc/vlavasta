<?php get_header(); ?>

<div class="container" style="padding: 60px 20px;">
    <h1 class="section-title" style="margin-bottom: 30px; text-align: center;">
        <?php the_title(); ?>
    </h1>

    <div class="page-content" style="max-width: 800px; margin: 0 auto; line-height: 1.6;">
        <?php 
        if ( have_posts() ) : 
            while ( have_posts() ) : the_post();
                the_content();
            endwhile;
        endif;
        ?>
    </div>
</div>

<?php get_footer(); ?>