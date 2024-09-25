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
            const response = await fetch(`/currency/change`, {
                method: "POST",
                headers: {
                    "Content-Type": 'application/json',
                },
                body: JSON.stringify({
                    currency: currency,
                }),
            });
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

    if (page.classList.contains('page_product' || 'page_home')) {
        const cartButtons = document.querySelectorAll('.cart-button');
        const cartButtonView = document.querySelector('.cart-button-view');
        if (cartButtonView) {
            const productTableParams = document.querySelector('.body-product__table');
            const buyButton = document.querySelector('.actions-product__buy');
            const product = cartButtonView.closest('[data-id]');
            let qty = parseInt(product.querySelector('.quantity__input input').value);
            if (productTableParams.innerHTML.trim() === ''){
                productTableParams.remove();
            }
            cartButtonView.addEventListener('click', async function(){
                addToCart(this, product, true, qty);
            });
            const minusButton = document.querySelector('.quantity__button_minus');
            const plusButton = document.querySelector('.quantity__button_plus');
            minusButton.addEventListener('click', function() {
                if (qty > 1) {
                    qty--;
                }
                if (qty <= product.dataset.qty) {
                    [cartButtonView, buyButton].forEach(button => {
                        button.style.backgroundColor = "";
                        button.style.cursor = "";
                        button.disabled = false;
                        button.style.boxShadow = "";
                    })
                }
            });
            plusButton.addEventListener('click', function() {
                qty++;
                if (qty > product.dataset.qty) {
                    [cartButtonView, buyButton].forEach(button => {
                        button.style.backgroundColor = "#b3b3b3";
                        button.style.cursor = "default";
                        button.disabled = true;
                        button.style.boxShadow = "0 0 0 0";
                    })
                }
            });
        }

        const inCartArray = JSON.parse(decodeURIComponent(getCookie('cart') ?? "[]"));
        const buttonEventHandlers = new Map();
        const cart = document.querySelector('.shop__cart');
        cartButtons.forEach(button => {
            const product = button.closest('[data-id]');
            if (inCartArray.hasOwnProperty(product.dataset.id)) {
                setButton(button);
            }
            button.addEventListener('click', async function(){
                addToCart(this, product);
            })
        });

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
        async function addToCart(button, product, fromView = false, qty = 1) {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    "Content-Type": 'application/json',
                },
                body: JSON.stringify({
                    product_id: (product.dataset.id),
                    qty: qty,
                    fromView: fromView,
                })
            })
            const data = await response.json();
            if (!response.ok) {
                handleNotification(response.status, data.message);
                return;
            }
            handleNotification(response.status, data.message);
        
            if (!fromView) {
                if (data.action === 'add') {
                    cart.dataset.qty++;
                    const addedProducts = document.querySelectorAll(`[data-id="${product.dataset.id}"]`);
                    addedProducts.forEach(product => {
                        const button = product.querySelector('.cart-button');
                        setButton(button);
                    })
                } else if (data.action === 'remove') {
                    cart.dataset.qty--;
                    const removedProducts = document.querySelectorAll(`[data-id="${product.dataset.id}"]`)
                    removedProducts.forEach(product => {
                        const button = product.querySelector('.cart-button');
                        unsetButton(button);
                    })
                }
            } else {
                const cartQty = parseInt(cart.dataset.qty) + qty;
                cart.dataset.qty = cartQty;
            }
        }
    }

    if (page.classList.contains('page_cart')) {
        const products = document.querySelectorAll('[data-id]');
        const totalSum = document.querySelector('.footer-cart__price');
        let productsItems = products.length;
        let totalSumValue = parseFloat(totalSum.textContent.trim().replace(',', '.').replace(' ', ''));
        products.forEach(product => {
            let productQty = parseInt(product.querySelector('.quantity__input input').value.trim());
            let maxQty = parseInt(product.dataset.qty.trim());
            const productPrice = parseFloat(product.querySelector('.product-price__value').textContent.trim().replace(',', '.').replace(' ', ''));
            const minusButton = product.querySelector('.quantity__button_minus');
            const plusButton = product.querySelector('.quantity__button_plus');
            const deleteButton = product.querySelector('.cart-item__delete');
            maxQty = 10;
            

            function initButtons() {
                if (productQty >= maxQty) {
                    plusButton.disabled = true;
                    plusButton.style.backgroundColor = "#b3b3b3";
                }
                if (productQty <= 1) {
                    minusButton.disabled = true;
                    minusButton.style.backgroundColor = "#b3b3b3";
                }
            }
            initButtons();
            
            
            minusButton.addEventListener('click', function() {
                if (productQty > 1) {
                    const remainder = formatNumber(totalSumValue - productPrice);
                    totalSum.textContent = remainder;
                    totalSumValue = parseFloat(remainder.replace(',', '.').replace(/[\s&nbsp;]+/g, ''));
                    productQty--;
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
                const response = await fetch('/cart/delete', {
                    method: "POST",
                    body: JSON.stringify({
                        product_id: product.dataset.id,
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });
                const data = await response.json();
                if (!response.ok) {
                    handleNotification(response.status, data.message);
                    return;
                }
                handleNotification(response.status, data.message);
                product.remove();
                totalSum.textContent = formatNumber(totalSumValue - (productPrice * productQty));
                totalSumValue -= (productPrice * productQty); 
                productsItems--;
                if (productsItems === 0) {
                    const cartBody = document.querySelector('.cart__body');
                    cartBody.innerHTML = `
                    <div class=\"cart__no-items\">
                        <div class="cart__no-items-image">
                            <img src="img/cart/empty-cart.svg" alt="no items in cart">
                        </div>
                        <div class="cart__no-items-text">No items in cart</div>
                    </div>
                    `;
                }
            });
        });


        const mq = window.matchMedia("(max-width: 991px)");
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





function handleNotification(code, msg) {
    let type;
    
    if (code >= 200 && code < 300) {
        type = '_success'
    } else if (code >= 100 && code < 200 || code >= 300 && code < 400) {
        type = '_alert'
    } else if (code >= 400) {
        type = '_failure'
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
