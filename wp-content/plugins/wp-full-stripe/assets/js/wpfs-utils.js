/**
 * Created by tnagy on 2018.06.26..
 */

/**
 * Formats a currency amount in smallest common currency unit to display as a String.
 *
 * @param amount in smallest common currency unit
 * @param zeroDecimalSupport
 */
function formatCurrencyAmount(amount, zeroDecimalSupport) {
    var theAmount = parseInt(amount);
    if (!isNaN(theAmount)) {
        if (zeroDecimalSupport == true) {
            return theAmount.toFixed(0);
        } else {
            theAmount = theAmount / 100;
            return theAmount.toFixed(2);
        }
    }
    return amount;
}

function parseCurrencyAmount(amount, zeroDecimalSupport, returnSmallestCommonCurrencyUnit) {
    var theAmount;
    if (zeroDecimalSupport == true) {
        theAmount = parseInt(amount);
    } else {
        theAmount = parseFloat(amount);
    }
    if (!isNaN(theAmount)) {
        if (returnSmallestCommonCurrencyUnit) {
            if (zeroDecimalSupport == false) {
                theAmount = Math.round(theAmount * 100);
            }
        }
    }
    return theAmount;
}

/**
 * Apply coupon to the given amount.
 *
 * @param currency
 * @param amount in smallest common currency unit
 * @param coupon
 * @returns {result}
 */

/**
 *
 * @param currency
 * @param amount
 * @param coupon
 * @returns {{amountCurrency: *, amount: *, couponCurrency: (OPTIONS.currency|{importance, type, default}|*|Array), discount: number, discountType: *, total: number, error: *}}
 */
function applyCoupon(currency, amount, coupon) {
    // console.log('applyCoupon(): CALLED, currency=' + currency + ', amount=' + amount + ', coupon=' + JSON.stringify(coupon));
    var discount = 0;
    var discountType = null;
    var error = null;
    if (coupon != null) {
        //noinspection JSUnresolvedVariable
        if (coupon.hasOwnProperty('percent_off') && coupon.percent_off != null) {
            discountType = 'percent_off';
            //noinspection JSUnresolvedVariable
            var percentOff = parseInt(coupon.percent_off) / 100;
            discount = Math.round(amount * percentOff);
        } else {
            //noinspection JSUnresolvedVariable
            if (coupon.hasOwnProperty('amount_off') && coupon.amount_off != null) {
                discountType = 'amount_off';
                if (coupon.hasOwnProperty('currency')) {
                    if (coupon.currency == currency) {
                        //noinspection JSUnresolvedVariable
                        discount = parseInt(coupon.amount_off);
                    } else {
                        error = 'currency mismatch';
                    }
                } else {
                    error = 'currency mismatch';
                }
            } else {
                error = 'invalid coupon';
            }
        }
    }
    var amountAsInteger = parseInt(amount);
    var total = amountAsInteger - discount;

    var result = {
        amountCurrency: currency,
        amount: amountAsInteger,
        couponCurrency: (coupon != null && coupon.hasOwnProperty('currency') ? coupon.currency : null),
        discount: discount,
        discountType: discountType,
        discountPercentOff: (coupon != null && coupon.hasOwnProperty('percent_off') ? coupon.percent_off : null),
        total: total,
        error: error
    };

    // console.log('applyCoupon(): result=' + JSON.stringify(result));

    return result;
}

function calculateVATAmount(amount, vatPercent) {
    return Math.round(amount * vatPercent / 100);
}

function logError(handlerName, jqXHR, textStatus, errorThrown) {
    if (window.console) {
        console.log(handlerName + '.error(): textStatus=' + textStatus);
        console.log(handlerName + '.error(): errorThrown=' + errorThrown);
        if (jqXHR) {
            console.log(handlerName + '.error(): jqXHR.status=' + jqXHR.status);
            console.log(handlerName + '.error(): jqXHR.responseText=' + jqXHR.responseText);
        }
    }
}

function logInfo(handlerName, message) {
    if (window.console) {
        console.log(handlerName + '  INFO: ' + message);
    }
}

function logWarn(handlerName, message) {
    if (window.console) {
        console.log(handlerName + '  WARN: ' + message);
    }
}

// function logError(handlerName, message) {
//     if (window.console) {
//         console.log(handlerName + ' ERROR: ' + message);
//     }
// }

function logException(formId, exception) {
    if (window.console && exception) {
        if (exception.message) {
            console.log('ERROR: formId=' + formId + ', message=' + exception.message);
        }
    }
}

function logResponseException(source, response) {
    if (window.console && response) {
        if (response.ex_msg) {
            console.log('ERROR: source=' + source + ', message=' + response.ex_msg);
        }
    }
}
