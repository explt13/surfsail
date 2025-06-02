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
            deleteFromFavorite('product');
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
        data = await secureFetch(`/api/currency`, {}, NOTIFY_ON_FAILURE);
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
        const data = await secureFetch(`/api/currency`, {
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
        let productQtyEl = product.querySelector('.quantity__input input');
        let productQty = parseInt(product.querySelector('.quantity__input input').value.trim());
        let maxQty = parseInt(product.dataset.qty.trim());
        const productPrice = parseFloat(product.querySelector('.product-price__value').textContent.trim().replace(',', '.').replace(' ', ''));
        const minusButton = product.querySelector('.quantity__button_minus');
        const plusButton = product.querySelector('.quantity__button_plus');
        const deleteButton = product.querySelector('.cart-item__delete');

        initPlusMinusButtons(plusButton, minusButton, productQty, maxQty);
        minusButton.addEventListener('click', async function() {
            handleMinusButton(this, plusButton, productQty, productPrice, maxQty);
            productQtyEl.value = --productQty;
            await debounceAddToCartFewProducts(product, productQty, false);
        });

        plusButton.addEventListener('click', async function() {
            handlePlusButton(this, minusButton, productQty, productPrice, maxQty);
            productQtyEl.value = ++productQty;
            await debounceAddToCartFewProducts(product, productQty, false);
        });

        deleteButton.addEventListener('click', async function(){
            await secureFetch(`/api/cart/items/${product.dataset.id}`, {
                method: "DELETE",
            });
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

async function addToCartFewProducts(product, qty, update_time, notify = 3) {
    await secureFetch('/api/cart/items', {
        method: 'POST',
        body: JSON.stringify({
            product_id: parseInt(product.dataset.id),
            qty,
            update_time,
        })
    }, notify);
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
        const itemId = item.dataset.id;
        const deleteButton = item.querySelector('.cart-item__delete');
        deleteButton.addEventListener('click', async function(){
            await secureFetch(`/api/favorite/${entity}/items/${itemId}`, {
                method: "DELETE",
            });
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
    

    const cartButton = document.querySelector('.cart-button-view');
    if (!cartButton) return;

    const cart = document.querySelector('.shop__cart');
    const productTableParams = document.querySelector('.body-product__table');
    const product = cartButton.closest('[data-id]');
    const buyButton = document.querySelector('.actions-product__buy');
    const minusButton = document.querySelector('.quantity__button_minus');
    const plusButton = document.querySelector('.quantity__button_plus');
    let productQty = parseInt(product.querySelector('.quantity__input input').value.trim());
    let in_cart = productQty > 0;
    let productQtyInput = product.querySelector('.quantity__input input');
    let maxQty = parseInt(product.dataset.qty.trim());

    if (productTableParams.innerHTML.trim() === '') {
        productTableParams.remove();
    }
    
    cartButton.addEventListener('click', async function() {
        await addToCartFewProducts(product, productQty, true);
        in_cart = true;
        cart.dataset.qty = parseInt(cart.dataset.qty) + 1;
    });
    
    const updateQtyButtons = () => {
        minusButton.disabled = productQty <= 0;
        plusButton.disabled = productQty >= maxQty;
    };
    updateQtyButtons();

    const debounceAddToCartFewProducts = debounceAsync(addToCartFewProducts, 1000);

    minusButton.addEventListener('click', async function() {
        --productQty;
        if (productQty >= 0) {
            productQtyInput.value = productQty;
            updateQtyButtons();
        }
        if (in_cart) {
            if (productQty === 0) {
                await secureFetch(`/api/cart/items/${product.dataset.id}`, {
                    method: "DELETE",
                });
                in_cart = false;
                cart.dataset.qty = parseInt(cart.dataset.qty) - 1;
                return;
            }

            await debounceAddToCartFewProducts(product, productQty, true);
        }
    });
    
    plusButton.addEventListener('click', function() {
        ++productQty;
        if (productQty <= maxQty) {
            productQtyInput.value = productQty;
            updateQtyButtons();
        }
    });
}

async function addProductToCartFromShowcase() {
    const cartButtons = document.querySelectorAll('.cart-button');
    const buttonEventHandlers = new Map();

    const setCartButtons = async () => {
        let data = [];
        try {
            data = await secureFetch('/api/cart/items', {}, NOTIFY_ON_FAILURE);
        } catch (e) {
            console.warn('Couldn\'t load added products from cart');
            console.error(e);
        }
        cartButtons.forEach((button) => {
            const productId = parseInt(button.closest('[data-product][data-id]').dataset.id);
            if (data.includes(productId)) {
                setButton(button);
            }
            button.addEventListener('click', function() {
                if (data.includes(productId)) {
                    deleteFromCart(productId);
                    const index = data.indexOf(productId);
                    if (index > -1) data.splice(index, 1);
                } else {
                    addToCart(productId);
                    if (!data.includes(productId)) data.push(productId);
                }
            })
        })
    }
    await setCartButtons();

    async function addToCart(productId) {
        await secureFetch('/api/cart/items', {
            method: 'POST',
            body: JSON.stringify({
                product_id: productId,
                qty: 1,
            })
        }, NOTIFY_ON_SUCCESS | NOTIFY_ON_FAILURE);
        const cart = document.querySelector('.shop__cart');
        if (cart) {
            cart.dataset.qty = parseInt(cart.dataset.qty) + 1;
        };
        const sameProducts = document.querySelectorAll(`[data-product][data-id="${productId}"]`);

        sameProducts.forEach(product => {
            const button = product.querySelector('.cart-button');
            setButton(button);
        });
    }

    async function deleteFromCart(productId) {
        await secureFetch(`/api/cart/items/${productId}`, {
            method: 'DELETE',
        }, NOTIFY_ON_SUCCESS | NOTIFY_ON_FAILURE);
        const cart = document.querySelector('.shop__cart');
        if (cart) {
            cart.dataset.qty = parseInt(cart.dataset.qty) - 1;
        };
        const sameProducts = document.querySelectorAll(`[data-product][data-id="${productId}"]`);

        sameProducts.forEach(product => {
            const button = product.querySelector('.cart-button');
            unsetButton(button);
        })
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
        button.textContent = 'To cart';
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
        alreadyInFav = await secureFetch(`/api/favorite/${entity}/items`, {}, NOTIFY_ON_FAILURE);
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
    const data = await secureFetch(`/api/favorite/${entity}/items`, {
        method: "POST",
        body: JSON.stringify({
            item_id: parseInt(item.dataset.id),
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
    const data = await secureFetch(`/api/search?query=${encodeURIComponent(inputValue)}`, {}, NOTIFY_ON_FAILURE);
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
        await secureFetch(`/api/user/${authFormMethod}`, {
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
        let filterUrl = '/api/catalog?';
        applyFiltersBtn.addEventListener('click', async function() {
            getFilterUrl();
            const data = await secureFetch(filterUrl);
            const catalogContent = document.querySelector('.catalog__content');
            catalogContent.innerHTML = '<div class="loader"></div>';

            if (catalogContent) {
                catalogContent.innerHTML = data.html;
            }
            window.history.pushState({}, "", filterUrl);
            filterUrl = '/api/catalog?';
            
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
            const params = new URLSearchParams(window.location.search);
            if (params.get('f').indexOf(filterGroup) !== -1) {
                const [min, max] = slider.noUiSlider.get(true);
                if (!filters[filterGroup]) {
                    filters[filterGroup] = [];
                }
                filters[filterGroup] = [min, max];
            }
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
        const filterUrl = `/api/${dist}${filterStr}`;
        const data = await secureFetch(filterUrl);
        const catalogContent = document.querySelector('.catalog__content');
        catalogContent.innerHTML = data.html;
        window.history.pushState({}, "", filterUrl.replace('/api', ''));
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