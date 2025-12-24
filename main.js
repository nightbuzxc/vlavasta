document.addEventListener('DOMContentLoaded', () => {

    const strings = (typeof vlavasta_globals !== 'undefined' && vlavasta_globals.strings) ? vlavasta_globals.strings : {
        alert_empty: 'Ваш кошик порожній!',
        cart_empty_text: 'Кошик порожній',
        nova_poshta: 'Нова Пошта',
        ukrposhta: 'Укрпошта',
        inpost: 'InPost (Paczkomat)',
        dpd: 'DPD Courier',
        poczta_polska: 'Poczta Polska',
        ua_branch_label: 'Номер відділення або адреса *',
        ua_placeholder: 'Наприклад: Відділення №1, м. Рівне',
        pl_branch_label: 'Адреса доставки / Номер поштомату *',
        pl_placeholder: 'Вулиця, будинок або код Paczkomat'
    };

    const track = document.querySelector('.slider-track');
    const nextBtn = document.querySelector('.next-btn');
    const prevBtn = document.querySelector('.prev-btn');

    // Кнопки та меню
    const cartBtn = document.getElementById('cartBtn');
    const cartDropdown = document.getElementById('cartDropdown');
    
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userMenuDropdown = document.getElementById('userMenuDropdown');
    const menuFavBtn = document.querySelector('.open-fav-from-menu'); // Кнопка "Вподобане" в меню
    
    const favDropdown = document.getElementById('favDropdown');
    const backToMenuBtn = document.querySelector('.back-to-menu-btn'); // Кнопка "Назад"
    const favList = document.querySelector('.fav-list');
    const favCountInline = document.querySelector('.fav-count-inline');
    const favEmptyMsg = document.querySelector('.fav-empty-msg');

    const buyButtons = document.querySelectorAll('.btn-buy');
    const cartList = document.querySelector('.cart-list');
    const cartCount = document.querySelector('.cart-count');
    const cartEmptyMsg = document.querySelector('.cart-empty-msg');
    const cartTotalEl = document.querySelector('.total-price');
    const cartFooter = document.querySelector('.cart-footer');
    const checkoutBtn = document.querySelector('.btn-checkout');

    // --- ЛОГІКА ІНТЕРФЕЙСУ (МЕНЮ) ---

    // 1. Кошик (клік)
    if (cartBtn && cartDropdown) {
        cartBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            cartDropdown.classList.toggle('active');
            
            // Закриваємо інші меню
            if (userMenuDropdown) userMenuDropdown.classList.remove('active');
            if (favDropdown) favDropdown.classList.remove('active');
        });
    }

    // 2. Головне меню користувача (клік на людину)
    if (userMenuBtn && userMenuDropdown) {
        userMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            // Якщо відкрито вподобане - закриваємо його
            if (favDropdown) favDropdown.classList.remove('active');
            
            userMenuDropdown.classList.toggle('active');
            
            // Закриваємо кошик
            if (cartDropdown) cartDropdown.classList.remove('active');
        });
    }

    // 3. Перехід Меню -> Вподобане
    if (menuFavBtn && favDropdown && userMenuDropdown) {
        menuFavBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            userMenuDropdown.classList.remove('active'); // Ховаємо меню
            favDropdown.classList.add('active');         // Показуємо список
        });
    }

    // 4. Кнопка "Назад" (Вподобане -> Меню)
    if (backToMenuBtn && favDropdown && userMenuDropdown) {
        backToMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            favDropdown.classList.remove('active');      // Ховаємо список
            userMenuDropdown.classList.add('active');    // Показуємо меню
        });
    }

    // 5. Закриття при кліку повз
    document.addEventListener('click', (e) => {
        // Кошик
        if (cartDropdown && cartDropdown.classList.contains('active')) {
            if (!cartDropdown.contains(e.target) && !cartBtn.contains(e.target)) {
                cartDropdown.classList.remove('active');
            }
        }
        
        // Меню (обидва вікна в одному контейнері)
        const isClickInsideMenu = userMenuDropdown && userMenuDropdown.contains(e.target);
        const isClickInsideFav = favDropdown && favDropdown.contains(e.target);
        const isClickOnUserBtn = userMenuBtn && userMenuBtn.contains(e.target);

        // Якщо клік не в меню, не в списку і не на кнопці
        if (!isClickInsideMenu && !isClickInsideFav && !isClickOnUserBtn) {
            if (userMenuDropdown) userMenuDropdown.classList.remove('active');
            if (favDropdown) favDropdown.classList.remove('active');
        }
    });

    // --- ДАНІ ТА ФУНКЦІОНАЛ ---
    function loadData(key) {
        try { return JSON.parse(localStorage.getItem(key)) || []; } catch (e) { return []; }
    }

    let favorites = loadData('vlavasta_favorites');
    let cart = loadData('vlavasta_cart');

    // Фільтрація сміття
    cart = cart.filter(item => item.id && item.title && !isNaN(parseFloat(item.priceVal)));
    localStorage.setItem('vlavasta_cart', JSON.stringify(cart));

    function updateFavUI() {
        if (favCountInline) {
            favCountInline.textContent = favorites.length > 0 ? favorites.length : '';
        }
        if (favEmptyMsg) favEmptyMsg.style.display = favorites.length > 0 ? 'none' : 'block';

        const favButtons = document.querySelectorAll('.btn-fav');
        favButtons.forEach(btn => {
            const id = btn.getAttribute('data-id');
            const isLiked = favorites.some(item => item.id == id);
            const icon = btn.querySelector('i');
            if (isLiked) {
                btn.classList.add('liked');
                if (icon) { icon.classList.remove('fa-regular'); icon.classList.add('fa-solid'); }
            } else {
                btn.classList.remove('liked');
                if (icon) { icon.classList.remove('fa-solid'); icon.classList.add('fa-regular'); }
            }
        });
        renderFavList();
    }

    function renderFavList() {
        if (!favList) return;
        favList.innerHTML = '';
        favorites.forEach(item => {
            const li = document.createElement('li');
            li.classList.add('fav-item');
            li.innerHTML = `
                <a href="${item.link || '#'}" class="fav-img-link"><img src="${item.img || ''}" alt="${item.title}"></a>
                <div class="fav-info">
                    <a href="${item.link || '#'}" class="fav-title">${item.title}</a>
                    <div class="fav-price">${item.price}</div>
                </div>
                <div class="fav-remove-btn" data-id="${item.id}"><i class="fa-regular fa-trash-can"></i></div>
            `;
            favList.appendChild(li);
        });
    }

    // Видалення з вподобаного
    if (favList) {
        favList.addEventListener('click', (e) => {
            const removeBtn = e.target.closest('.fav-remove-btn');
            if (removeBtn) {
                e.stopPropagation();
                const id = removeBtn.getAttribute('data-id');
                favorites = favorites.filter(item => item.id != id);
                localStorage.setItem('vlavasta_favorites', JSON.stringify(favorites));
                updateFavUI();
            }
        });
    }

    // Додавання в вподобане (Глобальний)
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-fav');
        if (btn) {
            e.preventDefault();
            const product = {
                id: btn.getAttribute('data-id'),
                title: btn.getAttribute('data-title'),
                price: btn.getAttribute('data-price'),
                img: btn.getAttribute('data-img'),
                link: btn.getAttribute('data-link')
            };
            const index = favorites.findIndex(item => item.id == product.id);
            if (index === -1) favorites.push(product);
            else favorites.splice(index, 1);
            localStorage.setItem('vlavasta_favorites', JSON.stringify(favorites));
            updateFavUI();
        }
    });

    // --- КОШИК ---
    function updateCartUI() {
        const totalQty = cart.reduce((acc, item) => acc + (item.qty || 0), 0);
        if (cartCount) {
            cartCount.textContent = totalQty;
            cartCount.classList.toggle('visible', totalQty > 0);
        }
        if (cartEmptyMsg) cartEmptyMsg.style.display = totalQty > 0 ? 'none' : 'block';
        if (cartFooter) cartFooter.style.display = totalQty > 0 ? 'block' : 'none';
        renderCartList();
    }

    function renderCartList() {
        if (!cartList) return;
        cartList.innerHTML = '';
        let totalPrice = 0;
        let currencySymbol = 'Zł';

        cart.forEach(item => {
            const rawPrice = parseFloat(item.priceVal);
            const safePrice = isNaN(rawPrice) ? 0 : rawPrice;
            const safeQty = item.qty || 1;
            totalPrice += safePrice * safeQty;
            if (item.currency) currencySymbol = item.currency;

            const li = document.createElement('li');
            li.classList.add('cart-item');
            li.innerHTML = `
                <a href="${item.link || '#'}"><img src="${item.img || ''}" alt="${item.title}"></a>
                <div class="cart-info">
                    <a href="${item.link || '#'}" class="cart-title">${item.title}</a>
                    <div class="cart-price">${safePrice} ${currencySymbol}</div>
                </div>
                <div class="qty-controls">
                    <button class="qty-btn minus" data-id="${item.id}">-</button>
                    <span class="qty-val">${safeQty}</span>
                    <button class="qty-btn plus" data-id="${item.id}">+</button>
                </div>
            `;
            cartList.appendChild(li);
        });
        if (cartTotalEl) cartTotalEl.textContent = totalPrice.toFixed(2) + ' ' + currencySymbol;
    }

    if (cartList) {
        cartList.addEventListener('click', (e) => {
            const btn = e.target.closest('.qty-btn');
            if (!btn) return;
            e.stopPropagation();
            const id = btn.getAttribute('data-id');
            const itemIndex = cart.findIndex(item => item.id == id);
            if (itemIndex > -1) {
                if (btn.classList.contains('plus')) cart[itemIndex].qty++;
                else if (btn.classList.contains('minus')) {
                    cart[itemIndex].qty--;
                    if (cart[itemIndex].qty <= 0) cart.splice(itemIndex, 1);
                }
                localStorage.setItem('vlavasta_cart', JSON.stringify(cart));
                updateCartUI();
            }
        });
    }

    // Додавання в кошик (Глобальний)
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-buy');
        if (btn) {
            e.preventDefault();
            const rawPrice = btn.getAttribute('data-price-val');
            const safePrice = parseFloat(rawPrice) || 0;
            const id = btn.getAttribute('data-id');
            if (!id) return;

            const product = {
                id: id,
                title: btn.getAttribute('data-title'),
                priceVal: safePrice,
                currency: btn.getAttribute('data-currency') || 'Zł',
                img: btn.getAttribute('data-img'),
                link: btn.getAttribute('data-link'),
                qty: 1
            };
            const existingItem = cart.find(item => item.id == product.id);
            if (existingItem) existingItem.qty++;
            else cart.push(product);

            localStorage.setItem('vlavasta_cart', JSON.stringify(cart));
            updateCartUI();
            
            // Відкрити кошик
            if (cartDropdown) {
                cartDropdown.classList.add('active');
                if (userMenuDropdown) userMenuDropdown.classList.remove('active');
                if (favDropdown) favDropdown.classList.remove('active');
            }
        }
    });

    // Checkout redirect
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function(e) {
            if (cart.length === 0) {
                e.preventDefault();
                alert(strings.alert_empty); 
                return;
            }
            const url = (typeof vlavasta_globals !== 'undefined' && vlavasta_globals.checkout_url) 
                        ? vlavasta_globals.checkout_url : '/checkout/';
            window.location.href = url;
        });
    }

    // Checkout page logic...
    const countrySelect = document.getElementById('country-select');
    const carrierBlock = document.getElementById('carrier-block');
    const carrierSelect = document.getElementById('carrier-select');
    const branchBlock = document.getElementById('branch-block');
    const branchLabel = document.getElementById('branch-label');
    const branchInput = document.querySelector('input[name="billing_address"]');
    const phoneInput = document.querySelector('input[name="billing_phone"]');
    const checkoutItemsList = document.getElementById('checkout-items-list');
    const checkoutTotalPrice = document.getElementById('checkout-total-price');
    const cartDataInput = document.getElementById('cart-data-input');

    if (checkoutItemsList) {
        // Рендер чекауту
        if (cart.length === 0) {
            checkoutItemsList.innerHTML = `<p>${strings.cart_empty_text}</p>`;
            if(checkoutTotalPrice) checkoutTotalPrice.textContent = '0';
        } else {
            checkoutItemsList.innerHTML = '';
            let total = 0;
            let currency = 'Zł';
            cart.forEach(item => {
                const price = parseFloat(item.priceVal) || 0;
                const qty = item.qty || 1;
                const sum = price * qty;
                total += sum;
                if(item.currency) currency = item.currency;
                checkoutItemsList.innerHTML += `
                    <div class="checkout-item">
                        <img src="${item.img}" alt="${item.title}">
                        <div class="checkout-item-info">
                            <div class="ch-title">${item.title}</div>
                            <div class="ch-meta">
                                <span>x${qty}</span>
                                <span class="ch-price">${sum} ${currency}</span>
                            </div>
                        </div>
                    </div>
                `;
            });
            if (checkoutTotalPrice) checkoutTotalPrice.textContent = total.toFixed(2) + ' ' + currency;
            if (cartDataInput) cartDataInput.value = JSON.stringify(cart);
        }
    }

    if (countrySelect && carrierSelect) {
        const carriersData = {
            'UA': [ { val: 'nova_poshta', label: strings.nova_poshta }, { val: 'ukrposhta', label: strings.ukrposhta } ],
            'PL': [ { val: 'inpost', label: strings.inpost }, { val: 'dpd', label: strings.dpd }, { val: 'poczta_polska', label: strings.poczta_polska } ]
        };
        const prefixes = { 'UA': '+380', 'PL': '+48' };

        countrySelect.addEventListener('change', function() {
            const country = this.value;
            carrierSelect.innerHTML = '';
            if (country && carriersData[country]) {
                carrierBlock.style.display = 'block';
                branchBlock.style.display = 'block';
                carriersData[country].forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.val; opt.textContent = c.label;
                    carrierSelect.appendChild(opt);
                });
                if (country === 'UA') {
                     if(branchLabel) branchLabel.textContent = strings.ua_branch_label;
                     if(branchInput) branchInput.placeholder = strings.ua_placeholder;
                } else {
                     if(branchLabel) branchLabel.textContent = strings.pl_branch_label;
                     if(branchInput) branchInput.placeholder = strings.pl_placeholder;
                }
                if (phoneInput) phoneInput.value = prefixes[country] || '';
            } else {
                carrierBlock.style.display = 'none';
                branchBlock.style.display = 'none';
            }
        });
        
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                const country = countrySelect.value || 'UA';
                const prefix = prefixes[country] || '';
                let val = this.value.replace(/[^0-9+]/g, '');
                if (!val.startsWith(prefix)) {
                    let userDigits = val.replace(/\D/g, '');
                    let cleanPrefixDigits = prefix.replace(/\D/g, '');
                    if (userDigits.startsWith(cleanPrefixDigits)) userDigits = userDigits.substring(cleanPrefixDigits.length);
                    val = prefix + userDigits;
                }
                if (val.length > 15) val = val.substring(0, 15);
                this.value = val;
            });
            phoneInput.addEventListener('focus', function() {
                if (this.value === '' || this.value === '+') {
                    const country = countrySelect.value || 'UA';
                    this.value = prefixes[country] || '+380';
                }
            });
        }
    }

    // Ajax оновлення
    function refreshCartLanguage() {
        if (cart.length === 0 || typeof vlavasta_globals === 'undefined') return;
        fetch(vlavasta_globals.ajax_url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'vlavasta_refresh_cart', 'cart_ids[]': cart.map(i => i.id) })
        }).then(r => r.json()).then(resp => {
            if (resp.success && resp.data.cart) {
                cart = cart.map(old => {
                    const fresh = resp.data.cart.find(i => i.original_id == old.id);
                    if (fresh) return { ...old, title: fresh.title, priceVal: parseFloat(fresh.priceVal), currency: fresh.currency, link: fresh.link, img: fresh.img || old.img };
                    return old;
                });
                localStorage.setItem('vlavasta_cart', JSON.stringify(cart));
                updateCartUI();
            }
        });
    }

    function refreshFavLanguage() {
        if (favorites.length === 0 || typeof vlavasta_globals === 'undefined') return;
        fetch(vlavasta_globals.ajax_url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'vlavasta_refresh_cart', 'fav_ids[]': favorites.map(i => i.id) })
        }).then(r => r.json()).then(resp => {
            if (resp.success && resp.data.fav) {
                favorites = favorites.map(old => {
                    const fresh = resp.data.fav.find(i => i.original_id == old.id);
                    if (fresh) return { ...old, title: fresh.title, price: fresh.price_fmt, link: fresh.link, img: fresh.img || old.img };
                    return old;
                });
                localStorage.setItem('vlavasta_favorites', JSON.stringify(favorites));
                updateFavUI();
            }
        });
    }

    const prodTrack = document.querySelector('.product-slider-track');
    const prodNextBtn = document.querySelector('.prod-next');
    const prodPrevBtn = document.querySelector('.prod-prev');

    if (prodTrack && prodNextBtn && prodPrevBtn) {
        const prodSlides = prodTrack.querySelectorAll('.product-slide');
        const totalProdSlides = prodSlides.length;
        let prodIndex = 0;
        function updateProdSlider() { prodTrack.style.transform = `translateX(-${prodIndex * 100}%)`; }
        prodNextBtn.addEventListener('click', () => { prodIndex = (prodIndex + 1) % totalProdSlides; updateProdSlider(); });
        prodPrevBtn.addEventListener('click', () => { prodIndex = (prodIndex - 1 + totalProdSlides) % totalProdSlides; updateProdSlider(); });
    }

    refreshCartLanguage();
    refreshFavLanguage();
    updateFavUI();
    updateCartUI();
});