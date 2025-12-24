<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            
            <div class="footer-col contact-col">
                <h4><?php if(function_exists('pll_e')) { pll_e('Контакти'); } else { echo 'Контакти'; } ?></h4>
                <p>vlavasta@gmail.com</p>
                <p>+48 12 345 6789</p>

                <div style="margin-top: 20px; font-size: 13px;">
                    <?php 
                    // --- ЛОГІКА ПОСИЛАНЬ (БЕЗ functions.php) ---
                    $current_lang = (function_exists('pll_current_language')) ? pll_current_language() : 'en';
                    
                    // Замовчування (Англійська або інша)
                    $offer_slug = 'public-offer';
                    $privacy_slug = 'privacy-policy';

                    // Українська (код 'uk')
                    if ($current_lang == 'uk') {
                        $offer_slug = 'public-offer-ua'; // Ваше посилання з адмінки
                        $privacy_slug = 'privacy-policy'; // Перевірте, чи створили ви privacy-policy-ua
                    } 
                    // Польська (код 'pl')
                    elseif ($current_lang == 'pl') {
                        $offer_slug = 'public-offer-pl'; // Ваше посилання з адмінки
                        $privacy_slug = 'privacy-policy'; // Тут теж треба буде змінити, якщо створите окрему сторінку
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

            <div class="footer-col social-col">
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

<?php wp_footer(); ?>
</body>
</html>