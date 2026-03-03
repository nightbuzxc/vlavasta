<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            
            <div class="footer-col contact-col">
                <h4><?php if(function_exists('pll_e')) { pll_e('Контакти'); } else { echo 'Контакти'; } ?></h4>
                <p>vlavasta.nk@gmail.com</p>
                <p>+48 79 296 6425</p>

                <div style="margin-top: 20px; font-size: 13px;">
                    <?php 
                    $current_lang = (function_exists('pll_current_language')) ? pll_current_language() : 'en';
                    $offer_slug = 'public-offer';
                    $privacy_slug = 'privacy-policy';

                    if ($current_lang == 'uk') {
                        $offer_slug = 'public-offer-ua';
                        $privacy_slug = 'privacy-policy';
                    } 
                    elseif ($current_lang == 'pl') {
                        $offer_slug = 'public-offer-pl';
                        $privacy_slug = 'privacy-policy';
                    }
                    ?>

                    <a href="<?php echo home_url('/' . $offer_slug . '/'); ?>" style="color: #333; text-decoration: underline; display: block; margin-bottom: 5px;">
                        <?php if(function_exists('pll_e')) { pll_e('Публічна оферта'); } else { echo 'Публічна оферта'; } ?>
                    </a>
                    
                    <a href="<?php echo home_url('/' . $privacy_slug . '/'); ?>" style="color: #333; text-decoration: underline;">
                        <?php if(function_exists('pll_e')) { pll_e('Політика конфіденційності'); } else { echo 'Політика конфіденційності'; } ?>
                    </a>
                </div>
            </div>

            <div class="footer-col2 social-col">
                <h4><?php if(function_exists('pll_e')) { pll_e('Соціальні мережі'); } else { echo 'Соціальні мережі'; } ?></h4>
                <a href="https://www.tiktok.com/@vlavasta.pl" class="social-link">
                    <i class="fa-brands fa-tiktok"></i> vlavasta.pl
                </a>
                <a href="https://www.instagram.com/vlavasta_nk/" class="social-link">
                    <i class="fa-brands fa-instagram"></i> vlavasta_nk
                </a>
                <a href="#" class="social-link">
                    <i class="fa-brands fa-facebook"></i> vlavasta.nk
                </a>
                <a href="#" class="social-link">
                    <i class="fa-brands fa-viber"></i> vlavasta.nk
                </a>
            </div>

        </div>
    </div>

    <div class="copyright-bar">
        Copyright 2026 Vlavasta
    </div>
</footer>

<div id="fav-modal-overlay" class="custom-modal-overlay" style="display:none;">
    <div class="custom-modal-content">
        <div class="mod-header">
            <h4 class="mod-title">
                <i class="fa-solid fa-heart" style="color:#E8A6A6; margin-right:10px;"></i> 
                <?php if(function_exists('pll_e')) { pll_e('Вподобані товари'); } else { echo 'Вподобані товари'; } ?>
            </h4>
            <button class="close-modal-btn" id="close-fav-btn">&times;</button>
        </div>
        
        <div class="mod-body">
            <div id="fav-list-container">
                </div>
        </div>

        <div style="padding: 20px 30px; border-top: 1px solid #eee; text-align: center;">
            <button class="btn-buy close-modal-action" style="width: 100%; max-width: 250px; margin: 0 auto; background: #6BCFB8; color: #fff;">
                <?php if(function_exists('pll_e')) { pll_e('Продовжити покупки'); } else { echo 'Продовжити покупки'; } ?>
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userBtn = document.getElementById('userMenuBtn');
    const userMenu = document.getElementById('userMenuDropdown');

    if (userBtn && userMenu) {
        userBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            userMenu.classList.toggle('active');
        });
        document.addEventListener('click', function(e) {
            if (!userMenu.contains(e.target) && !userBtn.contains(e.target)) {
                userMenu.classList.remove('active');
            }
        });
    }
});
</script>
<?php wp_footer(); ?>
</body>
</html>