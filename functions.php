<?php
// 1. ПІДКЛЮЧЕННЯ СТИЛІВ ТА СКРИПТІВ
function vlavasta_scripts() {
    wp_enqueue_style('main-style', get_stylesheet_uri());
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

    wp_enqueue_script(
        'vlavasta-main-js', 
        get_template_directory_uri() . '/main.js', 
        array(), 
        '1.0', 
        true 
    );

    // Шукаємо сторінку Checkout
    $checkout_url = home_url('/');
    $pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-checkout.php',
        'hierarchical' => 0,
        'number' => 1
    ));

    if (!empty($pages)) {
        $page_id = $pages[0]->ID;
        if (function_exists('pll_get_post')) {
            $translated_id = pll_get_post($page_id);
            if ($translated_id) {
                $page_id = $translated_id;
            }
        }
        $checkout_url = get_permalink($page_id);
    }

    // Переклади для JS
    $translations = array(
        'alert_empty'     => function_exists('pll__') ? pll__('Ваш кошик порожній!') : 'Ваш кошик порожній!',
        'cart_empty_text' => function_exists('pll__') ? pll__('Кошик порожній') : 'Кошик порожній',
        'nova_poshta'     => function_exists('pll__') ? pll__('Нова Пошта') : 'Нова Пошта',
        'ukrposhta'       => function_exists('pll__') ? pll__('Укрпошта') : 'Укрпошта',
        'inpost'          => function_exists('pll__') ? pll__('InPost (Paczkomat)') : 'InPost (Paczkomat)',
        'dpd'             => function_exists('pll__') ? pll__('DPD Courier') : 'DPD Courier',
        'poczta_polska'   => function_exists('pll__') ? pll__('Poczta Polska') : 'Poczta Polska',
        'ua_branch_label' => function_exists('pll__') ? pll__('Номер відділення або адреса *') : 'Номер відділення або адреса *',
        'ua_placeholder'  => function_exists('pll__') ? pll__('Наприклад: Відділення №1, м. Рівне') : 'Наприклад: Відділення №1, м. Рівне',
        'pl_branch_label' => function_exists('pll__') ? pll__('Адреса доставки / Номер поштомату *') : 'Адреса доставки / Номер поштомату *',
        'pl_placeholder'  => function_exists('pll__') ? pll__('Вулиця, будинок або код Paczkomat') : 'Вулиця, будинок або код Paczkomat',
    );

    wp_localize_script('vlavasta-main-js', 'vlavasta_globals', array(
        'ajax_url'     => admin_url('admin-ajax.php'),
        'checkout_url' => $checkout_url,
        'strings'      => $translations
    ));
}
add_action('wp_enqueue_scripts', 'vlavasta_scripts');

// 2. ПІДТРИМКА МІНІАТЮР
add_theme_support('post-thumbnails');

