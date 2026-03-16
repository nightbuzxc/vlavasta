<?php
/* Template Name: Thank You Page */
get_header();

// Функція-хелпер для перекладу
if (!function_exists('vlavasta_t')) {
    function vlavasta_t($string) {
        if (function_exists('pll__')) {
            return pll__($string);
        }
        return $string;
    }
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$order_post = get_post($order_id);
$is_valid_order = ($order_post && $order_post->post_type === 'shop_order');

// Отримуємо метадані
$payment_status = get_post_meta($order_id, 'payment_status', true);
$order_status = get_post_meta($order_id, 'order_status', true);
?>

<div class="container" style="padding: 80px 20px; text-align: center;">
    
    <?php if ($is_valid_order): 
        $order_num = get_post_meta($order_id, 'custom_order_number', true);
        $total = get_post_meta($order_id, 'order_total', true);
        $payment_method = get_post_meta($order_id, 'payment_method', true);
    ?>

        <?php if ($payment_status === 'paid' || $payment_method !== 'card_online'): ?>
            
            <div style="font-size: 60px; color: #57bfa3; margin-bottom: 20px;">
                <i class="fa-regular fa-circle-check"></i>
            </div>
            
            <h1 class="section-title" style="margin-bottom: 10px;">
                <?php echo vlavasta_t('Дякуємо за замовлення!'); ?>
            </h1>
            
            <p style="font-size: 18px; color: #333; margin-bottom: 30px;">
                <?php echo vlavasta_t('Ваше замовлення'); ?> 
                <b>#<?php echo esc_html($order_num); ?></b> 
                <?php echo vlavasta_t('успішно прийнято.'); ?>
            </p>

            <div style="background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 30px; max-width: 500px; margin: 0 auto; text-align: left; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                <h3 style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px; font-size: 18px; color: #634343;">
                    <?php echo vlavasta_t('Деталі:'); ?>
                </h3>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="color: #777;"><?php echo vlavasta_t('Номер замовлення:'); ?></span>
                    <span style="font-weight: bold;">#<?php echo esc_html($order_num); ?></span>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="color: #777;"><?php echo vlavasta_t('Сума до сплати:'); ?></span>
                    <span style="font-weight: bold; color: #57bfa3; font-size: 18px;"><?php echo esc_html($total); ?> Zł</span>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="color: #777;"><?php echo vlavasta_t('Статус оплати:'); ?></span>
                    <?php if($payment_status === 'paid'): ?>
                        <span style="color: #57bfa3; font-weight: bold;"><?php echo vlavasta_t('Оплачено'); ?></span>
                    <?php else: ?>
                        <span style="color: #f39c12; font-weight: bold;"><?php echo vlavasta_t('Оплата при отриманні'); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <p style="margin-top: 30px; color: #888; font-size: 14px;">
                <?php echo vlavasta_t('Ми також надіслали лист із деталями на вашу електронну пошту.'); ?>
            </p>

        <?php elseif ($payment_status === 'failed'): ?>
            
            <div style="font-size: 60px; color: #e74c3c; margin-bottom: 20px;">
                <i class="fa-regular fa-circle-xmark"></i>
            </div>
            
            <h1 class="section-title" style="margin-bottom: 10px;"><?php echo vlavasta_t('Оплата не пройшла'); ?></h1>
            <p style="margin-bottom: 30px;"><?php echo vlavasta_t('На жаль, сталася помилка при оплаті карткою.'); ?></p>
            
            <a href="<?php echo home_url('/checkout/'); ?>" class="btn-checkout" style="display: inline-block; width: auto; padding: 12px 30px;">
                <?php echo vlavasta_t('Спробувати ще раз'); ?>
            </a>

        <?php else: ?>
            <div style="font-size: 60px; color: #f39c12; margin-bottom: 20px;">
                <i class="fa-solid fa-spinner fa-spin"></i>
            </div>
            <h1 class="section-title"><?php echo vlavasta_t('Перевірка платежу...'); ?></h1>
            <p><?php echo vlavasta_t('Будь ласка, зачекайте, ми перевіряємо статус вашої оплати.'); ?></p>
            <script>setTimeout(function(){ window.location.reload(); }, 3000);</script>
        <?php endif; ?>

    <?php else: ?>
        <h1><?php echo vlavasta_t('Замовлення не знайдено.'); ?></h1>
        <a href="<?php echo home_url(); ?>" class="btn-checkout" style="display: inline-block; width: auto; padding: 12px 30px; margin-top: 20px;">
            <?php echo vlavasta_t('На головну'); ?>
        </a>
    <?php endif; ?>

    <div style="margin-top: 50px;">
        <a href="<?php echo home_url(); ?>" style="text-decoration: none; color: #E8A6A6; font-weight: bold;">
            <i class="fa-solid fa-arrow-left"></i> <?php echo vlavasta_t('Повернутися на головну'); ?>
        </a>
    </div>

</div>

<?php get_footer(); ?>