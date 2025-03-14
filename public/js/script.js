import {isEmptyObject, secureFetch, getCookie, setCookie, formatNumber, escapeHTML, debounce, debounceAsync, sleep, NOTIFY_ON_SUCCESS, NOTIFY_ON_FAILURE, is_null, showPostponedNotification} from './utils.js';

async function main() {
    const page = window.location.pathname.split('/')[1] || 'main';
    reloadPageOnPopState();
    showPostponedNotification();
    handleSearch();
    await handleCurrency();
    const handlers = {
        favorite: async () => {
            await setFavoriteButtons('product');
            deleteFromFavorite();
            favoriteMinHeightCalc();
        },
        cart: () => {
            setFavoriteButtons('product');
            handleCart();
        }, 
        product: () => {
            setFavoriteButtons('product');
            addProductFromProductPage();
            addProductToCartFromShowcase();
        },
        main: () => {
            setFavoriteButtons('product');
            addProductToCartFromShowcase();
        },
        auth: () => {
            authenticate();
        },
        catalog: () => {
            setFavoriteButtons('product');
            addProductToCartFromShowcase();
            handleFiltersRealTime('catalog');
        }
    }
    handlers[page]();
}


const handleCurrency = async () => {
    let data;
    try {
        data = await secureFetch(`/currency/get`, {}, NOTIFY_ON_FAILURE);
    } catch (e) {
        console.warn('Couldn\'t init currency changing functionality');
        console.error(e);
        return;
    }
    let currentCurrencyValue = data.currency.value;
    const options = document.querySelectorAll('.select_currency .select__option');
    
    options.forEach(option => {
        option.addEventListener('click', async function(e) {
            const data = await changeCurrency(e);
            calculatePrices(data.currency);
            currentCurrencyValue = data.value.value;
        })
    });
   
    async function changeCurrency(e) {
        const currency = e.target.dataset.value;
        const data = await secureFetch(`/currency/change`, {
            method: "POST",
            body: JSON.stringify({
                currency: currency,
            }),
        });
        return data;
    }
    
    function calculatePrices(currency) {
        const currentPrices = document.querySelectorAll('.product-price__value');
        if (currentPrices.length > 0) {
            currentPrices.forEach(price => {
                const priceValue = Number(price.textContent.trim().replace(',', '.').replace(' ', ''));
                price.previousElementSibling.textContent = currency.symbol;
                price.textContent = formatNumber(priceValue * (currency.value / currentCurrencyValue));
            })
        }
    }
}