// 3. РЕЄСТРАЦІЯ РЯДКІВ (Polylang)
add_action('init', function() {
    if (function_exists('pll_register_string')) {
        // Vlavasta URLs (ДЛЯ РЕДІРЕКТІВ)
        pll_register_string('slug_public_offer', 'public-offer', 'Vlavasta URLs');
        pll_register_string('slug_privacy_policy', 'privacy-policy', 'Vlavasta URLs');
        pll_register_string('slug_account_page', 'my-account', 'Vlavasta URLs'); // <--- ВАЖЛИВО: Сюди впишете account-ua

        // Інші рядки
        pll_register_string('vlavasta_products_title', 'Товари', 'Vlavasta Theme');
        pll_register_string('vlavasta_buy_btn', 'Купити', 'Vlavasta Theme');
        pll_register_string('vlavasta_out_stock', 'Закінчився', 'Vlavasta Theme');
        pll_register_string('vlavasta_contacts', 'Контакти', 'Vlavasta Theme');
        pll_register_string('vlavasta_tarsh', 'Кошик', 'Vlavasta Theme');
        pll_register_string('vlavasta_tarsh_null', 'Кошик порожній', 'Vlavasta Theme');
        pll_register_string('vlavasta_alert_empty', 'Ваш кошик порожній!', 'Vlavasta Theme');
        pll_register_string('vlavasta_liked', 'Вподобане', 'Vlavasta Theme');
        pll_register_string('vlavasta_socials', 'Соціальні мережі', 'Vlavasta Theme');
        pll_register_string('vlavasta_public_offer', 'Публічна оферта', 'Vlavasta Theme');
        pll_register_string('vlavasta_privacy_policy', 'Політика конфіденційності', 'Vlavasta Theme');
        pll_register_string('vlavasta_Placing_an_order', 'Оформити замовлення', 'Vlavasta Theme');
        pll_register_string('vlavasta_order_buy', 'Контактні дані', 'Vlavasta Theme');
        pll_register_string('vlavasta_name', "Ім'я *", 'Vlavasta Theme');
        pll_register_string('vlavasta_surname', 'Прізвище *', 'Vlavasta Theme');
        pll_register_string('vlavasta_Phone', 'Телефон *', 'Vlavasta Theme');
        pll_register_string('vlavasta_email', 'Email *', 'Vlavasta Theme');
        pll_register_string('vlavasta_Delivery', 'Доставка', 'Vlavasta Theme');
        pll_register_string('vlavasta_Country', 'Країна *', 'Vlavasta Theme');
        pll_register_string('vlavasta_Choose_a_country', 'Оберіть країну', 'Vlavasta Theme');
        pll_register_string('vlavasta_City', 'Місто *', 'Vlavasta Theme');
        pll_register_string('vlavasta_City_name', 'Введіть назву міста', 'Vlavasta Theme');
        pll_register_string('vlavasta_Method_of_payment', 'Спосіб оплати', 'Vlavasta Theme');
        pll_register_string('vlavasta_Cash_on_delivery', 'Накладений платіж', 'Vlavasta Theme');
        pll_register_string('vlavasta_Payment_by_cash_or_card_upon_receipt', 'Оплата готівкою або карткою при отриманні', 'Vlavasta Theme');
        pll_register_string('vlavasta_By_card_online', 'Картою онлайн (LiqPay)', 'Vlavasta Theme');
        pll_register_string('vlavasta_Confirm_the_order', 'Підтвердити замовлення', 'Vlavasta Theme');
        pll_register_string('vlavasta_Your_order', 'Ваше замовлення', 'Vlavasta Theme');
        pll_register_string('vlavasta_Delivery_service', 'Служба доставки *', 'Vlavasta Theme');
        pll_register_string('vlavasta_Branch_number_or_address', 'Номер відділення або адреса *', 'Vlavasta Theme');
        pll_register_string('vlavasta_Department', 'Наприклад: Відділення №1, м. Рівне', 'Vlavasta Theme');
        pll_register_string('vlavasta_Newpost', 'Нова Пошта', 'Vlavasta Theme');
        pll_register_string('vlavasta_Ukrpost', 'Укрпошта', 'Vlavasta Theme');
        pll_register_string('vlavasta_pl_delivery', 'Адреса доставки / Номер поштомату *', 'Vlavasta Theme');
        pll_register_string('vlavasta_pl_delivery_adress', 'Вулиця, будинок або код Paczkomat', 'Vlavasta Theme');
        pll_register_string('vlavasta_inpost', 'InPost (Paczkomat)', 'Vlavasta Theme');
        pll_register_string('vlavasta_dpd', 'DPD Courier', 'Vlavasta Theme'); 
        pll_register_string('vlavasta_poczta', 'Poczta Polska', 'Vlavasta Theme'); 
        pll_register_string('vlavasta_thank_you_title', 'Дякуємо за замовлення!', 'Vlavasta Theme');
        pll_register_string('vlavasta_your_order_txt', 'ваше замовлення', 'Vlavasta Theme');
        pll_register_string('vlavasta_success_accepted', 'успішно прийнято.', 'Vlavasta Theme');
        pll_register_string('vlavasta_details', 'Деталі:', 'Vlavasta Theme');
        pll_register_string('vlavasta_order_num', 'Номер замовлення:', 'Vlavasta Theme');
        pll_register_string('vlavasta_amount_due', 'Сума до сплати:', 'Vlavasta Theme');
        pll_register_string('vlavasta_email_msg', 'Ми також надіслали лист із деталями на вашу електронну пошту. Менеджер зв\'яжеться з вами найближчим часом для підтвердження.', 'Vlavasta Theme');
        pll_register_string('vlavasta_return_home', 'Повернутися на головну', 'Vlavasta Theme');
        pll_register_string('vlavasta_error', 'Помилка', 'Vlavasta Theme');
        pll_register_string('vlavasta_order_not_found', 'Замовлення не знайдено.', 'Vlavasta Theme');
        pll_register_string('vlavasta_to_home', 'На головну', 'Vlavasta Theme');
        pll_register_string('vlavasta_404_title', 'Ой! Такої сторінки не існує.', 'Vlavasta Theme');
        pll_register_string('vlavasta_404_text', 'Здається, ви перейшли за неправильним посиланням.', 'Vlavasta Theme');
    }
});

