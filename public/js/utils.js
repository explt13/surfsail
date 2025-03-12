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

class FetchDataError extends Error {
    constructor (message, details) {
        super(message)
        if (!details.message) {
            details.message = 'Operation has failed. Try again later';
        }
        this._details = details;
    }

    getDetails() {
        return this._details;
    }
}

class BadRequestError extends FetchDataError {}

class InternalServerError extends FetchDataError {}

class UndefinedResponseError extends FetchDataError {
    constructor() {
        super('Undefined Response Error', {status: 500, message: 'Operation has failed. Try again later'})
    }
}

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

async function secureFetch(url, options={}, notifyOnResult = NOTIFY_ON_SUCCESS | NOTIFY_ON_FAILURE) {
    let executed = false;
    // if it takes more than 1 sec show spinner;
    setTimeout(() => {
        if (!executed) {
            showSpinner();
        }
    }, 1000);
    const response = await fetch(url, {
        method: 'GET',
        ...options,

        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            ...options.headers,
        }
    });
    try {
        const data = await getResponseData(response);
        if (notifyOnResult & NOTIFY_ON_SUCCESS) {
            nSender(response.status, data.message ?? 'Operation successful');
        }
        return data;
    } catch (e) {
        if (e instanceof FetchDataError) {
            const errorDetails = e.getDetails();
            if ((notifyOnResult & NOTIFY_ON_FAILURE)) {
                nSender(errorDetails.status, errorDetails.message);
            }
        }
        throw e;
    } finally {
        executed = true;
        hideSpinner();
    }
}

const getResponseData = async (response) => {
    const data = await response.json();
    switch (true) {
        
        // Success
        case (response.status >= 200 && response.status < 300):
            return data;
            
        // redirected
        case (response.status === 401 || response.status === 302):
            localStorage.setItem('postponed_notification', JSON.stringify({status: response.status, message: data.message}));
            window.history.pushState({}, "", window.location.href);
            window.location.replace(data.redirect);
            return;

        // Client Errors
        case (response.status >= 400 && response.status < 500):
            throw new BadRequestError(data.message, {status: response.status});

        // Server Errors
        case (response.status >= 500 && response.status < 600):
            throw new InternalServerError(data.message, {status: response.status});

        // Fallback
        default:
            throw new UndefinedResponseError();
    }
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
    spinner.style.removeProperty('display');
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

function debounceAsync(callback, wait, showSpinnerOnWaiting=false) {
    let timeId;
    return function(...args) {
        return new Promise((resolve, reject) => {
            clearTimeout(timeId);
            if (showSpinnerOnWaiting) showSpinner()
            timeId = setTimeout(async() => {
                const data = await callback(...args);
                if (showSpinnerOnWaiting) hideSpinner();
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
