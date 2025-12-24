<?php get_header(); ?>

<main class="container single-product-page">

    <?php if (have_posts()) : while (have_posts()) : the_post(); 
        // Отримуємо дані ACF
        $price = get_field('price');
        $out_of_stock = get_field('out_of_stock');
        
        // Підготовка змінних для JS
        $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'large'); 
        if (!$thumb_url) { $thumb_url = ''; }
        
        $currency = function_exists('pll__') ? pll__('грн') : 'грн';
    ?>

    <div class="product-layout">
        
        <div class="product-gallery">
            <?php 
            // 1. Створюємо масив для всіх картинок
            $all_images = [];

            // 2. Беремо ГОЛОВНЕ фото (Featured Image) - воно буде першим
            $main_img = get_the_post_thumbnail_url(get_the_ID(), 'full');
            if ($main_img) {
                $all_images[] = $main_img;
            }

            // 3. ЦИКЛ: Автоматично перевіряємо поля photo_1 до photo_5
            for ($i = 1; $i <= 5; $i++) {
                $field_name = 'photo_' . $i;
                $img_url = get_field($field_name);
                if ($img_url) {
                    $all_images[] = $img_url;
                }
            }
            ?>

            <?php if (count($all_images) > 1) : ?>
                <div class="product-slider-container">
                    <div class="product-slider-track">
                        <?php foreach($all_images as $img_url): ?>
                            <div class="product-slide">
                                <img src="<?php echo esc_url($img_url); ?>" alt="Product Image">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button class="prod-arrow prod-prev"><i class="fa-solid fa-chevron-left"></i></button>
                    <button class="prod-arrow prod-next"><i class="fa-solid fa-chevron-right"></i></button>
                </div>

            <?php elseif (count($all_images) === 1) : ?>
                <div class="main-image">
                    <img src="<?php echo esc_url($all_images[0]); ?>" alt="Product Image">
                </div>
            <?php else: ?>
                <div class="placeholder-image" style="background:#eee; height:450px; width:100%; border-radius: 12px;"></div>
            <?php endif; ?>
        </div>

        <div class="product-details">
            <h1 class="product-title"><?php the_title(); ?></h1>
            
            <?php if($out_of_stock): ?>
                <div class="product-status out-of-stock">
                    <?php if(function_exists('pll_e')) { pll_e('Закінчився'); } else { echo 'Закінчився'; } ?>
                </div>
            <?php else: ?>
                
                <div class="product-price">
                    <?php echo esc_html($price); ?> <span><?php echo esc_html($currency); ?></span>
                </div>

                <div class="product-actions-single">
                    <button class="btn-buy big-btn"
                        data-id="<?php the_ID(); ?>"
                        data-title="<?php the_title_attribute(); ?>"
                        data-price-val="<?php echo esc_attr($price); ?>"
                        data-currency="<?php echo esc_attr($currency); ?>"
                        data-img="<?php echo esc_url($thumb_url); ?>"
                        data-link="<?php the_permalink(); ?>">
                        <?php if(function_exists('pll_e')) { pll_e('Додати в кошик'); } else { echo 'Додати в кошик'; } ?>
                    </button>

                    <button class="btn-fav big-fav" 
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
    </div>

    <div class="product-full-description">
        <h3><?php if(function_exists('pll_e')) { pll_e('Опис товару'); } else { echo 'Опис товару'; } ?></h3>
        <div class="description-content">
            <?php the_content(); ?>
        </div>
    </div>

    <?php endwhile; endif; ?>

</main>

<?php get_footer(); ?>