// 4. AJAX HANDLER
add_action('wp_ajax_vlavasta_refresh_cart', 'vlavasta_refresh_cart_handler');
add_action('wp_ajax_nopriv_vlavasta_refresh_cart', 'vlavasta_refresh_cart_handler');

function vlavasta_refresh_cart_handler() {
    $cart_ids = isset($_POST['cart_ids']) ? $_POST['cart_ids'] : array();
    $fav_ids = isset($_POST['fav_ids']) ? $_POST['fav_ids'] : array();
    $response = array('cart' => array(), 'fav' => array());

    function get_fresh_product_data($original_id) {
        $translated_id = function_exists('pll_get_post') ? pll_get_post($original_id) : $original_id;
        if (!$translated_id) $translated_id = $original_id;

        $price = get_field('price', $translated_id);
        $currency = function_exists('pll__') ? pll__('грн') : 'грн'; 
        if (function_exists('pll_current_language') && pll_current_language() == 'en') { $currency = 'PLN'; }

        return array(
            'original_id' => $original_id,
            'new_id'      => $translated_id,
            'title'       => get_the_title($translated_id),
            'priceVal'    => $price,
            'currency'    => $currency,
            'price_fmt'   => $price . ' ' . $currency,
            'link'        => get_permalink($translated_id),
            'img'         => get_the_post_thumbnail_url($translated_id, 'thumbnail')
        );
    }

    if (!empty($cart_ids)) foreach ($cart_ids as $id) $response['cart'][] = get_fresh_product_data($id);
    if (!empty($fav_ids)) foreach ($fav_ids as $id) $response['fav'][] = get_fresh_product_data($id);

    wp_send_json_success($response);
}

// 5. РЕЄСТРАЦІЯ CPT "ЗАМОВЛЕННЯ"
add_action('init', 'vlavasta_register_orders');
function vlavasta_register_orders() {
    register_post_type('shop_order', [
        'labels' => ['name' => 'Замовлення', 'singular_name' => 'Замовлення'],
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-cart',
        'supports' => ['title', 'custom-fields'],
        'capability_type' => 'post',
    ]);
}

// 6. ОБРОБКА ЗАМОВЛЕННЯ
add_action('admin_post_nopriv_place_order', 'vlavasta_handle_order');
add_action('admin_post_place_order', 'vlavasta_handle_order');

