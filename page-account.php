<?php
/* Template Name: Vlavasta Account Page */
get_header();

$current_user = wp_get_current_user();
?>

<div class="container" style="padding: 80px 50px;">

    <?php 
    // ДОДАНО: Блок для виводу помилок та повідомлень WooCommerce
    if ( function_exists( 'wc_print_notices' ) ) {
        echo '<div class="vlavasta-notices-wrapper" style="max-width: 900px; margin: 0 auto 30px;">';
        wc_print_notices();
        echo '</div>';
    }
    ?>

    <?php if (is_user_logged_in()): ?>
        <div class="account-dashboard-wrapper">
            <div class="dashboard-header">
                <div class="user-avatar">
                    <?php echo mb_strtoupper(mb_substr($current_user->display_name, 0, 1)); ?>
                </div>
                <div>
                    <h1><?php echo vlavasta_t('Привіт'); ?>, <?php echo esc_html( !empty($current_user->first_name) ? $current_user->first_name : $current_user->display_name ); ?>!</h1>
                    <p style="margin: 5px 0 0; color: #777;"><?php echo esc_html($current_user->user_email); ?></p>
                </div>
            </div>

            <div class="dashboard-content">
                <h3 class="section-title-small"><?php echo vlavasta_t('Історія замовлень'); ?></h3>
                
                <?php
                // Отримуємо замовлення
                $args = array(
                    'customer_id' => $current_user->ID,
                    'limit'       => -1,
                    'status'      => array('completed', 'processing', 'on-hold', 'pending', 'cancelled', 'refunded', 'failed'),
                );
                
                $orders = wc_get_orders($args);

                if (!$orders && $current_user->user_email) {
                    $orders = wc_get_orders(array('billing_email' => $current_user->user_email, 'limit' => -1));
                }

                if ($orders) : ?>
                    <div class="orders-grid">
                        <?php foreach ($orders as $order) : 
                            $status = $order->get_status();
                            $total = $order->get_total();
                            $currency_symbol = get_woocommerce_currency_symbol($order->get_currency());
                            $order_num = $order->get_order_number();
                            $date = $order->get_date_created()->date('d.m.Y');
                            
                            // Статуси
                            $status_labels = wc_get_order_statuses();
                            $status_key = 'wc-' . $status;
                            $st_text = isset($status_labels[$status_key]) ? $status_labels[$status_key] : $status;

                            $st_class = 'st-gray';
                            if (in_array($status, ['completed', 'processing'])) $st_class = 'st-green';
                            if (in_array($status, ['cancelled', 'failed', 'refunded'])) $st_class = 'st-red';
                            if (in_array($status, ['on-hold', 'pending'])) $st_class = 'st-yellow';
                        ?>
                            
                            <div class="order-card js-open-modal" data-target="order-modal-<?php echo $order->get_id(); ?>" style="cursor:pointer;">
                                <div class="ord-header">
                                    <span class="ord-num">#<?php echo esc_html($order_num); ?></span>
                                    <span class="ord-date"><?php echo esc_html($date); ?></span>
                                </div>
                                <div class="ord-body">
                                    <div class="ord-status <?php echo $st_class; ?>">
                                        <?php echo esc_html($st_text); ?>
                                    </div>
                                    <div class="ord-total">
                                        <?php echo esc_html($total . ' ' . $currency_symbol); ?>
                                    </div>
                                </div>
                            </div>

                            <div id="order-modal-<?php echo $order->get_id(); ?>" class="custom-modal-overlay">
                                <div class="custom-modal-content">
                                    <div class="mod-header">
                                        <h4 class="mod-title"><?php echo vlavasta_t('Замовлення'); ?> #<?php echo esc_html($order_num); ?></h4>
                                        <button class="close-modal-btn">&times;</button>
                                    </div>
                                    <div class="mod-body">
                                        
                                        <div class="mod-section">
                                            <div class="mod-section-title"><?php echo vlavasta_t('Товари'); ?></div>
                                            <?php foreach ($order->get_items() as $item_id => $item) : 
                                                $product = $item->get_product();
                                                $item_total = $order->get_formatted_line_subtotal($item);
                                            ?>
                                                <div class="mod-product-item">
                                                    <div>
                                                        <div class="mod-prod-name"><?php echo esc_html($item->get_name()); ?></div>
                                                        <div class="mod-prod-meta">x <?php echo esc_html($item->get_quantity()); ?></div>
                                                    </div>
                                                    <div class="mod-prod-total">
                                                        <?php echo $item_total; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                            
                                            <div class="mod-total-row">
                                                <span><?php echo vlavasta_t('Разом до сплати:'); ?></span>
                                                <span class="mod-total-val"><?php echo $order->get_formatted_order_total(); ?></span>
                                            </div>
                                        </div>

                                        <div class="mod-section">
                                            <div class="mod-section-title"><?php echo vlavasta_t('Доставка'); ?></div>
                                            <p style="margin: 0; font-weight: 600;"><?php echo esc_html($order->get_shipping_method()); ?></p>
                                            <div class="mod-address">
                                                <?php echo $order->get_formatted_shipping_address(); ?>
                                            </div>
                                        </div>

                                        <div class="mod-section">
                                            <div class="mod-section-title"><?php echo vlavasta_t('Оплата'); ?></div>
                                            <p style="margin: 0; color: #555;">
                                                <?php echo esc_html($order->get_payment_method_title()); ?>
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fa-solid fa-box-open"></i>
                        <p><?php echo vlavasta_t('У вас поки немає замовлень.'); ?></p>
                        <a href="<?php echo home_url(); ?>" class="btn-checkout" style="width:auto; display:inline-block; margin-top:10px;">
                            <?php echo vlavasta_t('Перейти до покупок'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php else: ?>
        <div class="auth-wrapper">
            <h1 class="main-title"><?php echo vlavasta_t('Ласкаво просимо'); ?></h1>
            <p class="sub-title"><?php echo vlavasta_t('Увійдіть або створіть акаунт'); ?></p>

            <div class="auth-cards-container">
                <div class="auth-card login-card">
                    <h3><?php echo vlavasta_t('Вхід'); ?></h3>
                    <form method="POST">
                        <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                        <input type="hidden" name="redirect" value="<?php echo esc_url(get_permalink()); ?>" />
                        
                        <div class="input-wrap">
                            <i class="fa-regular fa-envelope"></i>
                            <input type="text" name="username" placeholder="<?php echo vlavasta_t('Email або логін'); ?>" required>
                        </div>
                        <div class="input-wrap">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="password" placeholder="<?php echo vlavasta_t('Пароль'); ?>" required>
                        </div>
                        <button type="submit" name="login" class="btn-auth btn-login" value="<?php echo vlavasta_t('Увійти'); ?>"><?php echo vlavasta_t('Увійти'); ?></button>
                    </form>
                </div>
                <div class="auth-divider"><span><?php echo vlavasta_t('АБО'); ?></span></div>
                <div class="auth-card reg-card">
                    <h3><?php echo vlavasta_t('Новий клієнт?'); ?></h3>
                    <p style="font-size: 13px; color: #666; margin-bottom: 20px;"><?php echo vlavasta_t('Реєстрація займе всього хвилину.'); ?></p>
                    <form method="POST">
                        <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                        <div class="input-wrap">
                            <i class="fa-regular fa-envelope"></i>
                            <input type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="input-wrap">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="password" placeholder="<?php echo vlavasta_t('Придумайте пароль'); ?>" required>
                        </div>
                        <button type="submit" name="register" class="btn-auth btn-register" value="<?php echo vlavasta_t('Зареєструватися'); ?>"><?php echo vlavasta_t('Зареєструватися'); ?></button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Відкриття
    const cards = document.querySelectorAll('.js-open-modal');
    cards.forEach(card => {
        card.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const modal = document.getElementById(targetId);
            if (modal) {
                modal.classList.add('open');
                document.body.style.overflow = 'hidden'; // Блокуємо скрол сайту
            }
        });
    });

    // Закриття (хрестик)
    const closeBtns = document.querySelectorAll('.close-modal-btn');
    closeBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation(); // Щоб не спрацював клік на фон
            const modal = this.closest('.custom-modal-overlay');
            closeModal(modal);
        });
    });

    // Закриття (клік по фону)
    const modals = document.querySelectorAll('.custom-modal-overlay');
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this);
            }
        });
    });

    function closeModal(modal) {
        if (modal) {
            modal.classList.remove('open');
            document.body.style.overflow = ''; // Розблокуємо скрол
        }
    }
});
</script>

<?php get_footer(); ?>