function handleCart() {
    const cart = document.querySelector('.shop__cart');
    const products = document.querySelectorAll('[data-id]');
    const totalSum = document.querySelector('.footer-cart__price');
    const totalProductsEl = document.querySelector('.footer-cart__items-qty');
    let totalProductsQty = parseInt(totalProductsEl.textContent.trim());
    let totalSumValue = parseFloat(totalSum.textContent.trim().replace(',', '.').replace(' ', ''));

    function initPlusMinusButtons(plusButton, minusButton, productQty, maxQty) {
        if (productQty >= maxQty) {
            plusButton.disabled = true;
            plusButton.style.backgroundColor = "#b3b3b3";
        }
        if (productQty <= 1) {
            minusButton.disabled = true;
            minusButton.style.backgroundColor = "#b3b3b3";
        }
    }

    function handleMinusButton(minusButton, plusButton, productQty, productPrice, maxQty) {
        if (productQty > 1) {
            totalSum.textContent = formatNumber(totalSumValue - productPrice);
            totalSumValue -= productPrice;
            productQty--;
            totalProductsQty--;
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
    }

    function handlePlusButton(plusButton, minusButton, productQty, productPrice, maxQty) {
        if (productQty < maxQty) {
            totalSum.textContent = formatNumber(totalSumValue + productPrice);
            totalSumValue += productPrice;
            productQty++;
            totalProductsQty++;
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
    }

    function renderEmptyCart() {
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
    const debounceAddToCartFewProducts = debounceAsync(addToCartFewProducts, 1000);
    products.forEach(product => {
        let productQty = parseInt(product.querySelector('.quantity__input input').value.trim());
        let maxQty = parseInt(product.dataset.qty.trim());
        const productPrice = parseFloat(product.querySelector('.product-price__value').textContent.trim().replace(',', '.').replace(' ', ''));
        const minusButton = product.querySelector('.quantity__button_minus');
        const plusButton = product.querySelector('.quantity__button_plus');
        const deleteButton = product.querySelector('.cart-item__delete');

        initPlusMinusButtons(plusButton, minusButton, productQty, maxQty);
        minusButton.addEventListener('click', async function() {
            await debounceAddToCartFewProducts(product, productQty, 'direct_control', false);
            handleMinusButton(this, plusButton, productQty, productPrice, maxQty);
        });

        plusButton.addEventListener('click', async function() {
            await debounceAddToCartFewProducts(product, productQty, 'direct_control', false);
            handlePlusButton(this, minusButton, productQty, productPrice, maxQty);
        });

        deleteButton.addEventListener('click', async function(){
            await deleteProductFromAdded('cart', product);
            product.remove();
            totalSum.textContent = formatNumber(totalSumValue - (productPrice * productQty));
            totalSumValue -= (productPrice * productQty);
            totalProductsQty -= productQty;
            cart.dataset.qty = parseInt(cart.dataset.qty) - 1;
            totalProductsEl.textContent = totalProductsQty;
            if (totalProductsQty === 0) {
                renderEmptyCart();
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

async function addToCartFewProducts(product, qty, mode, update_time) {
    await secureFetch('/cart/add-multiple', {
        method: 'POST',
        body: JSON.stringify({
            product_id: parseInt(product.dataset.id),
            qty,
            mode,
            update_time,
        })
    });
}

async function deleteProductFromAdded(deleteFrom, item) {
    await secureFetch(`/${deleteFrom}/delete`, {
        method: "DELETE",
        body: JSON.stringify({
            item_id: parseInt(item.dataset.id),
        }),
        headers: {
            'Content-Type': 'application/json',
        }
    });
}

function deleteFromFavorite(entity) {
    const items = document.querySelectorAll('[data-id]');
    let itemsQty = items.length;

    function renderEmptyFav() {
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

    items.forEach(item => {
        const deleteButton = product.querySelector('.cart-item__delete');
        deleteButton.addEventListener('click', async function(){
            await deleteProductFromAdded(`favorite/${entity}`, item);
            item.remove();
            itemsQty--;
            if (itemsQty === 0) {
                renderEmptyFav();
            }
        });
    })
}

const favoriteMinHeightCalc = () => {
    const mq = window.matchMedia("(min-width: 992px)");
    if (mq.matches) {
        const windowSize = window.innerHeight;
        const headerHeight = document.querySelector('.header').scrollHeight;
        const cartBody = document.querySelector('.cart__body');
        cartBody.style.minHeight = (windowSize - headerHeight) + "px";
    }
}

function addProductFromProductPage() {
    const cartButtonView = document.querySelector('.cart-button-view');
    if (!cartButtonView) return;

    const cart = document.querySelector('.shop__cart');
    const productTableParams = document.querySelector('.body-product__table');
    const product = cartButtonView.closest('[data-id]');
    const buyButton = document.querySelector('.actions-product__buy');
    const minusButton = document.querySelector('.quantity__button_minus');
    const plusButton = document.querySelector('.quantity__button_plus');
    let alreadyHaveQtyEl = document.querySelector('.actions-product__cart-have b');
    let productQty = parseInt(product.querySelector('.quantity__input input').value.trim()); // 1
    let maxQty = parseInt(product.dataset.qty.trim());
    let alreadyHaveQtyValue = 0;
    
    if (alreadyHaveQtyEl) {
        alreadyHaveQtyValue = parseInt(alreadyHaveQtyEl.textContent.trim());
    }
    
    if (productTableParams.innerHTML.trim() === '') {
        productTableParams.remove();
    }

    function renderAddProduct() {
        const buttonsActions = document.querySelector('.actions-product__buttons');
        buttonsActions.insertAdjacentHTML('afterbegin', '<button class="actions-product__delete"><img src="img/home/trash.svg" /></button>');
        registerDeleteEvent();
        const span = document.createElement('span');
        span.classList.add('actions-product__cart-have');
        span.innerHTML = `(you have <b>${productQty}</b> in cart)`;
        cartButtonView.appendChild(span);
    }

    function setProductQty() {
        if (!alreadyHaveQtyEl) {
            renderAddProduct();
            alreadyHaveQtyEl = cartButtonView.querySelector('.actions-product__cart-have b');
            alreadyHaveQtyValue = parseInt(alreadyHaveQtyEl.textContent.trim());
        } else {
            alreadyHaveQtyValue += productQty;
            alreadyHaveQtyEl.textContent = alreadyHaveQtyValue;
        }
        
        if (productQty > maxQty - alreadyHaveQtyValue){
            productQty = maxQty - alreadyHaveQtyValue;
            if (productQty < 1) {
                product.querySelector('.quantity__input input').value = 1;
            } else {
                product.querySelector('.quantity__input input').value = productQty;
            }
        }
    }
    
    cartButtonView.addEventListener('click', async function() {
        await addToCartFewProducts(product, productQty, 'addup', true);
        cart.dataset.qty = parseInt(cart.dataset.qty) + 1;
        setProductQty();
        updateQtyButtons();
    });
    
    const updateQtyButtons = () => {
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
    updateQtyButtons();
    
    minusButton.addEventListener('click', function() {
        if (productQty > 1) {
            productQty--;
            product.querySelector('.quantity__input input').value = productQty;
            updateQtyButtons();
        }
    });
    
    plusButton.addEventListener('click', function() {
        if (productQty < maxQty) {
            productQty++;
            product.querySelector('.quantity__input input').value = productQty;
            updateQtyButtons();
        }
    });

    const registerDeleteEvent = () => {
        const deleteButton = document.querySelector('.actions-product__delete');
        if (deleteButton){
            deleteButton.addEventListener('click', async function () {
                await secureFetch('/cart/delete', {
                    method: "DELETE",
                    body: JSON.stringify({
                        product_id: parseInt(product.dataset.id),
                    }),
                });
                cart.dataset.qty = parseInt(cart.dataset.qty) - 1;
                alreadyHaveQtyValue = 0;
                alreadyHaveQtyEl.textContent = 0;
                updateQtyButtons();
                deleteButton.remove();
            })
        }
    }
    registerDeleteEvent();
}

async function addProductToCartFromShowcase() {
    const cartButtons = document.querySelectorAll('.cart-button');
    const buttonEventHandlers = new Map();
    const setCartButtons = async () => {
        let data = [];
        try {
            data = await secureFetch('/cart/get-added-items', {}, NOTIFY_ON_FAILURE);
        } catch (e) {
            console.warn('Couldn\'t load added products from cart');
            console.error(e);
        }
        cartButtons.forEach(button => {
            const product = button.closest('[data-id]');
            if (!product) return;
            if (data.includes(parseInt(product.dataset.id))){
                setButton(button);
            }
            button.addEventListener('click', async function(){
                addToCart(product);
            })
        });
    }
    await setCartButtons();

    async function addToCart(product) {
        const data = await secureFetch('/cart/add', {
            method: 'POST',
            body: JSON.stringify({
                product_id: parseInt(product.dataset.id),
                qty: 1,
            })
        }, NOTIFY_ON_SUCCESS);
        const cart = document.querySelector('.shop__cart');
        if (!cart) return;
        const sameProducts = document.querySelectorAll(`[data-id="${product.dataset.id}"]`);

        if (data.action === 'add') {
            cart.dataset.qty = parseInt(cart.dataset.qty) + 1;
            sameProducts.forEach(product => {
                const button = product.querySelector('.cart-button');
                setButton(button);
            });
        } else if (data.action === 'remove') {
            cart.dataset.qty = parseInt(cart.dataset.qty) - 1;
            sameProducts.forEach(product => {
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

async function setFavoriteButtons(entity) {
    const favoriteButtons = document.querySelectorAll('.like');
    let alreadyInFav = [];
    try {
        alreadyInFav = await secureFetch(`/favorite/${entity}/get-added-items`, {}, NOTIFY_ON_FAILURE);
    } catch (e) {
        console.warn(`Couldn\'t load favorite ${entity}s`);
        console.error(e);
    }
    favoriteButtons.forEach(button => {
        const item = button.closest('[data-id]');
        if (alreadyInFav.includes(parseInt(item.dataset.id))){
            button.classList.add('_in-favorite');
        }
        button.addEventListener('click', async function(e) {
            await addToFavorite(item, entity);
        })

    })
}

const addToFavorite = async (item, entity) => {
    const data = await secureFetch(`/favorite/add`, {
        method: "POST",
        body: JSON.stringify({
            item_id: parseInt(item.dataset.id),
            entity: entity,
        })
    });
    const sameItems = document.querySelectorAll(`[data-id="${item.dataset.id}"]`);
    if (data.action === 'add'){
        sameItems.forEach(item => {
            const button = item.querySelector('.like');
            button.classList.add('_in-favorite');
        });
    } else if (data.action === 'remove') {
        sameItems.forEach(item => {
            const button = item.querySelector('.like');
            button.classList.remove('_in-favorite');
        })
    }
}

const handleSearch = () => {
    const searchEl = document.querySelector('.search-header__form');
    const searchInput = document.querySelector('.search-header__search');
    const resultEl = document.querySelector('.search-header__result');
    if (!searchEl || !searchInput) return;

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-header__form')) {
            resultEl.style.display = 'none';
        } else {
            resultEl.style.display = 'block';
        }
    })
    searchInput.addEventListener('input', async function(e){
        const inputValue = e.target.value.trim();
        searchInput.value = searchInput.value.replace(/^\s+/, '');

        if (inputValue.length < 2){
            resultEl.innerHTML = '';
            return;
        }
        resultEl.innerHTML = '<ul class="search-header__result-list">Searching..</ul>';
        try {
            const searchResultList = await sendSearch(inputValue);
            renderSearchResult(searchResultList, inputValue, resultEl, searchEl);
        } catch (e) {
            resultEl.innerHTML = '<ul class="search-header__result-list">Searching problems, try again later</ul>';
        }
    });
}

const sendSearch = debounceAsync(async (inputValue) => {
    const data = await secureFetch(`/search/get?query=${encodeURIComponent(inputValue)}`, {}, NOTIFY_ON_FAILURE);
    return data;
}, 700);

const renderSearchResult = (data, inputValue, resultEl, searchEl) => {
    resultEl.innerHTML = '';
    const resultList = document.createElement('ul');
    resultList.classList.add('search-header__result-list');

    if (data.length === 0){
        resultList.textContent = "No results";
        resultEl.appendChild(resultList);
        return;
    }

    data.forEach(searchItem => {
        const resEl = document.createElement('li');
        resEl.classList.add('search-header__result-item');
        const startInd = searchItem.name.toLowerCase().search(inputValue.toLowerCase());
        const endInd = startInd + inputValue.length;
        const resultString = `<a href="/product/${escapeHTML(searchItem.alias)}">` +
        escapeHTML(searchItem.name.slice(0, startInd)) + "<b>" + 
        escapeHTML(searchItem.name.slice(startInd, endInd)) + "</b>" + 
        escapeHTML(searchItem.name.slice(endInd)) + 
        "</a>";
        resEl.innerHTML = resultString;
        resultList.appendChild(resEl);
    });
    resultEl.appendChild(resultList);
    searchEl.parentNode.appendChild(resultEl);
}

const authenticate = () => {
    const authForm = document.querySelector('.auth__form');
    authForm.addEventListener('formValidated', async (e) => {
        const params = new URLSearchParams(window.location.search);
        const authFormMethod = params.get('form');
        const authRedirecTo = params.get('r_link') ?? '';
        const formData = new FormData(authForm);
        await secureFetch(`/user/${authFormMethod}`, {
            method: "POST",
            body: formData,
            headers: {
                'Content-Type': null,
            }
        });
        window.location.replace(window.location.origin + authRedirecTo);
    })
}
const handleFilters = () => {
    const applyFiltersBtn = document.querySelector('.filter-catalog__apply-button');
    if (applyFiltersBtn) {
        let filterUrl = '/catalog?';
        applyFiltersBtn.addEventListener('click', async function() {
            getFilterUrl();
            const data = await secureFetch(filterUrl);
            const catalogContent = document.querySelector('.catalog__content');
            catalogContent.innerHTML = '<div class="loader"></div>';

            if (catalogContent) {
                catalogContent.innerHTML = data.html;
            }
            window.history.pushState({}, "", filterUrl);
            filterUrl = '/catalog?';
            
        });
    
        function getFilterUrl() {
            filterUrl += 'f='
            const filterItems = document.querySelectorAll('.item-filter');
            if (filterItems.length > 0) {
                filterItems.forEach((filterItem, index) => {
                    const groupNameEl = filterItem.querySelector('.item-filter__title');
                    const groupNameType = filterItem.dataset.filtertype;
                    const groupName = groupNameEl.dataset.alias;
        
                    switch (groupNameType) {
                        case 'checkbox':
                            getCheckboxGroup(groupName, filterItem);
                            break;
                        case 'range':
                            getRangeGroup(groupName, filterItem);
                            break;
                        case 'radio':
                            getRadioGroup(groupName, filterItem);
                            break;
                    }
                })
            }
        }

        function getCheckboxGroup(groupName, filterItem) {
            const checkedOptions = filterItem.querySelectorAll('input:checked');
            if (checkedOptions.length > 0) {
                const values = Array.from(checkedOptions)
                .map(el => el.value)
                .join(',');
                filterUrl += encodeURIComponent(groupName + ':' + values + ';');
            }
        }

        function getRangeGroup(groupName, filterItem) {
            const rangeMinEl = filterItem.querySelector('[data-range-from]');
            const rangeMaxEl = filterItem.querySelector('[data-range-to]');
            if (rangeMinEl && rangeMaxEl) {
                const rangeMin = rangeMinEl.value.replace(/[\$\s]/g, '');
                const rangeMax = rangeMaxEl.value.replace(/[\$\s]/g, '');
                filterUrl += encodeURIComponent(groupName + ':' + rangeMin + ',' + rangeMax + ';');
            }
            else if (rangeMinEl) {
                const rangeMin = rangeMinEl.value;
                filterUrl += encodeURIComponent(groupName + ':' + rangeMin + ';');
            }
            else if (rangeMaxEl) {
                const rangeMax = rangeMaxEl.value;
                filterUrl += encodeURIComponent(groupName + ':' + rangeMax + ';');
            }
        }
        
        function getRadioGroup(groupName, filterItem) {
            const checked = filterItem.querySelector('input:checked');
            if (checked) {
                filterUrl += encodeURIComponent(groupName + ':' + checked.value + ';');
            }
        }
    }
}

function handleFiltersRealTime(dist) {
    const filters = {};
    const filterItems = document.querySelectorAll('.item-filter');

    filterItems.forEach(filterItem => {
        const filterGroup = filterItem.querySelector('.item-filter__title').dataset.alias;
        switch (filterItem.dataset.filtertype) {
            case 'range':
                getRange(filterItem, filterGroup);
                break;
            case 'checkbox':
                getCheckbox(filterItem, filterGroup);
                break;
            case 'radio':
        }
    });

    function getRange(filterItem, filterGroup) {
        const slider = filterItem.querySelector('[data-range-item]');
        if (slider) {
            slider.noUiSlider.on('change', async function(vals) {
                vals = vals.map((v) => Number(v.replace(/[\$\s]/g, '')));
                filters[filterGroup] = vals;
                await debouncedFilters();
            })
        }
    }

    function getCheckbox(filterItem, filterGroup) {
        const inputs = filterItem.querySelectorAll('input');
        inputs.forEach(input => {
            initInputsPerGroup(filterGroup, input);
            input.addEventListener('click', async function(e) {
                if (!filters[filterGroup]) {
                    filters[filterGroup] = [];
                }
                if (!filters[filterGroup].includes(input.value)) {
                    filters[filterGroup].push(input.value);
                } else {
                    const index = filters[filterGroup].indexOf(input.value);
                    filters[filterGroup].splice(index, 1);
                    if (filters[filterGroup].length === 0) {
                        delete filters[filterGroup];
                    }
                }
                await debouncedFilters();
            });
        })
    }

    function initInputsPerGroup(filterGroup, input) {
        if (input.checked) {
            if (!filters[filterGroup]) {
                filters[filterGroup] = [];
            }
            filters[filterGroup].push(input.value);
        }
    }

    async function applyFilters() {
        const filterStr = prepareFilterStr('?f=');
        const filterUrl = `/${dist}${filterStr}`;
        const data = await secureFetch(filterUrl);
        const catalogContent = document.querySelector('.catalog__content');
        catalogContent.innerHTML = data.html;
        window.history.pushState({}, "", filterUrl);
    }
    const debouncedFilters = debounceAsync(applyFilters, 1500);

    function prepareFilterStr(filterStr) {
        if (isEmptyObject(filters)) {
            return '';
        };
        Object.entries(filters).forEach(([key, values]) => {
            filterStr += encodeURIComponent(key + ":" + values.join(',') + ";");
        })
        return filterStr;
    }
}

const reloadPageOnPopState = () => {
    window.addEventListener('popstate', async function() {
        window.location.reload();
    }) 
}
try {
    await main();
    console.log('successfully initialized')
} catch (e) {
    console.error(e);
}