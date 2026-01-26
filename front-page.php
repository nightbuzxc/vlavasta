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
                <div class="slide"><img src="<?php echo esc_url($banner1); ?>" alt="Banner 1"></div>
                <?php if($banner2): ?><div class="slide"><img src="<?php echo esc_url($banner2); ?>" alt="Banner 2"></div><?php endif; ?>
                <?php if($banner3): ?><div class="slide"><img src="<?php echo esc_url($banner3); ?>" alt="Banner 3"></div><?php endif; ?>
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
                'post_type' => 'product', 
                'posts_per_page' => 9,
            );
            $loop = new WP_Query($args);
            
            
            if ($loop->have_posts()) :
    while ($loop->have_posts()) : $loop->the_post(); 
        global $product;
        
        // 1. Отримуємо ID всіх картинок (Головна + Галерея)
        $attachment_ids = $product->get_gallery_image_ids();
        $main_image_id = $product->get_image_id();
        
        // Додаємо головне фото на початок масиву
        if ($main_image_id) {
            array_unshift($attachment_ids, $main_image_id);
        }
        
        // Видаляємо дублікати (якщо головне фото раптом є і в галереї)
        $attachment_ids = array_unique($attachment_ids);
        
        // Якщо фото взагалі немає, ставимо заглушку
        if (empty($attachment_ids)) {
            $attachment_ids[] = 'placeholder'; 
        }
        ?>

        <div class="product-card">
    
    <div class="product-img">
        <a href="<?php the_permalink(); ?>">
            <?php 
            if ($product->get_image_id()) {
                // Виводимо одне фото
                echo $product->get_image('woocommerce_thumbnail'); 
            } else {
                echo '<img src="' . wc_placeholder_img_src() . '" alt="Placeholder">';
            }
            ?>
        </a>
    </div>
    
    <div class="product-details-wrap" style="padding: 15px;">
        <div class="product-title">
            <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit;">
                <?php the_title(); ?>
            </a>
        </div>
        
        <?php if(!$product->is_in_stock()): ?>
            <div class="status" style="color:red; font-weight:bold;">
                <?php if(function_exists('pll_e')) { pll_e('Закінчився'); } else { echo 'Закінчився'; } ?>
            </div>
        <?php else: ?>
            
            <div class="price">
                <?php echo $product->get_price_html(); ?>
            </div>

            <div class="product-actions">
                <a href="?add-to-cart=<?php echo $product->get_id(); ?>" class="btn-buy" style="text-decoration: none;">
                    <?php if(function_exists('pll_e')) { pll_e('Купити'); } else { echo 'Купити'; } ?>
                </a>
                
                <button class="btn-fav" 
                    data-id="<?php echo $product->get_id(); ?>"
                    data-title="<?php echo esc_attr($product->get_name()); ?>"
                    data-price="<?php echo esc_attr($product->get_price()); ?>"
                    data-img="<?php echo esc_url(wp_get_attachment_image_url($product->get_image_id(), 'thumbnail')); ?>"
                    data-link="<?php echo esc_url(get_permalink()); ?>">
                    <i class="fa-regular fa-heart"></i>
                </button>
            </div> 
        <?php endif; ?>
    </div>
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