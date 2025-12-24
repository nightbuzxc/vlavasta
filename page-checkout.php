<?php 
/* Template Name: Checkout Page */
get_header(); 
?>

<div class="container checkout-page-container">
    <h1 class="section-title"><?php if(function_exists('pll_e')) { pll_e('Оформлення замовлення'); } else { echo 'Оформлення замовлення'; } ?></h1>

    <div class="checkout-layout">
        
        <div class="checkout-form-col">
            <form action="<?php echo admin_url('admin-post.php'); ?>" method="POST" id="checkout-form">
                
                <input type="hidden" name="action" value="place_order">
                <?php wp_nonce_field('vlavasta_place_order', 'vlavasta_checkout_nonce'); ?>
                <input type="hidden" name="cart_data" id="cart-data-input">

                <h3 class="form-section-title">
                    <?php if(function_exists('pll_e')) { pll_e('Контактні дані'); } else { echo 'Контактні дані'; } ?>
                </h3>

                <div class="form-group">
                    <label><?php if(function_exists('pll_e')) { pll_e('Країна *'); } else { echo 'Країна *'; } ?></label>
                    <select name="billing_country" id="country-select" class="form-control" required>
                        <option value="" disabled selected>
                            <?php if(function_exists('pll_e')) { pll_e('Оберіть країну'); } else { echo 'Оберіть країну'; } ?>
                        </option>
                        <option value="UA">
                            <?php if(function_exists('pll_e')) { pll_e('Україна 🇺🇦'); } else { echo 'Україна 🇺🇦'; } ?>
                        </option>
                        <option value="PL">
                            <?php if(function_exists('pll_e')) { pll_e('Polska 🇵🇱'); } else { echo 'Polska 🇵🇱'; } ?>
                        </option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label><?php if(function_exists('pll_e')) { pll_e('Ім\'я *'); } else { echo 'Ім\'я *'; } ?></label>
                        <input type="text" name="billing_name" required class="form-control">
                    </div>
                    <div class="form-group half">
                        <label><?php if(function_exists('pll_e')) { pll_e('Прізвище *'); } else { echo 'Прізвище *'; } ?></label>
                        <input type="text" name="billing_surname" required class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label><?php if(function_exists('pll_e')) { pll_e('Телефон *'); } else { echo 'Телефон *'; } ?></label>
                        <input type="tel" name="billing_phone" required class="form-control" placeholder="+380...">
                    </div>
                    <div class="form-group half">
                        <label><?php if(function_exists('pll_e')) { pll_e('Email *'); } else { echo 'Email *'; } ?></label>
                        <input type="email" name="billing_email" required class="form-control">
                    </div>
                </div>

                <h3 class="form-section-title" style="margin-top: 30px;">
                    <?php if(function_exists('pll_e')) { pll_e('Доставка'); } else { echo 'Доставка'; } ?>
                </h3>
                
                <div class="form-group">
                    <label><?php if(function_exists('pll_e')) { pll_e('Місто *'); } else { echo 'Місто *'; } ?></label>
                    <input type="text" name="billing_city" id="city-input" required class="form-control" 
                           placeholder="<?php if(function_exists('pll_e')) { pll_e('Введіть назву міста'); } else { echo 'Введіть назву міста'; } ?>">
                </div>

                <div class="form-group" id="carrier-block" style="display:none;">
                    <label><?php if(function_exists('pll_e')) { pll_e('Служба доставки *'); } else { echo 'Служба доставки *'; } ?></label>
                    <select name="shipping_carrier" id="carrier-select" class="form-control">
                        </select>
                </div>

                <div class="form-group" id="branch-block" style="display:none;">
                    <label id="branch-label">
                        <?php if(function_exists('pll_e')) { pll_e('Номер відділення або адреса *'); } else { echo 'Номер відділення або адреса *'; } ?>
                    </label>
                    <input type="text" name="billing_address" required class="form-control" 
                           placeholder="<?php if(function_exists('pll_e')) { pll_e('Наприклад: Відділення №1'); } else { echo 'Наприклад: Відділення №1'; } ?>">
                </div>

                <h3 class="form-section-title" style="margin-top: 30px;">
                    <?php if(function_exists('pll_e')) { pll_e('Спосіб оплати'); } else { echo 'Спосіб оплати'; } ?>
                </h3>
                <div class="payment-methods">
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="cod" checked>
                        <div class="payment-box">
                            <span class="pay-title">
                                <?php if(function_exists('pll_e')) { pll_e('Накладений платіж'); } else { echo 'Накладений платіж'; } ?>
                            </span>
                            <span class="pay-desc">
                                <?php if(function_exists('pll_e')) { pll_e('Оплата готівкою або карткою при отриманні'); } else { echo 'Оплата готівкою або карткою при отриманні'; } ?>
                            </span>
                        </div>
                    </label>
                    
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="liqpay">
                        <div class="payment-box">
                            <span class="pay-title">
                                <?php if(function_exists('pll_e')) { pll_e('Картою онлайн (LiqPay)'); } else { echo 'Картою онлайн (LiqPay)'; } ?>
                            </span>
                            <span class="pay-desc">
                                <?php if(function_exists('pll_e')) { pll_e('Visa / Mastercard / Privat24'); } else { echo 'Visa / Mastercard / Privat24'; } ?>
                            </span>
                        </div>
                    </label>
                </div>

                <button type="submit" class="btn-checkout big-submit-btn">
                    <?php if(function_exists('pll_e')) { pll_e('Підтвердити замовлення'); } else { echo 'Підтвердити замовлення'; } ?>
                </button>
            </form>
        </div>

        <div class="checkout-summary-col">
            <h3>
                <?php if(function_exists('pll_e')) { pll_e('Ваше замовлення'); } else { echo 'Ваше замовлення'; } ?>
            </h3>
            <div id="checkout-items-list">
                </div>
            
            <div class="checkout-footer">
                <div class="checkout-row total">
                    <span><?php if(function_exists('pll_e')) { pll_e('Разом:'); } else { echo 'Разом:'; } ?></span>
                    <span id="checkout-total-price">0</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>