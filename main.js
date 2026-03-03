document.addEventListener('DOMContentLoaded', () => {

    /* =========================================
       1. ЛОГІКА ВПОДОБАНОГО (ВИПРАВЛЕНО)
       ========================================= */
    const wishlistKey = 'vlavasta_wishlist_v1';

    function getWishlist() {
        return JSON.parse(localStorage.getItem(wishlistKey)) || [];
    }

    function saveWishlist(list) {
        localStorage.setItem(wishlistKey, JSON.stringify(list));
        updateHeartIcons();
        updateWishlistBadge();
    }

    function toggleWishlist(product) {
        let list = getWishlist();
        const existingIndex = list.findIndex(item => item.id === product.id);

        if (existingIndex > -1) {
            list.splice(existingIndex, 1);
        } else {
            list.push(product);
        }
        saveWishlist(list);
    }

    function updateWishlistBadge() {
        const list = getWishlist();
        const count = list.length;
        const badges = document.querySelectorAll('.fav-count-badge');

        badges.forEach(badge => {
            badge.innerText = count;
            if (count > 0) {
                badge.classList.add('visible');
            } else {
                badge.classList.remove('visible');
            }
        });
    }

    function updateHeartIcons() {
        const list = getWishlist();
        const ids = list.map(item => item.id.toString());

        document.querySelectorAll('.btn-fav').forEach(btn => {
            let id = btn.getAttribute('data-id');
            // Спроба знайти ID, якщо він не вказаний прямо на кнопці
            if (!id) {
                const card = btn.closest('.product-card, .product');
                if (card) {
                    const buyBtn = card.querySelector('.btn-buy, .add_to_cart_button');
                    if (buyBtn && buyBtn.getAttribute('href') && buyBtn.getAttribute('href').includes('add-to-cart=')) {
                        id = buyBtn.getAttribute('href').split('add-to-cart=')[1];
                        btn.setAttribute('data-id', id); // Зберігаємо, щоб не шукати знову
                    } else if (buyBtn && buyBtn.getAttribute('data-product_id')) {
                        id = buyBtn.getAttribute('data-product_id');
                        btn.setAttribute('data-id', id);
                    }
                }
            }

            if (id && ids.includes(id)) {
                btn.classList.add('liked');
                const icon = btn.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                }
                btn.style.borderColor = '#e74c3c';
                btn.style.color = '#e74c3c';
            } else {
                btn.classList.remove('liked');
                const icon = btn.querySelector('i');
                if (icon) {
                    icon.classList.add('fa-regular');
                    icon.classList.remove('fa-solid');
                }
                btn.style.borderColor = '';
                btn.style.color = '';
            }
        });
    }

    // Слухач кліків
    document.body.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-fav');
        if (!btn) return;
        
        e.preventDefault();
        e.stopPropagation();

        let id = btn.getAttribute('data-id');
        const card = btn.closest('.product-card') || btn.closest('.product') || btn.closest('li.product');

        // Якщо ID немає, пробуємо знайти його знову
        if (!id && card) {
             const buy = card.querySelector('.btn-buy, .add_to_cart_button');
             if(buy) {
                 if(buy.href && buy.href.includes('add-to-cart=')) {
                    id = buy.href.split('add-to-cart=')[1];
                 } else if (buy.dataset.product_id) {
                    id = buy.dataset.product_id;
                 }
             }
        }

        if (id) {
            // --- ЗЧИТУВАННЯ ДАНИХ (SCRAPING) ---
            let title = 'Товар';
            let price = '';
            let img = '';
            let link = '#';

            if (card) {
                // Шукаємо назву
                const titleEl = card.querySelector('.product-title, .woocommerce-loop-product__title, .product_title');
                if(titleEl) title = titleEl.innerText.trim();

                // Шукаємо ціну
                const priceEl = card.querySelector('.price');
                if(priceEl) price = priceEl.innerText.trim();

                // Шукаємо картинку
                const imgEl = card.querySelector('img');
                if(imgEl) {
                    img = imgEl.getAttribute('data-src') || imgEl.src; // Підтримка lazy loading
                }

                // Шукаємо посилання
                const linkEl = card.querySelector('a');
                if(linkEl) link = linkEl.href;
            }

            toggleWishlist({ 
                id: id.toString(), 
                title: title, 
                price: price, 
                img: img, 
                link: link 
            });
        }
    });

    // Ініціалізація при завантаженні
    updateHeartIcons();
    updateWishlistBadge();


    /* =========================================
       2. МОДАЛЬНЕ ВІКНО ВПОДОБАНОГО
       ========================================= */
    const favModal = document.getElementById('fav-modal-overlay');
    const openFavBtns = document.querySelectorAll('.js-open-fav-modal');
    const closeFavBtn = document.getElementById('close-fav-btn');
    const closeActionBtns = document.querySelectorAll('.close-modal-action');
    const favContainer = document.getElementById('fav-list-container');

    window.renderFavModal = function() {
        if(!favContainer) return;
        const list = getWishlist();
        favContainer.innerHTML = '';

        if (list.length === 0) {
            favContainer.innerHTML = `
                <div style="text-align:center; padding: 40px; color: #999;">
                    <i class="fa-regular fa-heart" style="font-size: 40px; margin-bottom: 15px; opacity: 0.5;"></i>
                    <p>Список порожній</p>
                </div>
            `;
            return;
        }

        list.forEach(item => {
            const row = document.createElement('div');
            // Стилі рядка в модальному вікні
            row.style.borderBottom = '1px dashed #eee';
            row.style.padding = '10px 0';
            
            row.innerHTML = `
                <div style="display:flex; align-items:center; gap:15px; width:100%;">
                    <a href="${item.link}" style="width: 60px; height: 60px; flex-shrink: 0; border-radius: 8px; overflow: hidden; background: #f9f9f9;">
                        <img src="${item.img}" style="width:100%; height:100%; object-fit:cover;">
                    </a>
                    <div style="flex-grow: 1;">
                        <a href="${item.link}" style="font-weight: 600; color: #333; text-decoration: none; display: block; line-height: 1.2;">
                            ${item.title}
                        </a>
                        <div style="color: #2c2c2c; font-weight: 700; font-size: 14px; margin-top: 5px;">
                            ${item.price}
                        </div>
                    </div>
                    <button class="remove-fav-btn" data-id="${item.id}" style="border: none; background: transparent; color: #ccc; width: 30px; height: 30px; cursor: pointer; flex-shrink: 0; font-size: 18px;">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            `;
            favContainer.appendChild(row);
        });

        // Додаємо події для кнопок видалення
        document.querySelectorAll('.remove-fav-btn').forEach(delBtn => {
            delBtn.addEventListener('click', (e) => {
                const idToRemove = e.currentTarget.getAttribute('data-id');
                let currentList = getWishlist();
                currentList = currentList.filter(p => p.id !== idToRemove);
                saveWishlist(currentList);
                renderFavModal(); // Перемальовуємо вікно
            });
        });
    }

    openFavBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            renderFavModal();
            if(favModal) {
                favModal.style.display = 'flex';
                setTimeout(() => favModal.classList.add('open'), 10);
                document.body.style.overflow = 'hidden';
                // Закриваємо інші меню, якщо відкриті
                const userMenu = document.getElementById('userMenuDropdown');
                if(userMenu) userMenu.classList.remove('active');
            }
        });
    });

    function closeMyModal() {
        if(favModal) {
            favModal.classList.remove('open');
            setTimeout(() => { favModal.style.display = 'none'; }, 300);
            document.body.style.overflow = '';
        }
    }

    if(closeFavBtn) closeFavBtn.addEventListener('click', closeMyModal);
    closeActionBtns.forEach(b => b.addEventListener('click', closeMyModal));
    
    if(favModal) {
        favModal.addEventListener('click', (e) => {
            if (e.target === favModal) closeMyModal();
        });
    }

    /* =========================================
       3. СЛАЙДЕР ТА МЕНЮ
       ========================================= */
    const track = document.querySelector('.slider-track');
    if(track) {
        let idx = 0; 
        const slides = document.querySelectorAll('.hero .slide');
        const nextBtn = document.querySelector('.next-btn');
        const prevBtn = document.querySelector('.prev-btn');
        
        if(slides.length > 0) {
            if(nextBtn) nextBtn.addEventListener('click', () => { idx = (idx+1)%slides.length; track.style.transform=`translateX(-${idx*100}%)`; });
            if(prevBtn) prevBtn.addEventListener('click', () => { idx = (idx-1+slides.length)%slides.length; track.style.transform=`translateX(-${idx*100}%)`; });
        }
    }
    
    const userBtn = document.getElementById('userMenuBtn');
    if(userBtn) userBtn.addEventListener('click', (e) => { e.stopPropagation(); document.getElementById('userMenuDropdown').classList.toggle('active'); });
    
    const langBtn = document.querySelector('.lang-btn'); // Виправлено селектор
    if(langBtn) langBtn.addEventListener('click', (e) => { e.stopPropagation(); document.querySelector('.lang-dropdown').classList.toggle('active'); });

    document.addEventListener('click', () => {
        document.querySelectorAll('.active').forEach(el => el.classList.remove('active'));
    });
});
/* =========================================
   QUANTITY BUTTONS & CART LOGIC FIX
   ========================================= */
