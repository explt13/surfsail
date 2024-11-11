function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*3600*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

const handleCurrency = () => {
    const options = document.querySelectorAll('.select_information__city .select__option');
    let prevCurrencyValue = null;

    options.forEach(option => {
        option.addEventListener('click', async function(e){
            const currency = e.target.textContent;
            const response = await fetchSecured(`/currency/change`, {
                method: "POST",
                headers: {
                    "Content-Type": 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    currency: currency,
                }),
            });
            if (!response) return;
            const data = await response.json();
            if (!response.ok) {
                throw new Error(response.statusText, data.message);
            }
            calculatePrices(data.currency);
        });
    });

    async function getCurrentCurrency(){
        const response = await fetch(`/currency/get`);
        const data = await response.json();
        if (!response.ok) {
            throw new Error(response.statusText, data.message);
        }
        prevCurrencyValue = data.currency.value;
    }
    getCurrentCurrency();
    
    function calculatePrices(currency) {
        const currentPrices = document.querySelectorAll('.product-price__value');
        if (currentPrices.length > 0) {
            currentPrices.forEach(price => {
                const priceValue = Number(price.textContent.trim().replace(',', '.').replace(' ', ''));
                price.previousElementSibling.textContent = currency.symbol;
                price.textContent = formatNumber(priceValue * (currency.value / prevCurrencyValue));
            })
        }
        prevCurrencyValue = currency.value;
    }
}
handleCurrency();

