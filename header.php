<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('', true, 'right'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header>
    <div class="container header-inner">
        
        <a href="<?php echo function_exists('pll_home_url') ? pll_home_url() : home_url(); ?>" class="logo">
            vlavasta
        </a>
        
        <div class="header-controls">
            
            <div class="lang-dropdown">
                <?php
                if (function_exists('pll_the_languages')) {
                    $languages = pll_the_languages(array('raw' => 1));
                    $current_lang_slug = pll_current_language(); 
                    $current_lang_data = isset($languages[$current_lang_slug]) ? $languages[$current_lang_slug] : null;
                    
                    if ($current_lang_data) : ?>
                        <button class="lang-btn">
                            <img src="<?php echo esc_url($current_lang_data['flag']); ?>" alt="<?php echo esc_attr($current_lang_slug); ?>">
                            <span><?php echo strtoupper($current_lang_slug); ?></span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>

                        <ul class="lang-list">
                            <?php foreach ($languages as $slug => $lang): ?>
                                <?php if ($slug !== $current_lang_slug): // Не показувати поточну мову в списку ?>
                                <li>
                                    <a href="<?php echo esc_url($lang['url']); ?>">
                                        <img src="<?php echo esc_url($lang['flag']); ?>" alt="<?php echo esc_attr($lang['name']); ?>"> 
                                        <?php echo esc_html($lang['name']); ?>
                                    </a>
                                </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; 
                } ?>
            </div>

            <div class="header-icons">

                <a href="https://www.instagram.com/vlavasta_nk/" class="icon-link" target="_blank">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                
                <?php 
                    $cart_count = WC()->cart->get_cart_contents_count(); 
                    $cart_url = wc_get_cart_url();
                ?>
                <a href="<?php echo esc_url($cart_url); ?>" class="cart-icon-wrapper" id="cartBtn">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-count visible"><?php echo esc_html($cart_count); ?></span>
                    <?php else: ?>
                        <span class="cart-count">0</span>
                    <?php endif; ?>
                </a>

                <div class="user-menu-container">
                    <div class="user-icon-trigger" id="userMenuBtn">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    
                    <div class="user-dropdown-menu" id="userMenuDropdown">
                        <?php if (is_user_logged_in()): 
                            $current_user = wp_get_current_user();
                            $my_account_url = get_permalink( get_option('woocommerce_myaccount_page_id') );
                        ?>
                            <div class="user-drop-header">
                                Привіт, <strong><?php echo esc_html( !empty($current_user->first_name) ? $current_user->first_name : $current_user->display_name ); ?></strong>
                            </div>
                            
                            <a href="<?php echo esc_url($my_account_url); ?>" class="user-drop-link">
                                <span class="icon-wrap"><i class="fa-solid fa-box-open"></i></span>
                                <span>Мій кабінет</span>
                            </a>
                            
                            <a href="#" class="user-drop-link js-open-fav-modal">
                                <span class="icon-wrap"><i class="fa-regular fa-heart"></i></span>
                                <span>Вподобане</span>
                                <span class="fav-count-badge">0</span> 
                            </a>

                            <div class="user-drop-divider"></div>

                            <a href="<?php echo wp_logout_url(home_url()); ?>" class="user-drop-link logout">
                                <span class="icon-wrap"><i class="fa-solid fa-arrow-right-from-bracket"></i></span>
                                <span>Вийти</span>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="user-drop-link">
                                <span class="icon-wrap"><i class="fa-regular fa-user"></i></span>
                                <span>Вхід / Реєстрація</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

            </div> 
        </div> 
    </div> 
</header>