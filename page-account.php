<?php
/* Template Name: Account Page */
get_header();

$current_user = wp_get_current_user();
?>

<div class="container" style="padding: 80px 20px;">

    <?php if (is_user_logged_in()): ?>
        <div class="account-dashboard-wrapper">
            <div class="dashboard-header">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($current_user->display_name, 0, 1)); ?>
                </div>
                <div>
                    <h1 style="margin: 0; font-size: 24px;">Привіт, <?php echo esc_html($current_user->display_name); ?>!</h1>
                    <p style="margin: 5px 0 0; color: #777;"><?php echo esc_html($current_user->user_email); ?></p>
                </div>
            </div>

            <div class="dashboard-content">
                <h3 class="section-title-small">Історія замовлень</h3>
                
                <?php
                $orders = get_posts([
                    'post_type' => 'shop_order',
                    'meta_key' => 'order_email',
                    'meta_value' => $current_user->user_email,
                    'numberposts' => -1
                ]);

                if ($orders) : ?>
                    <div class="orders-grid">
                        <?php foreach ($orders as $order) : 
                            $status = get_post_meta($order->ID, 'order_status', true);
                            $total = get_post_meta($order->ID, 'order_total', true);
                            $order_num = get_post_meta($order->ID, 'custom_order_number', true) ?: $order->ID;
                            
                            $st_class = ($status == 'completed' || $status == 'shipped') ? 'st-green' : (($status == 'cancelled') ? 'st-red' : 'st-gray');
                            
                            $statuses = [
                                'new' => 'Нове', 'paid' => 'Оплачено', 'shipped' => 'Відправлено',
                                'completed' => 'Виконано', 'cancelled' => 'Скасовано'
                            ];
                            $st_text = $statuses[$status] ?? $status;
                        ?>
                            <div class="order-card">
                                <div class="ord-header">
                                    <span class="ord-num">#<?php echo $order_num; ?></span>
                                    <span class="ord-date"><?php echo get_the_date('d.m.Y', $order->ID); ?></span>
                                </div>
                                <div class="ord-body">
                                    <div class="ord-status <?php echo $st_class; ?>">
                                        <?php echo $st_text; ?>
                                    </div>
                                    <div class="ord-total">
                                        <?php echo $total; ?> грн
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fa-solid fa-box-open"></i>
                        <p>У вас поки немає замовлень.</p>
                        <a href="<?php echo home_url(); ?>" class="btn-checkout" style="width:auto; display:inline-block; margin-top:10px;">Перейти до покупок</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php else: ?>
        <div class="auth-wrapper">
            <h1 class="main-title">Ласкаво просимо</h1>
            <p class="sub-title">Увійдіть або створіть акаунт</p>

            <div class="auth-cards-container">
                
                <div class="auth-card login-card">
                    <h3>Вхід</h3>
                    <form method="POST">
                        <?php wp_nonce_field('vlavasta_login_action', 'vlavasta_login_nonce'); ?>
                        
                        <div class="input-wrap">
                            <i class="fa-regular fa-envelope"></i>
                            <input type="email" name="log_email" placeholder="Ваш Email" required>
                        </div>
                        
                        <div class="input-wrap">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="log_password" placeholder="Пароль" required>
                        </div>

                        <button type="submit" class="btn-auth btn-login">Увійти</button>
                    </form>
                </div>

                <div class="auth-divider">
                    <span>АБО</span>
                </div>

                <div class="auth-card reg-card">
                    <h3>Новий клієнт?</h3>
                    <p style="font-size: 13px; color: #666; margin-bottom: 20px;">Реєстрація займе всього хвилину.</p>
                    
                    <form method="POST">
                        <?php wp_nonce_field('vlavasta_register_action', 'vlavasta_register_nonce'); ?>
                        
                        <div class="input-wrap">
                            <i class="fa-regular fa-user"></i>
                            <input type="text" name="reg_name" placeholder="Ваше ім'я" required>
                        </div>

                        <div class="input-wrap">
                            <i class="fa-regular fa-envelope"></i>
                            <input type="email" name="reg_email" placeholder="Email" required>
                        </div>

                        <div class="input-wrap">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="reg_password" placeholder="Придумайте пароль" required minlength="6">
                        </div>

                        <button type="submit" class="btn-auth btn-register">Зареєструватися</button>
                    </form>
                </div>

            </div>
        </div>
    <?php endif; ?>

</div>

<?php get_footer(); ?>