<?php get_header(); ?>

<main class="container">
    
    <section class="hero">
        <?php 
        $banner1 = get_field('banner_1');
        $banner2 = get_field('banner_2');
        $banner3 = get_field('banner_3');
        ?>

        <?php if($banner1): ?>
        <div class="slider-container">
            <div class="slider-track">
                <div class="slide">
                    <img src="<?php echo esc_url($banner1); ?>" alt="Banner 1">
                </div>
                
                <?php if($banner2): ?>
                <div class="slide">
                    <img src="<?php echo esc_url($banner2); ?>" alt="Banner 2">
                </div>
                <?php endif; ?>

                <?php if($banner3): ?>
                <div class="slide">
                    <img src="<?php echo esc_url($banner3); ?>" alt="Banner 3">
                </div>
                <?php endif; ?>
            </div>

            <button class="slider-arrow prev-btn"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="slider-arrow next-btn"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
        <?php endif; ?>
    </section>

    <section class="products">
        <h2 class="section-title">
            <?php if(function_exists('pll_e')) { pll_e('Товари'); } else { echo 'Товари'; } ?>
        </h2>
        
        <div class="products-grid">
            <?php
            $args = array(
                'post_type' => 'product_book',
                'posts_per_page' => 9
            );
            $loop = new WP_Query($args);
            
            if ($loop->have_posts()) :
                while ($loop->have_posts()) : $loop->the_post(); 
                    // Отримуємо дані
                    $price = get_field('price'); 
                    $out_of_stock = get_field('out_of_stock');
                    
                    // Підготовка змінних для кнопок (щоб не дублювати код)
                    $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); 
                    if (!$thumb_url) { $thumb_url = ''; } // Якщо немає фото
                    
                    $currency = function_exists('pll__') ? pll__('грн') : 'грн';
                    ?>

                    <div class="product-card">
                        <div class="product-img">
                            <a href="<?php the_permalink(); ?>">
                                <?php 
                                if (has_post_thumbnail()) {
                                    the_post_thumbnail('large'); 
                                } else {
                                    echo '<div style="background:#eee; height:100%; width:100%;"></div>';
                                }
                                ?>
                            </a>
                        </div>
                        
                        <div class="product-title">
                            <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit;">
                                <?php the_title(); ?>
                            </a>
                        </div>
                        
                        <?php if($out_of_stock): ?>
                            <div class="status" style="color:red;">
                                <?php if(function_exists('pll_e')) { pll_e('Закінчився'); } else { echo 'Закінчився'; } ?>
                            </div>
                        <?php else: ?>
                            
                            <div class="price">
                                <?php echo esc_html($price); ?> <?php echo esc_html($currency); ?>
                            </div>

                            <div class="product-actions">
                                
                                <button class="btn-buy"
                                    data-id="<?php the_ID(); ?>"
                                    data-title="<?php the_title_attribute(); ?>"
                                    data-price-val="<?php echo esc_attr($price); ?>"
                                    data-currency="<?php echo esc_attr($currency); ?>"
                                    data-img="<?php echo esc_url($thumb_url); ?>"
                                    data-link="<?php the_permalink(); ?>">
                                    <?php if(function_exists('pll_e')) { pll_e('Купити'); } else { echo 'Купити'; } ?>
                                </button>
                                
                                <button class="btn-fav" 
                                    data-id="<?php the_ID(); ?>" 
                                    data-title="<?php the_title_attribute(); ?>" 
                                    data-price="<?php echo esc_attr($price . ' ' . $currency); ?>" 
                                    data-img="<?php echo esc_url($thumb_url); ?>" 
                                    data-link="<?php the_permalink(); ?>">
                                    <i class="fa-regular fa-heart"></i>
                                </button>

                            </div> 
                        <?php endif; ?>
                    </div>

                <?php endwhile;
            else: ?>
                <p><?php if(function_exists('pll_e')) { pll_e('Товарів поки немає'); } ?></p>
            <?php endif;
            wp_reset_postdata();
            ?>
        </div>
    </section>

</main>

<?php get_footer(); ?>