function vlavasta_handle_order() {
    if (!isset($_POST['vlavasta_checkout_nonce']) || !wp_verify_nonce($_POST['vlavasta_checkout_nonce'], 'vlavasta_place_order')) {
        wp_die('Помилка безпеки');
    }

    $name = sanitize_text_field($_POST['billing_name']);
    $surname = sanitize_text_field($_POST['billing_surname']);
    $phone = sanitize_text_field($_POST['billing_phone']);
    $email = sanitize_email($_POST['billing_email']);
    $city = sanitize_text_field($_POST['billing_city']);
    $method = sanitize_text_field($_POST['payment_method']); 
    $country_code = sanitize_text_field($_POST['billing_country']);
    $address_branch = sanitize_text_field($_POST['billing_address']);
    $country_name = ($country_code === 'UA') ? 'Україна' : 'Польща';
    $carrier_name = sanitize_text_field($_POST['shipping_carrier']);

    $cart_json = stripslashes($_POST['cart_data']);
    $cart_items = json_decode($cart_json, true);
    
    $total_sum = 0;
    $order_items_text = "";
    $order_items_html = "";

    if ($cart_items) {
        foreach ($cart_items as $item) {
            $subtotal = $item['qty'] * $item['priceVal'];
            $total_sum += $subtotal;
            $order_items_text .= "• {$item['title']} x {$item['qty']} — {$subtotal} {$item['currency']}\n";
            $order_items_html .= "<b>{$item['title']}</b> (x{$item['qty']}) — {$subtotal} {$item['currency']}<br>";

            $prod_id = $item['original_id'];
            $qty_bought = $item['qty'];
            
            $current_stock = get_field('stock_quantity', $prod_id);
            if ($current_stock === null || $current_stock === '') $current_stock = 999; 

            $new_stock = $current_stock - $qty_bought;
            if ($new_stock < 0) $new_stock = 0; 

            update_field('stock_quantity', $new_stock, $prod_id);

            if ($new_stock == 0) {
                update_field('out_of_stock', true, $prod_id);
            }
        }
    }

    $current_order_number = get_option('vlavasta_order_counter', 0);
    $new_order_number = $current_order_number + 1;
    update_option('vlavasta_order_counter', $new_order_number);

    $order_id = wp_insert_post([
        'post_type' => 'shop_order',
        'post_title' => 'Замовлення #' . $new_order_number . ' - ' . $name . ' ' . $surname,
        'post_status' => 'publish',
    ]);

    if ($order_id) {
        update_post_meta($order_id, 'custom_order_number', $new_order_number);
        update_post_meta($order_id, 'order_client_name', $name . ' ' . $surname);
        update_post_meta($order_id, 'order_phone', $phone);
        update_post_meta($order_id, 'order_email', $email);
        update_post_meta($order_id, 'order_city', $city);
        update_post_meta($order_id, 'order_country', $country_name);
        update_post_meta($order_id, 'delivery_carrier', $carrier_name);
        update_post_meta($order_id, 'delivery_address', $address_branch);
        update_post_meta($order_id, 'order_total', $total_sum);
        update_post_meta($order_id, 'order_items', $order_items_text);
        update_post_meta($order_id, 'payment_method', $method);
        update_post_meta($order_id, 'payment_status', 'pending');

        $admin_email = get_option('admin_email');
        $headers = ['Content-Type: text/html; charset=UTF-8'];
        $msg_body = "<h3>Деталі замовлення #{$new_order_number}:</h3><p>Клієнт: {$name} {$surname}</p><p>Сума: {$total_sum}</p><p>Товари:<br>{$order_items_html}</p>";
        
        wp_mail($admin_email, "Нове замовлення #{$new_order_number}", $msg_body, $headers);
        wp_mail($email, "Ваше замовлення #{$new_order_number} на Vlavasta", $msg_body, $headers);

        wp_redirect(home_url('/thank-you/?order_id=' . $order_id));
        exit;
    }
}

// 7. СТАТУСИ ЗАМОВЛЕНЬ
add_action('add_meta_boxes', 'vlavasta_add_status_metabox');
function vlavasta_add_status_metabox() {
    add_meta_box('vlavasta_order_status_box', 'Статус та Сповіщення', 'vlavasta_render_status_metabox', 'shop_order', 'side', 'high');
}

function vlavasta_render_status_metabox($post) {
    $current_status = get_post_meta($post->ID, 'order_status', true);
    if (!$current_status) $current_status = 'new';
    $tracking_number = get_post_meta($post->ID, 'delivery_tracking', true);
    wp_nonce_field('vlavasta_save_status', 'vlavasta_status_nonce');
    ?>
    <style>.v-box {margin-bottom:15px;} .v-box label {font-weight:bold; display:block;} .v-box input, .v-box select {width:100%;}</style>
    <div class="v-box">
        <label>Статус замовлення:</label>
        <select name="order_status">
            <option value="new" <?php selected($current_status, 'new'); ?>>Нове замовлення</option>
            <option value="paid" <?php selected($current_status, 'paid'); ?>>Оплачено</option>
            <option value="shipped" <?php selected($current_status, 'shipped'); ?>>Відправлено</option>
            <option value="completed" <?php selected($current_status, 'completed'); ?>>Виконано</option>
            <option value="cancelled" <?php selected($current_status, 'cancelled'); ?>>Скасовано</option>
        </select>
    </div>
    <div class="v-box">
        <label>ТТН:</label>
        <input type="text" name="delivery_tracking" value="<?php echo esc_attr($tracking_number); ?>">
    </div>
    <?php
}