function main() {
    const page = document.querySelector('.page');
    const cartButtons = document.querySelectorAll('.cart-button');
    const favoriteButtons = document.querySelectorAll('.product-card-actions__like')
    const cartButtonView = document.querySelector('.cart-button-view');
    const cart = document.querySelector('.shop__cart');
    if (cartButtonView) {
        const productTableParams = document.querySelector('.body-product__table');
        const product = cartButtonView.closest('[data-id]');
        let alreadyHaveQtyEl = document.querySelector('.actions-product__cart-have b');
        const buyButton = document.querySelector('.actions-product__buy');
        const minusButton = document.querySelector('.quantity__button_minus');
        const plusButton = document.querySelector('.quantity__button_plus');
        let productQty = parseInt(product.querySelector('.quantity__input input').value.trim()); // 1
        let maxQty = parseInt(product.dataset.qty.trim());
        let alreadyHaveQtyValue = 0;
        
        if (alreadyHaveQtyEl) {
            alreadyHaveQtyValue = parseInt(alreadyHaveQtyEl.textContent.trim());
        }
        
        if (productTableParams.innerHTML.trim() === '') {
            productTableParams.remove();
        }
        
        cartButtonView.addEventListener('click', async function() {
            addToCart(this, product, productQty).then(() => {
                cart.dataset.qty = parseInt(cart.dataset.qty) + productQty;
        
                if (!alreadyHaveQtyEl) {
                    const buttonsActions = document.querySelector('.actions-product__buttons');
                    buttonsActions.insertAdjacentHTML('afterbegin', '<button class="actions-product__delete"><img src="img/home/trash.svg" /></button>');
                    deleteEvent();
                    const span = document.createElement('span');
                    span.classList.add('actions-product__cart-have');
                    span.innerHTML = `(you have <b>${productQty}</b> in cart)`;
                    cartButtonView.appendChild(span);
                    alreadyHaveQtyEl = span.querySelector('b');
                    alreadyHaveQtyValue = parseInt(alreadyHaveQtyEl.textContent.trim());
                    if (productQty > maxQty - alreadyHaveQtyValue){
                        productQty = maxQty - alreadyHaveQtyValue;
                        if (productQty < 1) {
                            product.querySelector('.quantity__input input').value = 1;
                        } else {
                            product.querySelector('.quantity__input input').value = productQty;
                        }
                    }
                } else {
                    alreadyHaveQtyValue += productQty;
                    alreadyHaveQtyEl.textContent = alreadyHaveQtyValue;
                    if (productQty > maxQty - alreadyHaveQtyValue){
                        productQty = maxQty - alreadyHaveQtyValue;
                        if (productQty < 1) {
                            product.querySelector('.quantity__input input').value = 1;
                        } else {
                            product.querySelector('.quantity__input input').value = productQty;
                        }
                    }
                }
        
                handleQtyButtons();
            });
        });
        
        const handleQtyButtons = () => {
            minusButton.disabled = productQty <= 1;
            plusButton.disabled = productQty + alreadyHaveQtyValue >= maxQty;
        
            minusButton.style.backgroundColor = minusButton.disabled ? "#b3b3b3" : "";
            plusButton.style.backgroundColor = plusButton.disabled ? "#b3b3b3" : "";

            if ((productQty + alreadyHaveQtyValue > maxQty) || alreadyHaveQtyValue === maxQty) {
                [buyButton, cartButtonView].forEach(button => {
                    button.disabled = true;
                    button.style.backgroundColor = "#b3b3b3";
                    button.style.boxShadow = "none";
                });
            } else {
                [buyButton, cartButtonView].forEach(button => {
                    button.disabled = false;
                    button.style.backgroundColor = "";
                    button.style.boxShadow = "";
                });
            }
        };
        const deleteEvent = () => {
            const deleteButton = document.querySelector('.actions-product__delete');
            if (deleteButton){
                deleteButton.addEventListener('click', async function () {
                    const response = await fetchSecured('/cart/delete', {
                        method: "DELETE",
                        body: JSON.stringify({
                            product_id: parseInt(product.dataset.id),
                        }),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!response) return;
                    const data = await response.json();
                    if (!response.ok) {
                        handleNotification(response.status, data.message);
                        return;
                    }
                    cart.dataset.qty = parseInt(cart.dataset.qty) - alreadyHaveQtyValue;
                    alreadyHaveQtyValue = 0;
                    alreadyHaveQtyEl.textContent = 0;
                    handleQtyButtons();
                    deleteButton.remove();
                })
            }
        }
        deleteEvent();
     
        minusButton.addEventListener('click', function() {
            if (productQty > 1) {
                productQty--;
                product.querySelector('.quantity__input input').value = productQty;
                handleQtyButtons();
            }
        });
        
        plusButton.addEventListener('click', function() {
            if (productQty < maxQty) {
                productQty++;
                product.querySelector('.quantity__input input').value = productQty;
                handleQtyButtons();
            }
        });
        
        handleQtyButtons();
        async function addToCart(button, product, qty) {
            const response = await fetchSecured('/cart/add', {
                method: 'POST',
                headers: {
                    "Content-Type": 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    product_id: parseInt(product.dataset.id),
                    qty: qty,
                    qty_control: true,
                })
            })
            if (!response) return;
            const data = await response.json();
            if (!response.ok) {
                handleNotification(response.status, data.message);
                return;
            }
            handleNotification(response.status, data.message);
        }
    }

    if (cartButtons) {
        const buttonEventHandlers = new Map();
        const setCartButtons = async () => {
            const response = await fetchSecured('/cart/get-products-list', {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            if (!response) return;
            const data = await response.json();
            cartButtons.forEach(button => {
                const product = button.closest('[data-id]');
                if (data.includes(parseInt(product.dataset.id))){
                    setButton(button);
                }
                button.addEventListener('click', async function(){
                    addToCart(this, product);
                })
            });
        }
        setCartButtons();

        async function addToCart(button, product) {
            const response = await fetchSecured('/cart/add', {
                method: 'POST',
                headers: {
                    "Content-Type": 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    product_id: parseInt(product.dataset.id),
                    qty: 1,
                    qty_control: false,
                })
            })
            if (!response) return;
            const data = await response.json();
            if (!response.ok) {
                handleNotification(response.status, data.message);
                return;
            }
            handleNotification(response.status, data.message);
        
            if (data.action === 'add') {
                cart.dataset.qty = parseInt(cart.dataset.qty) + 1;
                const addedProducts = document.querySelectorAll(`[data-id="${product.dataset.id}"]`);
                addedProducts.forEach(product => {
                    const button = product.querySelector('.cart-button');
                    setButton(button);
                })
            } else if (data.action === 'remove') {
                cart.dataset.qty = parseInt(cart.dataset.qty) - 1;
                const removedProducts = document.querySelectorAll(`[data-id="${product.dataset.id}"]`)
                removedProducts.forEach(product => {
                    const button = product.querySelector('.cart-button');
                    unsetButton(button);
                })
            }
        }
                
        function setButton(button) {
            button.textContent = 'In cart';
            button.classList.add('_in-cart');
            const onMouseEnter = function() {
                button.classList.add('_remove');
                this.textContent = 'Remove from cart';
            };
            const onMouseLeave = function() {
                button.classList.remove('_remove');
                this.textContent = 'In cart';
            };
            buttonEventHandlers.set(button, { onMouseEnter, onMouseLeave });
        
            button.addEventListener('mouseenter', onMouseEnter);
            button.addEventListener('mouseleave', onMouseLeave);
            
        }
        function unsetButton(button) {
            if (button.classList.contains('_in-cart')) {
                button.classList.remove('_in-cart');
            }
            if (button.classList.contains('_remove')) {
                button.classList.remove('_remove');
            }
            button.textContent = 'Add to cart';
            const handlers = buttonEventHandlers.get(button);
            if (handlers) {
                button.removeEventListener('mouseenter', handlers.onMouseEnter);
                button.removeEventListener('mouseleave', handlers.onMouseLeave);
            }
            buttonEventHandlers.delete(button)
        }
    }
    if (favoriteButtons.length > 0) {
        async function setButtons() {
            const response = await fetchSecured('/favorite/get-products-list', {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            if (!response) return;
            const data = await response.json();
            favoriteButtons.forEach(button => {
                const product = button.closest('[data-id]');
                if (data.includes(parseInt(product.dataset.id))){
                    button.classList.add('_in-favorite');
                }
                button.addEventListener('click', async function(e) {
                    const response = await fetchSecured('/favorite/add', {
                        method: "post",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            product_id: parseInt(product.dataset.id),
                        })
                    })
                    if (!response) return;
                    const data = await response.json();
                    if (!response.ok) {
                        handleNotification(response.code, data.message);
                        return;
                    }
                    handleNotification(response.status, data.message);
                    if (data.action === 'add'){
                        const addedProducts = document.querySelectorAll(`[data-id="${product.dataset.id}"]`);
                        addedProducts.forEach(product => {
                            const button = product.querySelector('.product-card-actions__like');
                            button.classList.add('_in-favorite');
                        })
                        
                    } else if (data.action === 'remove') {
                        const removedProducts = document.querySelectorAll(`[data-id="${product.dataset.id}"]`)
                        removedProducts.forEach(product => {
                            const button = product.querySelector('.product-card-actions__like');
                            button.classList.remove('_in-favorite');
                        })
                    }
                })
    
            })
        }
        setButtons();
    }

    if (page.classList.contains('page_favorite')) {
        const products = document.querySelectorAll('[data-id]');
        let productsQty = products.length;
        products.forEach(product => {
            const deleteButton = product.querySelector('.cart-item__delete');
            deleteButton.addEventListener('click', async function(){
                const response = await fetchSecured('/favorite/delete', {
                    method: "DELETE",
                    body: JSON.stringify({
                        product_id: parseInt(product.dataset.id),
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (!response) return;
                const data = await response.json();
                if (!response.ok) {
                    handleNotification(response.status, data.message);
                    return;
                }
                handleNotification(response.status, data.message);
                product.remove();
                productsQty--;
                if (productsQty === 0) {
                    const cartBody = document.querySelector('.cart__body');
                    cartBody.innerHTML = `
                    <div class=\"cart__no-items\">
                        <div class="cart__no-items-image">
                            <img src="img/home/empty-cart.svg" alt="no favorite items">
                        </div>
                        <div class="cart__no-items-text">No favorite items</div>
                    </div>
                    `;
                }
            });
        })
        const mq = window.matchMedia("(min-width: 992px)");
        if (mq.matches) {
            const windowSize = window.innerHeight;
            const headerHeight = document.querySelector('.header').scrollHeight;
            const cartBody = document.querySelector('.cart__body');
            cartBody.style.minHeight = (windowSize - headerHeight) + "px";
        }

    }
    if (page.classList.contains('page_cart')) {
        const cart = document.querySelector('.shop__cart');
        const products = document.querySelectorAll('[data-id]');
        const totalSum = document.querySelector('.footer-cart__price');
        const totalProductsEl = document.querySelector('.footer-cart__items-qty');
        let totalProductsQty = parseInt(totalProductsEl.textContent.trim());
        let totalSumValue = parseFloat(totalSum.textContent.trim().replace(',', '.').replace(' ', '')); 

        products.forEach(product => {
            let productQty = parseInt(product.querySelector('.quantity__input input').value.trim());
            let maxQty = parseInt(product.dataset.qty.trim());
            const productPrice = parseFloat(product.querySelector('.product-price__value').textContent.trim().replace(',', '.').replace(' ', ''));
            const minusButton = product.querySelector('.quantity__button_minus');
            const plusButton = product.querySelector('.quantity__button_plus');
            const deleteButton = product.querySelector('.cart-item__delete');

            function initPlusMinusButtons() {  // handle on server
                if (productQty >= maxQty) {
                    plusButton.disabled = true;
                    plusButton.style.backgroundColor = "#b3b3b3";
                }
                if (productQty <= 1) {
                    minusButton.disabled = true;
                    minusButton.style.backgroundColor = "#b3b3b3";
                }
            }
            initPlusMinusButtons();
            
            
            minusButton.addEventListener('click', function() {
                if (productQty > 1) {
                    totalSum.textContent = formatNumber(totalSumValue - productPrice);
                    totalSumValue -= productPrice;
                    productQty--;
                    totalProductsQty--;
                    cart.dataset.qty = parseInt(cart.dataset.qty) - 1;
                    totalProductsEl.textContent = totalProductsQty;
                }
                if (productQty === 1) {
                    minusButton.disabled = true;
                    minusButton.style.backgroundColor = "#b3b3b3";
                }
                if (productQty < maxQty) {
                    plusButton.disabled = false;
                    plusButton.style.backgroundColor = "";
                }
            });
            plusButton.addEventListener('click', function() {
                if (productQty < maxQty) {
                    totalSum.textContent = formatNumber(totalSumValue + productPrice);
                    totalSumValue += productPrice;
                    productQty++;
                    totalProductsQty++;
                    cart.dataset.qty = parseInt(cart.dataset.qty) + 1;
                    totalProductsEl.textContent = totalProductsQty;
                }
                if (productQty > 1) {
                    minusButton.disabled = false;
                    minusButton.style.backgroundColor = "";
                }
                if (productQty === maxQty) {
                    plusButton.disabled = true;
                    plusButton.style.backgroundColor = "#b3b3b3";
                }
            });

            deleteButton.addEventListener('click', async function(){
                const response = await fetchSecured('/cart/delete', {
                    method: "DELETE",
                    body: JSON.stringify({
                        product_id: parseInt(product.dataset.id),
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (!response) return;
                const data = await response.json();
                if (!response.ok) {
                    handleNotification(response.status, data.message);
                    return;
                }
                handleNotification(response.status, data.message);
                product.remove();
                totalSum.textContent = formatNumber(totalSumValue - (productPrice * productQty));
                totalSumValue -= (productPrice * productQty); 
                totalProductsQty -= productQty;
                cart.dataset.qty = parseInt(cart.dataset.qty) - productQty;
                totalProductsEl.textContent = totalProductsQty;
                if (totalProductsQty === 0) {
                    const cartBody = document.querySelector('.cart__body');
                    cartBody.innerHTML = `
                    <div class=\"cart__no-items\">
                        <div class="cart__no-items-image">
                            <img src="img/home/empty-cart.svg" alt="no items in cart">
                        </div>
                        <div class="cart__no-items-text">No items in cart</div>
                    </div>
                    `;
                }
            });
        });

        const mq = window.matchMedia("(max-width: 992px)");
        if (mq.matches) {
            const windowSize = window.innerHeight;
            const cartFooterHeight = document.querySelector('.cart__footer').scrollHeight;
            const headerHeight = document.querySelector('.header').scrollHeight;
            const cartBody = document.querySelector('.cart__body');
            cartBody.style.minHeight = (windowSize - cartFooterHeight - headerHeight) + "px";
        }
    }
}
main();

async function fetchSecured(url, options){
    const response = await fetch(url, options);
    if (response.status === 401 || response.status === 302) {
        const data = await response.json();
        window.location.replace(data.redirect);
        return null;
    }
    
    return response;
}


function handleNotification(code, msg) {
    let type;
    
    if (code >= 200 && code < 300) {
        type = '_success';
    } else if (code >= 100 && code < 200 || code >= 300 && code < 400) {
        type = '_alert';
    } else if (code >= 400) {
        type = '_failure';
    }
    const notificationContainer = document.querySelector('.notification__container');
    
    const notificationItem = document.createElement('div');
    notificationItem.classList.add('notification__item');
    setTimeout(() => {
        notificationItem.classList.add('_active');
    }, 0)
    notificationContainer.appendChild(notificationItem);

    const notificationBody = document.createElement('div');
    notificationBody.classList.add('notification__body');
    
    notificationBody.classList.add(type);
    notificationItem.appendChild(notificationBody);

    const notificationMessage = document.createElement('div');
    notificationMessage.classList.add('notification__message');
    notificationMessage.classList.add(`_icon-notif${type}`);
    notificationMessage.textContent = msg;
    notificationBody.appendChild(notificationMessage);

    const notificationTime = document.createElement('div');
    notificationTime.classList.add('notification__time');
    setTimeout(() => {
        notificationTime.classList.add('_start');
    }, 500)
    notificationBody.appendChild(notificationTime);

    setTimeout(() => {
        notificationItem.classList.remove('_active');
        setTimeout(() => {
            notificationItem.remove();
        }, 500)
    }, 3500);
}
const formatNumber = (number) => {
    return Intl.NumberFormat(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(number)
}

const handleSearch = () => {
    const search = document.querySelector('.search-header__form');
    const searchInput = document.querySelector('.search-header__search');
    let result = document.querySelector('.search-header__result');
    let val;
    let timeID;
    if (search){
        if (!result) {
            result = document.createElement('div');
            result.classList.add('search-header__result');
            search.parentNode.appendChild(result);
        }
    
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-header__form')) {
                result.style.display = 'none';
            } else {
                result.style.display = 'block';
            }
        })
    
        searchInput.addEventListener('input', function(e){
            const inputValue = e.target.value.trim();
            searchInput.value = searchInput.value.replace(/^\s+/, '');
    
            if (val != inputValue){
                clearTimeout(timeID);
            }
            
            if (inputValue.length < 2){
                result.innerHTML = '';
                return;
            }
            
            
            timeID = setTimeout(() => {
                sendSearch(inputValue);
            }, 700);
            val = inputValue;
            result.innerHTML = '<ul class="search-header__result-list">Searching..</ul>';
    
        });
    
        const sendSearch = async (inputValue) => {
            const response = await fetch(`/search/get?query=${encodeURIComponent(inputValue)}`, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json"
                },
            });
            const data = await response.json();
            if (!response.ok) {
                handleNotification(response.status, "Try again later");
                return;
            }
    
            result.innerHTML = '';
            const resultList = document.createElement('ul');
            resultList.classList.add('search-header__result-list');
    
            if (data.length === 0){
                resultList.textContent = "No results";
                result.appendChild(resultList);
                return;
            }
    
            data.forEach(searchItem => {
                const resEl = document.createElement('li');
                resEl.classList.add('search-header__result-item');
                const startInd = searchItem.title.toLowerCase().search(inputValue.toLowerCase());
                const endInd = startInd + inputValue.length;
                const resultString = `<a href="/product/${escapeHTML(searchItem.alias)}">` +
                escapeHTML(searchItem.title.slice(0, startInd)) + "<b>" + 
                escapeHTML(searchItem.title.slice(startInd, endInd)) + "</b>" + 
                escapeHTML(searchItem.title.slice(endInd)) + 
                "</a>";
                resEl.innerHTML = resultString;
                resultList.appendChild(resEl);
            });
            result.appendChild(resultList);
            search.parentNode.appendChild(result);
        }
    }
}
handleSearch();

const escapeHTML = (str) => str.replace(/[&<>"']/g, (char) => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
}[char]));

const handleForms = () => {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const authContainer = form.closest('.auth__container');
        form.addEventListener('formValidated', async (e) => {
            const formData = new FormData(form);
            const isLogin = authContainer.classList.contains('_login');
            const action = isLogin ? 'login' : 'signup';
            const response = await fetch(`/user/${action}`, {
                method: "POST",
                body: formData,
            });
            const data = await response.json();
            handleNotification(response.status, data.message);
            if (response.ok) {
                window.location.replace("/");
            }
        })
    })
}
handleForms();
