<?php
/**
 * Empty cart page custom template
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="cart-empty-page" style="text-align: center; padding: 10px 0;">
    
    <p class="cart-empty-message" style="font-size: 16px; margin-bottom: 20px; font-weight: 700; color: #333;">
        <?php echo vlavasta_t( 'Your cart is currently empty!' ); ?>
    </p>

    <div class="new-in-store-section" style="margin-top: 10px; border-top: 1px solid #eee; padding-top: 20px;">
        <h3 class="section-title" style="margin-bottom: 20px; font-size: 18px;">
            <?php echo vlavasta_t( 'New in store' ); ?>
        </h3>
        
        <div class="products-grid">
            <?php
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => 4,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'post_status'    => 'publish',
            );
            $loop = new WP_Query($args);
            
            if ($loop->have_posts()) :
                while ($loop->have_posts()) : $loop->the_post(); 
                    global $product;
                    ?>
                    
                    <div class="product-card">
                        <div class="product-img">
                            <a href="<?php the_permalink(); ?>">
                                <?php 
                                if ($product->get_image_id()) {
                                    echo $product->get_image('woocommerce_thumbnail'); 
                                } else {
                                    echo '<img src="' . wc_placeholder_img_src() . '" alt="Placeholder">';
                                }
                                ?>
                            </a>
                        </div>
                        
                        <div class="product-details-wrap" style="padding: 10px;">
                            <div class="product-title" style="margin-bottom: 5px;">
                                <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit; font-size: 14px;">
                                    <?php the_title(); ?>
                                </a>
                            </div>
                            
                            <div class="price" style="font-size: 16px; margin-bottom: 10px;">
                                <?php echo $product->get_price_html(); ?>
                            </div>

                            <div class="product-actions" style="display: flex; gap: 8px; align-items: stretch;">
                                
                                <a href="?add-to-cart=<?php echo $product->get_id(); ?>" class="btn-buy" style="text-decoration: none; flex-grow: 1; padding: 8px; font-size: 13px;">
                                    <?php echo vlavasta_t('Купити'); ?>
                                </a>

                                <button class="btn-fav" 
                                    style="width: 40px; height: auto; padding: 0; display: flex; align-items: center; justify-content: center; border-radius: 8px;"
                                    data-id="<?php echo $product->get_id(); ?>"
                                    data-title="<?php echo esc_attr($product->get_name()); ?>"
                                    data-price="<?php echo esc_attr($product->get_price()); ?>"
                                    data-img="<?php echo esc_url(wp_get_attachment_image_url($product->get_image_id(), 'thumbnail')); ?>"
                                    data-link="<?php echo esc_url(get_permalink()); ?>">
                                    <i class="fa-regular fa-heart"></i>
                                </button>

                            </div> 
                        </div>
                    </div>

                <?php endwhile;
            endif;
            wp_reset_postdata();
            ?>
        </div>
    </div>
    
</div>