add_action('save_post_shop_order', 'vlavasta_save_and_notify', 10, 3);
function vlavasta_save_and_notify($post_id, $post, $update) {
    if (!isset($_POST['vlavasta_status_nonce']) || !wp_verify_nonce($_POST['vlavasta_status_nonce'], 'vlavasta_save_status')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $new_status = sanitize_text_field($_POST['order_status']);
    $new_tracking = sanitize_text_field($_POST['delivery_tracking']);
    $old_status = get_post_meta($post_id, 'order_status', true);

    update_post_meta($post_id, 'order_status', $new_status);
    update_post_meta($post_id, 'delivery_tracking', $new_tracking);

    if ($new_status !== $old_status) {
        $client_email = get_post_meta($post_id, 'order_email', true);
        if ($client_email) {
            wp_mail($client_email, "Оновлення статусу", "Ваш статус: $new_status. ТТН: $new_tracking", ['Content-Type: text/html; charset=UTF-8']);
        }
    }
}

add_filter('manage_shop_order_posts_columns', function($columns) {
    $new_columns = array();
    foreach($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key == 'title') {
            $new_columns['order_status_col'] = 'Статус';
            $new_columns['order_total_col'] = 'Сума';
        }
    }
    return $new_columns;
});

add_action('manage_shop_order_posts_custom_column', function($column, $post_id) {
    if ($column == 'order_status_col') {
        echo get_post_meta($post_id, 'order_status', true);
    }
    if ($column == 'order_total_col') {
        echo get_post_meta($post_id, 'order_total', true);
    }
}, 10, 2);

// ==========================================
// 8. БЕЗПЕКА ТА ОСОБИСТИЙ КАБІНЕТ
// ==========================================

// Допоміжна функція для отримання посилання на кабінет (мова)
function vlavasta_get_account_url() {
    $slug = function_exists('pll__') ? pll__('my-account') : 'my-account';
    return home_url('/' . trim($slug, '/'));
}

// 1. Приховуємо адмін-бар для клієнтів
add_action('after_setup_theme', function() {
    if (!current_user_can('administrator')) {
        show_admin_bar(false);
    }
});

// 2. Блокуємо /wp-admin для клієнтів
add_action('admin_init', function() {
    if (defined('DOING_AJAX') && DOING_AJAX) return;
    
    if (!current_user_can('administrator')) {
        wp_redirect(vlavasta_get_account_url());
        exit;
    }
});

// 3. Обробка РЕЄСТРАЦІЇ
add_action('init', 'vlavasta_process_register');
function vlavasta_process_register() {
    if (isset($_POST['vlavasta_register_nonce']) && wp_verify_nonce($_POST['vlavasta_register_nonce'], 'vlavasta_register_action')) {
        
        $email = sanitize_email($_POST['reg_email']);
        $password = $_POST['reg_password'];
        $name = sanitize_text_field($_POST['reg_name']);
        
        if (!is_email($email) || email_exists($email)) {
            wp_die('Помилка: Невірний Email або він вже зайнятий.');
        }

        $user_id = wp_create_user($email, $password, $email);

        if (!is_wp_error($user_id)) {
            $user = new WP_User($user_id);
            $user->set_role('subscriber');
            update_user_meta($user_id, 'first_name', $name);
            
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            
            wp_redirect(vlavasta_get_account_url());
            exit;
        }
    }
}

// 4. Обробка ВХОДУ
add_action('init', 'vlavasta_process_login');
function vlavasta_process_login() {
    if (isset($_POST['vlavasta_login_nonce']) && wp_verify_nonce($_POST['vlavasta_login_nonce'], 'vlavasta_login_action')) {
        
        $creds = array(
            'user_login'    => sanitize_email($_POST['log_email']),
            'user_password' => $_POST['log_password'],
            'remember'      => true
        );

        $user = wp_signon($creds, false);

        if (!is_wp_error($user)) {
            if (in_array('administrator', $user->roles)) {
                wp_redirect(admin_url());
            } else {
                // РЕДІРЕКТ НА ПРАВИЛЬНУ МОВНУ СТОРІНКУ
                wp_redirect(vlavasta_get_account_url());
            }
            exit;
        }
    }
}

// 5. Вихід
add_action('init', function() {
    if (isset($_GET['action']) && $_GET['action'] == 'logout') {
        wp_logout();
        wp_redirect(home_url());
        exit;
    }
});
?>