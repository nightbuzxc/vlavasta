<?php get_header(); ?>

<div class="container" style="padding: 100px 20px; text-align: center;">
    <h1 style="font-size: 80px; color: #E8A6A6; margin-bottom: 20px;">404</h1>
    
    <h2 style="font-size: 24px; margin-bottom: 20px;">
        <?php if(function_exists('pll_e')) { pll_e('Ой! Такої сторінки не існує.'); } else { echo 'Ой! Такої сторінки не існує.'; } ?>
    </h2>
    
    <p style="margin-bottom: 40px; color: #666;">
        <?php if(function_exists('pll_e')) { pll_e('Здається, ви перейшли за неправильним посиланням.'); } else { echo 'Здається, ви перейшли за неправильним посиланням.'; } ?>
    </p>

    <a href="<?php echo home_url(); ?>" class="btn-checkout" style="display: inline-block; width: auto; padding: 15px 40px; text-decoration: none;">
        <?php if(function_exists('pll_e')) { pll_e('Повернутися на головну'); } else { echo 'Повернутися на головну'; } ?>
    </a>
</div>

<?php get_footer(); ?>