jQuery(document).ready(function($) {
    
    // Функція примусового оновлення кошика при зміні кількості
    function triggerCartUpdate() {
         $("[name='update_cart']").prop("disabled", false).trigger("click");
    }

    // 1. Додавання кнопок +/- 
    function initQtyButtons() {
        // Працюємо тільки якщо кнопок ще немає
        $('.woocommerce-cart .quantity input.qty').each(function() {
            var $input = $(this);
            if ($input.parent('.quantity-wrapper').length === 0) {
                $input.wrap('<div class="quantity-wrapper"></div>');
                $input.before('<button type="button" class="minus">-</button>');
                $input.after('<button type="button" class="plus">+</button>');
            }
        });
    }

    // Ініціалізація при старті
    initQtyButtons();

    // 2. СЛУХАЧ ОНОВЛЕНЬ WOOCOMMERCE (Найважливіше!)
    // Ця подія спрацьовує, коли WooCommerce завершив AJAX-запит
    $(document.body).on('updated_wc_div', function() {
        initQtyButtons(); // Відновлюємо кнопки +/-
        
        // Перевіряємо, чи кошик пустий. Якщо таблиці немає або є клас empty - перезавантажуємо
        if ( $('.shop_table.cart').length === 0 || $('.cart-empty').length > 0 || $('.woocommerce-info').text().indexOf('empty') > -1 ) {
             window.location.reload();
        }
    });

    // 3. Логіка МІНУС
    $(document.body).on('click', '.minus', function(e) {
        e.preventDefault();
        var $input = $(this).siblings('input.qty');
        var val = parseFloat($input.val()) || 0;
        var step = parseFloat($input.attr('step')) || 1;
        var min = parseFloat($input.attr('min')) || 1;
        
        if (val > min) {
            $input.val(val - step).trigger('change');
            triggerCartUpdate();
        }
    });

    // 4. Логіка ПЛЮС
    $(document.body).on('click', '.plus', function(e) {
        e.preventDefault();
        var $input = $(this).siblings('input.qty');
        var val = parseFloat($input.val()) || 0;
        var step = parseFloat($input.attr('step')) || 1;
        var max = parseFloat($input.attr('max'));
        
        // Якщо max не вказано або val менше max
        if (isNaN(max) || val < max) {
            $input.val(val + step).trigger('change');
            triggerCartUpdate();
        }
    });

    // 5. Клік на видалення (Візуальний ефект)
    $(document.body).on('click', '.remove', function(e) {
        // Ми НЕ блокуємо дію (немає e.preventDefault), щоб WooCommerce сам обробив видалення.
        // Просто робимо рядок прозорим, щоб клієнт бачив реакцію.
        $(this).closest('.cart_item').css('opacity', '0.4').css('pointer-events', 'none');
    });

    /* --- СЛАЙДЕР ТОВАРУ (Product Gallery) --- */
    const prodSlider = document.getElementById('productGallerySlider');
    if (prodSlider) {
        const track = prodSlider.querySelector('.slider-inner');
        const slides = prodSlider.querySelectorAll('.slide-item');
        const nextBtn = prodSlider.querySelector('.p-next');
        const prevBtn = prodSlider.querySelector('.p-prev');
        let prodIdx = 0;

        // Функція оновлення позиції
        function updateProdSlider() {
            track.style.transform = `translateX(-${prodIdx * 100}%)`;
        }

        if (slides.length > 0) {
            if (nextBtn) {
                nextBtn.addEventListener('click', (e) => {
                    e.preventDefault(); // Щоб сторінка не скакала вгору
                    prodIdx = (prodIdx + 1) % slides.length;
                    updateProdSlider();
                });
            }
            if (prevBtn) {
                prevBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    prodIdx = (prodIdx - 1 + slides.length) % slides.length;
                    updateProdSlider();
                });
            }
        }
    }
});