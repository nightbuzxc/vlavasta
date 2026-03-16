<?php
function vlavasta_scripts() {
    // Стилі
    wp_enqueue_style( 'vlavasta-style', get_stylesheet_uri() );
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' );
    
    // Скрипти
    wp_enqueue_script( 'jquery' );
    
    // Підключення main.js
    wp_enqueue_script( 'vlavasta-main', get_template_directory_uri() . '/main.js', array('jquery'), null, true );
}
add_action( 'wp_enqueue_scripts', 'vlavasta_scripts' );

// 2. ПІДТРИМКА WOOCOMMERCE
function vlavasta_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'vlavasta_add_woocommerce_support' );

add_filter('woocommerce_checkout_fields', 'vlavasta_reorder_checkout_fields', 9999);

function vlavasta_reorder_checkout_fields($fields) {
    unset($fields['order']['order_comments']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_state']);
    
    // Країна
    $fields['billing']['billing_country']['priority'] = 10;
    $fields['billing']['billing_country']['class'] = array('form-row-wide');
    
    // Ім'я та Прізвище
    $fields['billing']['billing_first_name']['priority'] = 20;
    $fields['billing']['billing_first_name']['class'] = array('form-row-first');
    $fields['billing']['billing_last_name']['priority'] = 30;
    $fields['billing']['billing_last_name']['class'] = array('form-row-last');
    
    // Адреса
    $fields['billing']['billing_address_1']['priority'] = 40;
    $fields['billing']['billing_address_1']['class'] = array('form-row-wide');
    $fields['billing']['billing_address_1']['label'] = 'Адреса / Adres';
    $fields['billing']['billing_address_1']['placeholder'] = 'Вулиця та номер будинку';
    
    // Індекс та Місто
    $fields['billing']['billing_postcode']['priority'] = 50;
    $fields['billing']['billing_postcode']['class'] = array('form-row-first');
    $fields['billing']['billing_city']['priority'] = 60;
    $fields['billing']['billing_city']['class'] = array('form-row-last');
    
    // ТЕЛЕФОН І ЕМЕЙЛ
    $fields['billing']['billing_phone']['priority'] = 100;
    $fields['billing']['billing_phone']['class'] = array('form-row-first');
    $fields['billing']['billing_phone']['required'] = true;
    
    $fields['billing']['billing_email']['priority'] = 110;
    $fields['billing']['billing_email']['class'] = array('form-row-last');
    $fields['billing']['billing_email']['required'] = true;
    
    // ВИБІР СЛУЖБИ ДОСТАВКИ
    $fields['billing']['shipping_carrier_option'] = array(
        'type'      => 'select',
        'label'     => 'Служба доставки / Przewoźnik',
        'required'  => true,
        'class'     => array('form-row-wide', 'vlavasta-carrier-field'),
        'priority'  => 120,
        'options'   => array(
            '' => 'Спочатку оберіть країну / Wybierz kraj',
        ),
    );
    
    // Номер відділення
    // Номер відділення
    $fields['billing']['shipping_branch_number'] = array(
        'type'      => 'text',
        'label'     => 'Номер відділення / Paczkomat (№)',
        'placeholder' => 'Напр: №5 або WAW123',
        'required'  => false, // <--- ЗМІНІТЬ ЦЕ НА false
        'class'     => array('form-row-wide', 'vlavasta-branch-field'),
        'priority'  => 130,
    );

    return $fields;
}

// Очистка CSS (і приховування "Безкоштовно")
add_action('wp_head', 'vlavasta_clean_css');
function vlavasta_clean_css() {
    ?>
    <style>
        .woocommerce-billing-fields > h3 { display: none !important; }
        .woocommerce-additional-fields { display: none !important; }
        .mailpoet_checkout_subscribe_section { display: none !important; }
        #mailpoet_woocommerce_checkout_optin_present { display: none !important; }
        .vlavasta-carrier-field, .vlavasta-branch-field {
            margin-top: 20px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        
        /* Приховуємо "Безкоштовно" у новому блоці чекауту */
        .wc-block-checkout__shipping-option--free {
            display: none !important;
        }
        
        /* На всяк випадок, якщо структура зміниться */
        .wc-block-components-shipping-rates-control__package .wc-block-components-radio-control__label-group > span:last-child:contains("Bezplatnie"),
        .wc-block-components-shipping-rates-control__package .wc-block-components-radio-control__label-group > span:last-child:contains("Free") {
             display: none !important;
        }

        /* CSS-дубль для підстраховки */
        #order_review_heading, 
        .woocommerce-checkout-review-order-table-toggle {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
            height: 0 !important;
            pointer-events: none !important;
        }
    </style>
    <?php
}

// ЗМІНА СПИСКУ ДОСТАВКИ (UA/PL)
add_action('wp_footer', 'vlavasta_js_logic');
function vlavasta_js_logic() {
    if (!is_checkout()) return;
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // --- 1. ЛОГІКА ДОСТАВКИ ---
        function updateCarriers() {
            var country = $('#billing_country').val();
            var carrierSelect = $('#shipping_carrier_option');
            
            if(carrierSelect.length === 0) return;
            var selectedVal = carrierSelect.val(); 
            carrierSelect.empty(); 

            if (country === 'UA') {
                carrierSelect.append('<option value="nova_poshta">Нова Пошта</option>');
                carrierSelect.append('<option value="ukrposhta">Укрпошта</option>');
            } else if (country === 'PL') {
                carrierSelect.append('<option value="inpost">InPost (Paczkomat)</option>');
                carrierSelect.append('<option value="dpd">DPD Courier</option>');
                carrierSelect.append('<option value="poczta">Poczta Polska</option>');
            } else {
                carrierSelect.append('<option value="">Спочатку оберіть країну / Wybierz kraj</option>');
            }
            
            // Залишаємо вибір, якщо змінили туди-сюди
            if(selectedVal && carrierSelect.find('option[value="'+selectedVal+'"]').length > 0) {
                carrierSelect.val(selectedVal);
            }
            
            toggleBranchField(); // Оновлюємо видимість поля поштомату
        }

        // Функція показу/сховування поля відділення
        function toggleBranchField() {
            var carrier = $('#shipping_carrier_option').val();
            var branchField = $('#shipping_branch_number_field');

            if (carrier === 'inpost' || carrier === 'nova_poshta') {
                branchField.slideDown(); // Показуємо поле
                // Додаємо зірочку обов'язкового поля візуально
                if (branchField.find('label .required').length === 0) {
                    branchField.find('label').append('&nbsp;<abbr class="required" title="required">*</abbr>');
                }
            } else {
                branchField.slideUp(); // Ховаємо поле
                branchField.find('label .required').remove(); // Прибираємо зірочку
            }
        }

        $('body').on('change', '#billing_country', function() { updateCarriers(); });
        $('body').on('change', '#shipping_carrier_option', function() { toggleBranchField(); });
        
        setTimeout(updateCarriers, 1000);
        $(document.body).on('updated_checkout', function(){ updateCarriers(); });

        // --- 2. ВИДАЛЕННЯ ДУБЛІВ НА МОБІЛЬНОМУ ---
        function removeMobileDuplicates() {
            if ($(window).width() < 769) {
                $('#order_review_heading').remove();
                $('.woocommerce-checkout-review-order-table-toggle').remove();
                $('.woocommerce-checkout > .woocommerce-checkout-review-order').remove(); 
            }
        }
        
        removeMobileDuplicates();
        $(window).resize(removeMobileDuplicates);
        setTimeout(removeMobileDuplicates, 500);
        setTimeout(removeMobileDuplicates, 1500);
    });
    </script>
    <?php
}

