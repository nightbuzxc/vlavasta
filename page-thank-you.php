<?php
/* Template Name: Thank You Page */
get_header();

// Отримуємо технічний ID (щоб знайти замовлення в базі)
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$order_post = get_post($order_id);

$visual_order_number = ''; // Змінна для красивого номера

// Перевірка, чи існує таке замовлення
if ($order_post && $order_post->post_type === 'shop_order') {
    $client_name = get_post_meta($order_id, 'order_client_name', true);
    $total = get_post_meta($order_id, 'order_total', true);
    $payment_method = get_post_meta($order_id, 'payment_method', true);
    
    // Спробуємо дістати наш красивий номер (1, 2, 3...)
    $visual_order_number = get_post_meta($order_id, 'custom_order_number', true);
    
    // Якщо це старе замовлення і в нього немає красивого номера, покажемо звичайний ID
    if (empty($visual_order_number)) {
        $visual_order_number = $order_id;
    }
} else {
    $order_id = 0; // Некоректне замовлення
}
?>

<div class="container" style="padding: 60px 20px; text-align: center;">
    
    <?php if ($order_id): ?>
        <div class="thank-you-box" style="max-width: 600px; margin: 0 auto;">
            <i class="fa-regular fa-circle-check" style="font-size: 80px; color: #6BCFB8; margin-bottom: 20px;"></i>
            
            <h1 style="font-size: 32px; margin-bottom: 15px;">
                <?php if(function_exists('pll_e')) { pll_e('Дякуємо за замовлення!'); } else { echo 'Дякуємо за замовлення!'; } ?>
            </h1>
            
            <p style="font-size: 18px; color: #555; margin-bottom: 30px;">
                <?php echo esc_html($client_name); ?>, 
                <?php if(function_exists('pll_e')) { pll_e('ваше замовлення'); } else { echo 'ваше замовлення'; } ?> 
                <strong>#<?php echo $visual_order_number; ?></strong> 
                <?php if(function_exists('pll_e')) { pll_e('успішно прийнято.'); } else { echo 'успішно прийнято.'; } ?>
            </p>

            <div class="order-summary" style="background: #FFFBF2; padding: 30px; border-radius: 12px; border: 1px solid #eee; text-align: left;">
                <h3 style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">
                    <?php if(function_exists('pll_e')) { pll_e('Деталі:'); } else { echo 'Деталі:'; } ?>
                </h3>
                
                <p><strong><?php if(function_exists('pll_e')) { pll_e('Номер замовлення:'); } else { echo 'Номер замовлення:'; } ?></strong> #<?php echo $visual_order_number; ?></p>
                
                <p><strong><?php if(function_exists('pll_e')) { pll_e('Сума до сплати:'); } else { echo 'Сума до сплати:'; } ?></strong> <span style="font-weight: bold; color: #E8A6A6; font-size: 18px;"><?php echo $total; ?></span></p>
                
                <p><strong><?php if(function_exists('pll_e')) { pll_e('Спосіб оплати:'); } else { echo 'Спосіб оплати:'; } ?></strong> 
                    <?php 
                    if ($payment_method == 'cod') {
                        echo function_exists('pll__') ? pll__('Накладений платіж') : 'Накладений платіж';
                    } elseif ($payment_method == 'liqpay') {
                        echo function_exists('pll__') ? pll__('Картою онлайн (LiqPay)') : 'Картою онлайн (LiqPay)';
                    } else {
                        echo $payment_method;
                    }
                    ?>
                </p>
                
                <p style="margin-top: 20px; font-size: 14px; color: #888;">
                    <?php if(function_exists('pll_e')) { pll_e('Ми також надіслали лист із деталями на вашу електронну пошту. Менеджер зв\'яжеться з вами найближчим часом для підтвердження.'); } else { echo 'Ми також надіслали лист із деталями на вашу електронну пошту. Менеджер зв\'яжеться з вами найближчим часом для підтвердження.'; } ?>
                </p>
            </div>

            <a href="<?php echo home_url(); ?>" class="btn-checkout" style="display: inline-block; margin-top: 30px; text-decoration: none; width: auto; padding: 15px 40px;">
                <?php if(function_exists('pll_e')) { pll_e('Повернутися на головну'); } else { echo 'Повернутися на головну'; } ?>
            </a>
        </div>

    <?php else: ?>
        <h1><?php if(function_exists('pll_e')) { pll_e('Помилка'); } else { echo 'Помилка'; } ?></h1>
        <p><?php if(function_exists('pll_e')) { pll_e('Замовлення не знайдено.'); } else { echo 'Замовлення не знайдено.'; } ?></p>
        <a href="<?php echo home_url(); ?>" class="btn-checkout" style="display: inline-block; width: auto;">
            <?php if(function_exists('pll_e')) { pll_e('На головну'); } else { echo 'На головну'; } ?>
        </a>
    <?php endif; ?>

</div>

<?php get_footer(); ?>