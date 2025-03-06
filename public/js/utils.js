function isEmptyObject(obj) {
    return Object.keys(obj).length === 0;
}

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


const formatNumber = (number) => {
    return Intl.NumberFormat(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(number)
}
String().replace()

const escapeHTML = (str) => str.replace(/[&<>"']/g, (char) => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
}[char]));

/**
 * 
 * @param {mixed} value value to check if null
 * @returns {boolean}
 */
const is_null = (value) => {
    return value === null;
}

const NOTIFY_ON_SUCCESS = 1;
const NOTIFY_ON_FAILURE = 2;

/**
 * 
 * @param {string} url - url to fetch the resource 
 * @param {object} options - fetch options, default method GET, predefined headers:\
 * 'X-Requested-With': 'XMLHttpRequest'\
 * 'Content-Type': 'application/json'
 * @param {int} notifyOnResult default NOTIFY_ON_SUCCESS | NOTIFY_ON_FAILURE
 * @returns {mixed}
 * @throws RequestFailed
 */

async function secureFetch(url, options={}, notifyOnResult = NOTIFY_ON_SUCCESS | NOTIFY_ON_FAILURE){
    showSpinner();
    const response = await fetch(url, {
        method: 'GET',
        ...options,

        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            ...options.headers,
        }
    });
    const data = await handleResponse(response, notifyOnResult);
    hideSpinner();
    return data;
}

const handleResponse = async (response, notifyOnResult) => {
    let result;
    let data = {};
    let succeed = false;
    switch (true) {
        case (response.status > 199 && response.status < 300):
            const text = await response.text();
            try {
                result = data = JSON.parse(text);
            } catch (e) {
                result = text;
            }
            succeed = true;
            break;

        case (response.status === 401):
            data = await response.json();
            localStorage.setItem('postponed_notification', JSON.stringify({status: response.status, message: data.message, notify: notifyOnResult}));
            window.history.pushState({}, "", window.location.href);
            window.location.replace(data.redirect);
            return null;
        
        // assuming 400 - 599 requests go here having appropriate error message
        default:
            data = await response.json();
            result = null;
            break;
    }

    if ((notifyOnResult & NOTIFY_ON_FAILURE) && !succeed) {
        nSender(response.status, data.message ?? 'Operation has failed. Try again later');
        
    }
    if ((notifyOnResult & NOTIFY_ON_SUCCESS) && succeed) {
        nSender(response.status, data.message ?? 'Operation successful');
    }
    return result;
}

const notificationSender = () => {
    const notification = document.querySelector('.notification');
    return function (code, msg) {
        notification.dispatchEvent(new CustomEvent('notification-sent', {detail: {code, msg}}));
    }
}
const nSender = notificationSender();

const showPostponedNotification = () => {
    const postponedNotification = localStorage.getItem('postponed_notification');
    if (postponedNotification) {
        const notificationItem = JSON.parse(localStorage.getItem('postponed_notification'));
        setTimeout(() => {
            nSender(notificationItem.status, notificationItem.message);
        }, 500)
        localStorage.removeItem('postponed_notification');
    }
    
}


const spinner = document.querySelector('.loader-wrapper');
function showSpinner() {
    spinner.style.display = 'block';
}

function hideSpinner() {
    spinner.style.display = 'none';
}

/**
 * @param {function} callback a function to be executed after **wait** time passes
 * @param {int} wait time **(in ms)** to wait before execute a function
 * @returns function(...args) { callback(...args) }
 */
function debounce(callback, wait) {
    let timeId;
    return function(...args) {
        clearTimeout(timeId);
        timeId = setTimeout(() => {
            callback(...args);
        }, wait)
    }
}
function debounceAsync(callback, wait) {
    let timeId;
    return function(...args) {
        return new Promise((resolve, reject) => {
            clearTimeout(timeId);
            timeId = setTimeout(async() => {
                const data = await callback(...args);
                resolve(data);
            }, wait);
        })
    }
}

function sleep(seconds) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            resolve();
        }, seconds * 1000)
    })
}

export {isEmptyObject, getCookie, setCookie, secureFetch, formatNumber, escapeHTML, showSpinner, debounce, debounceAsync, sleep, NOTIFY_ON_FAILURE, NOTIFY_ON_SUCCESS, is_null, showPostponedNotification, nSender};