/* 1. Робимо телефон ОБОВ'ЯЗКОВИМ */
add_filter( 'woocommerce_billing_fields', 'vlavasta_force_phone_true', 9999 );
function vlavasta_force_phone_true( $fields ) {
    $fields['billing_phone']['required'] = true;
    unset($fields['billing_phone']['optional']); 
    return $fields;
}

/* 2. Блокуємо замовлення, якщо телефон пустий */
add_action( 'woocommerce_checkout_process', 'vlavasta_check_phone_process' );
function vlavasta_check_phone_process() {
    if ( empty( $_POST['billing_phone'] ) ) {
         wc_add_notice( __( 'Введіть номер телефону!' ), 'error' );
    }
}

// Запобіжник: Видаляємо слово "Free" з перекладів
add_filter( 'gettext', 'vlavasta_kill_free_text', 999, 3 );
function vlavasta_kill_free_text( $translated_text, $text, $domain ) {
    if ( $domain === 'woocommerce' ) {
        if ( $text === 'Free' || $text === 'Free!' || $text === 'Bezplatnie' ) {
            return '';
        }
    }
    return $translated_text;
}

if (!function_exists('vlavasta_t')) {
    function vlavasta_t($string) {
        if (function_exists('pll__')) {
            return pll__($string);
        }
        return $string;
    }
}

add_filter( 'template_include', 'vlavasta_force_account_template', 99 );

function vlavasta_force_account_template( $template ) {
    if ( function_exists('is_account_page') && is_account_page() ) {
        $new_template = locate_template( array( 'page-account.php' ) );
        if ( '' != $new_template ) {
            return $new_template;
        }
    }
    return $template;
}

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
add_action( 'woocommerce_before_single_product_summary', 'vlavasta_custom_product_gallery', 20 );

