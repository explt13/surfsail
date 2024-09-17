
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

const options = document.querySelectorAll('.select_information__city .select__option');

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
        if (!response.ok) {
            throw new Error(response.statusText);
        }
        const data = await response.json();
        calculatePrices(data.currency);
    })
})

let prevCurrencyValue = null;

async function getCurrentCurrency(){
    const response = await fetch(`/currency/get`);
    if (!response.ok) {
        throw new Error(response.statusText);
    }
    const data = await response.json();
    prevCurrencyValue = data.currency.value;
}
getCurrentCurrency();

function calculatePrices(currency) {
    const currentPrices = document.querySelectorAll('.product-price__value');
    const symbolPrice = document.querySelectorAll('.product-price__symbol');
    if (currentPrices.length > 0) {
        currentPrices.forEach(price => {
            const priceValue = Number(price.textContent.trim().replace(',', '.').replace(' ', ''));
            price.previousElementSibling.textContent = currency.symbol;
            price.textContent = Intl.NumberFormat(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(priceValue * (currency.value / prevCurrencyValue));
        })
    }
    prevCurrencyValue = currency.value;
}


function renderCartProductsQty() {
    const cartProducts = JSON.parse(localStorage.getItem('cart'));
    if (cartProducts) {
        const productsQty = cartProducts.length;        
        const shoppingCart = document.querySelector('.shop__cart');
        shoppingCart.dataset.qty = productsQty;
    }   
}
renderCartProductsQty();

function handleAddAction() {
    const cartButtons = document.querySelectorAll('.cart-button');    
    const inCartArray = decodeURIComponent(getCookie('cart') ?? "[]");
    const buttonEventHandlers = new Map();
    
    cartButtons.forEach(button => {
        const product = button.closest('[data-id]');
        if (inCartArray.includes(product.dataset.id)) {
            setButton(button);
        }
        button.addEventListener('click', async function(){
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    "Content-Type": 'application/json',
                },
                body: JSON.stringify({
                    product_id:(product.dataset.id),
                })
            })
            if (!response.ok) {
                handleNotification(response.status, response.statusText);
            }
            const data = await response.json();
            handleNotification(response.status, data.message);
            if (data.action === 'add') {
                setButton(this);
            } else if (data.action === 'remove') {
                unsetButton(this);
            }
        })
    });

    function setButton(button) {
        button.textContent = 'In cart';
        button.style.backgroundColor = '#228b22';
        button.style.transition = 'background-color 0.2s ease-in-out';
        const onMouseEnter = function() {
            this.style.backgroundColor = '#e7401b';
            this.textContent = 'Remove from cart';
        };
        const onMouseLeave = function() {
            this.style.backgroundColor = '#228b22';
            this.textContent = 'In cart';
        };
        buttonEventHandlers.set(button, { onMouseEnter, onMouseLeave });

        button.addEventListener('mouseenter', onMouseEnter);
        button.addEventListener('mouseleave', onMouseLeave);
        
    }
    function unsetButton(button) {
        button.style.backgroundColor = 'var(--mainColor)';
        button.textContent = 'Add to cart';
        const handlers = buttonEventHandlers.get(button);
        if (handlers) {
            button.removeEventListener('mouseenter', handlers.onMouseEnter);
            button.removeEventListener('mouseleave', handlers.onMouseLeave);
        }
        buttonEventHandlers.delete(button)
    }
 
}
handleAddAction();

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