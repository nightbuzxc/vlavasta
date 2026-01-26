document.addEventListener('DOMContentLoaded', () => {

    /* =========================================
       1. ЛОГІКА ВПОДОБАНОГО
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
            if (!id) {
                const buyBtn = btn.parentElement.querySelector('.btn-buy');
                if (buyBtn && buyBtn.getAttribute('href').includes('add-to-cart=')) {
                    id = buyBtn.getAttribute('href').split('add-to-cart=')[1];
                }
            }
            if (id && ids.includes(id)) {
                btn.classList.add('liked');
                btn.querySelector('i').classList.remove('fa-regular');
                btn.querySelector('i').classList.add('fa-solid');
                btn.style.borderColor = '#e74c3c';
                btn.style.color = '#e74c3c';
            } else {
                btn.classList.remove('liked');
                btn.querySelector('i').classList.add('fa-regular');
                btn.querySelector('i').classList.remove('fa-solid');
                btn.style.borderColor = '';
                btn.style.color = '';
            }
        });
    }

    document.body.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-fav');
        if (!btn) return;
        e.preventDefault();
        e.stopPropagation();
        let id = btn.getAttribute('data-id');
        if (!id) {
             const card = btn.closest('.product-card');
             if(card) {
                 const buy = card.querySelector('.btn-buy');
                 if(buy) id = buy.href.split('add-to-cart=')[1];
             }
        }
        if (id) {
            toggleWishlist({ id: id.toString(), title: 'Item', price: '', img: '', link: '' });
        }
    });

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
            row.innerHTML = `
                <div style="display:flex; align-items:center; gap:15px; width:100%;">
                    <a href="${item.link}" style="width: 60px; height: 60px; flex-shrink: 0; border-radius: 8px; overflow: hidden; background: #f9f9f9;">
                        <img src="${item.img}" style="width:100%; height:100%; object-fit:cover;">
                    </a>
                    <div style="flex-grow: 1;">
                        <a href="${item.link}" style="font-weight: 600; color: #333; text-decoration: none; display: block; line-height: 1.2;">
                            ${item.title}
                        </a>
                        <div style="color: #6BCFB8; font-weight: 700; font-size: 14px; margin-top: 5px;">
                            ${item.price}
                        </div>
                    </div>
                    <button class="remove-fav-btn" data-id="${item.id}" style="border: none; background: #fff0f0; color: #e74c3c; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; flex-shrink: 0;">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            `;
            favContainer.appendChild(row);
        });
        document.querySelectorAll('.remove-fav-btn').forEach(delBtn => {
            delBtn.addEventListener('click', (e) => {
                const idToRemove = e.currentTarget.getAttribute('data-id');
                let currentList = getWishlist();
                currentList = currentList.filter(p => p.id !== idToRemove);
                saveWishlist(currentList);
                renderFavModal();
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
       3. СТАРІ СКРИПТИ
       ========================================= */
    const track = document.querySelector('.slider-track');
    if(track) {
        let idx = 0; const slides = document.querySelectorAll('.hero .slide');
        const nextBtn = document.querySelector('.next-btn');
        const prevBtn = document.querySelector('.prev-btn');
        if(nextBtn) nextBtn.addEventListener('click', () => { idx = (idx+1)%slides.length; track.style.transform=`translateX(-${idx*100}%)`; });
        if(prevBtn) prevBtn.addEventListener('click', () => { idx = (idx-1+slides.length)%slides.length; track.style.transform=`translateX(-${idx*100}%)`; });
    }
    
    const userBtn = document.getElementById('userMenuBtn');
    if(userBtn) userBtn.addEventListener('click', (e) => { e.stopPropagation(); document.getElementById('userMenuDropdown').classList.toggle('active'); });
    const langBtn = document.querySelector('.lang-dropdown');
    if(langBtn) langBtn.addEventListener('click', (e) => { e.stopPropagation(); document.querySelector('.lang-dropdown').classList.toggle('active'); });

    document.addEventListener('click', () => {
        document.querySelectorAll('.active').forEach(el => el.classList.remove('active'));
    });
});

/* =========================================
   QUANTITY BUTTONS & REMOVE FIX
   ========================================= */
jQuery(document).ready(function($) {
    
    // Функція оновлення кошика
    function updateCart() {
         $("[name='update_cart']").prop("disabled", false).trigger("click");
    }

    // 1. Додавання кнопок +/-
    function initQtyButtons() {
        $('.woocommerce-cart .quantity input.qty').each(function() {
            var $input = $(this);
            if ($input.parent('.quantity-wrapper').length === 0 && $input.siblings('.plus').length === 0) {
                $input.wrap('<div class="quantity-wrapper"></div>');
                $input.before('<button type="button" class="minus">-</button>');
                $input.after('<button type="button" class="plus">+</button>');
            }
        });
    }

    initQtyButtons();
    $(document.body).on('updated_cart_totals', initQtyButtons);
    $(document.body).on('updated_wc_div', initQtyButtons);

    // 2. Логіка МІНУС
    $(document.body).on('click', '.minus', function(e) {
        e.preventDefault();
        var $input = $(this).siblings('input.qty');
        var val = parseFloat($input.val()) || 0;
        var step = parseFloat($input.attr('step')) || 1;
        var min = parseFloat($input.attr('min')) || 0;
        if (min === 0) min = 1; // Зазвичай мінімум 1
        if (val > min) {
            $input.val(val - step).trigger('change');
            updateCart();
        }
    });

    // 3. Логіка ПЛЮС
    $(document.body).on('click', '.plus', function(e) {
        e.preventDefault();
        var $input = $(this).siblings('input.qty');
        var val = parseFloat($input.val()) || 0;
        var step = parseFloat($input.attr('step')) || 1;
        var max = parseFloat($input.attr('max'));
        if (isNaN(max) || val < max) {
            $input.val(val + step).trigger('change');
            updateCart();
        }
    });

    // 4. Фікс видалення (візуально ховаємо рядок одразу)
    $(document.body).on('click', '.remove', function(e) {
        // Ми не скасовуємо дефолтну поведінку, бо це посилання!
        // Але ми візуально ховаємо рядок, щоб користувач бачив реакцію
        $(this).closest('tr').css('opacity', '0.2').fadeTo(500, 0.1); 
        
        setTimeout(function() {
             // Якщо кошик став пустим, перезавантажуємо
             if ( $('.woocommerce-cart-form .shop_table').length === 0 ) {
                window.location.reload(); 
            }
        }, 1500);
    });
});