function vlavasta_custom_product_gallery() {
    global $product;

    $main_image_id = $product->get_image_id();
    $attachment_ids = $product->get_gallery_image_ids();

    $all_images = array();
    
    if ($main_image_id) {
        $all_images[] = $main_image_id;
    }
    
    if ($attachment_ids) {
        foreach($attachment_ids as $id) {
            $all_images[] = $id;
        }
    }
    
    $all_images = array_unique($all_images);

    echo '<div class="images custom-product-slider-wrapper">';
        
        if ( !empty($all_images) ) {
            echo '<div class="custom-product-slider" id="productGallerySlider">';
                echo '<div class="slider-inner">';
                foreach ( $all_images as $index => $img_id ) {
                    $img_url = wp_get_attachment_image_url($img_id, 'large');
                    echo '<div class="slide-item">';
                        echo '<img src="' . esc_url($img_url) . '" alt="Product image">';
                    echo '</div>';
                }
                echo '</div>';
                if ( count($all_images) > 1 ) {
                    echo '<button class="p-arrow p-prev"><i class="fa-solid fa-chevron-left"></i></button>';
                    echo '<button class="p-arrow p-next"><i class="fa-solid fa-chevron-right"></i></button>';
                }
            echo '</div>';
        } else {
            echo '<img src="' . wc_placeholder_img_src() . '" alt="Placeholder" style="border-radius:20px;">';
        }

    echo '</div>';
}

add_filter( 'woocommerce_product_tabs', 'vlavasta_remove_reviews_tab', 98 );
function vlavasta_remove_reviews_tab( $tabs ) {
    unset( $tabs['reviews'] );
    return $tabs;
}

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_single_product_summary', 'vlavasta_output_desc_under_cart', 35 );

function vlavasta_output_desc_under_cart() {
    ?>
    <div class="custom-product-desc" style="margin-top: 40px; color: #333;">
        <h3 style="font-size: 22px; font-weight: 800; color: #2c2c2c; margin-bottom: 15px; text-transform: none;">
            <?php 
            if(function_exists('pll_e')) { 
                pll_e('Опис'); 
            } else { 
                echo 'Opis';
            } 
            ?>
        </h3>
        <div style="font-size: 15px; line-height: 1.6;">
            <?php the_content(); ?>
        </div>
    </div>
    <?php
}

add_action( 'init', 'vlavasta_remove_breadcrumbs' );
function vlavasta_remove_breadcrumbs() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}

add_action('init', 'vlavasta_register_cart_strings');
function vlavasta_register_cart_strings() {
    if (function_exists('pll_register_string')) {
        pll_register_string('vlavasta', 'Cart', 'Vlavasta Theme');
        pll_register_string('vlavasta', 'Your cart is currently empty!', 'Vlavasta Theme');
        pll_register_string('vlavasta', 'New in store', 'Vlavasta Theme');
        pll_register_string('vlavasta', 'Return to shop', 'Vlavasta Theme'); 
    }
}

add_action( 'wp_footer', 'vlavasta_cart_refresh_fix' );
function vlavasta_cart_refresh_fix() {
    if ( is_cart() || is_checkout() ) {
        ?>
        <script type="text/javascript">
            jQuery(document.body).on('updated_wc_div', function(){
                jQuery(document.body).trigger('wc_fragment_refresh');
            });
        </script>
        <?php
    }
}

add_action( 'woocommerce_cart_is_empty', 'vlavasta_show_products_empty_cart' );
function vlavasta_show_products_empty_cart() {
    echo '<h3 style="margin-top: 30px; margin-bottom: 20px;">Можливо, вам сподобається:</h3>';
    echo do_shortcode( '[products limit="4" columns="4" orderby="popularity"]' );
}

add_action( 'template_redirect', 'vlavasta_force_no_cache' );
function vlavasta_force_no_cache() {
    if ( is_cart() || is_checkout() || is_account_page() ) {
        if ( ! headers_sent() ) {
            header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
            header( 'Cache-Control: post-check=0, pre-check=0', false );
            header( 'Pragma: no-cache' );
        }
        wc_nocache_headers();
    }
}

/* 3. Блокуємо замовлення, якщо не вказано поштомат для InPost/НП */
add_action( 'woocommerce_checkout_process', 'vlavasta_check_branch_process' );
function vlavasta_check_branch_process() {
    $carrier = isset($_POST['shipping_carrier_option']) ? $_POST['shipping_carrier_option'] : '';
    $branch  = isset($_POST['shipping_branch_number']) ? trim($_POST['shipping_branch_number']) : '';

    if ( in_array($carrier, array('inpost', 'nova_poshta')) && empty($branch) ) {
         wc_add_notice( __( 'Будь ласка, вкажіть номер поштомату або відділення.' ), 'error' );
    }
}