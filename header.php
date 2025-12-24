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
                                <li>
                                    <a href="<?php echo esc_url($lang['url']); ?>">
                                        <img src="<?php echo esc_url($lang['flag']); ?>" alt="<?php echo esc_attr($lang['name']); ?>"> 
                                        <?php echo esc_html($lang['name']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; 
                } ?>
            </div>

            <div class="header-icons">

                <a href="https://www.instagram.com/vlavasta_nk/" class="icon-link">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                
                <div class="cart-icon-wrapper" id="cartBtn">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span class="cart-count">0</span>
                    
                    <div class="cart-dropdown" id="cartDropdown">
                        <div class="cart-title">
                            <?php if(function_exists('pll_e')) { pll_e('Кошик'); } else { echo 'Кошик'; } ?>
                        </div>
                        <ul class="cart-list"></ul>
                        <div class="cart-empty-msg">
                            <?php if(function_exists('pll_e')) { pll_e('Кошик порожній'); } else { echo 'Кошик порожній'; } ?>
                        </div>
                        <div class="cart-footer">
                            <div class="cart-divider"></div>
                            <div class="total-row">
                                <span><?php if(function_exists('pll_e')) { pll_e('Разом'); } else { echo 'Разом'; } ?>:</span>
                                <span class="total-price">0 грн</span>
                            </div>
                            <button class="btn-checkout">
                                <?php if(function_exists('pll_e')) { pll_e('Оформити'); } else { echo 'Оформити'; } ?>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="user-menu-container">
                    <div class="user-icon-trigger" id="userMenuBtn">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    
                    <div class="user-dropdown-menu" id="userMenuDropdown">
                        <?php if (is_user_logged_in()): 
                            $current_user = wp_get_current_user();
                        ?>
                            <div class="user-drop-header">
                                <?php if(function_exists('pll_e')) { pll_e('Привіт'); } else { echo 'Привіт'; } ?>, <?php echo esc_html($current_user->display_name); ?>
                            </div>
                            
                            <a href="<?php echo home_url('/' . (function_exists('pll__') ? pll__('my-account') : 'my-account')); ?>" class="user-drop-link">
                                <span class="icon-wrap"><i class="fa-solid fa-box-open"></i></span>
                                <span><?php if(function_exists('pll_e')) { pll_e('Мій кабінет'); } else { echo 'Мій кабінет'; } ?></span>
                            </a>
                            
                            <a href="#" class="user-drop-link open-fav-from-menu">
                                <span class="icon-wrap"><i class="fa-regular fa-heart"></i></span>
                                <span><?php if(function_exists('pll_e')) { pll_e('Вподобане'); } else { echo 'Вподобане'; } ?></span>
                                <span class="fav-count-inline"></span>
                            </a>

                            <div style="border-top: 1px solid #eee; margin: 5px 0;"></div>

                            <a href="<?php echo home_url('?action=logout'); ?>" class="user-drop-link logout">
                                <span class="icon-wrap"><i class="fa-solid fa-arrow-right-from-bracket"></i></span>
                                <span><?php if(function_exists('pll_e')) { pll_e('Вийти'); } else { echo 'Вийти'; } ?></span>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo home_url('/' . (function_exists('pll__') ? pll__('my-account') : 'my-account')); ?>" class="user-drop-link">
                                <span class="icon-wrap"><i class="fa-regular fa-user"></i></span>
                                <span><?php if(function_exists('pll_e')) { pll_e('Вхід / Реєстрація'); } else { echo 'Вхід / Реєстрація'; } ?></span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="favorites-dropdown" id="favDropdown">
                        <div class="fav-header-nav">
                            <span class="back-to-menu-btn">
                                <i class="fa-solid fa-chevron-left"></i> 
                                <?php if(function_exists('pll_e')) { pll_e('Назад'); } else { echo 'Назад'; } ?>
                            </span>
                            <span class="fav-header-title"><?php if(function_exists('pll_e')) { pll_e('Вподобане'); } else { echo 'Вподобане'; } ?></span>
                        </div>
                        
                        <ul class="fav-list"></ul>
                        
                        <div class="fav-empty-msg">
                            <?php if(function_exists('pll_e')) { pll_e('Список порожній'); } else { echo 'Список порожній'; } ?>
                        </div>
                    </div>

                </div>

            </div> 
        </div> 
    </div> 
</header>