function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
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