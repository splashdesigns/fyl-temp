jQuery.noConflict();
(function ($) {

    "use strict";

    $(function () {


            // var wpfsCardUpdateSessionData = {};

            var reCAPTCHAWidgetId = null;
            var googleReCAPTCHA = null;
            window.addEventListener('load', function () {
                var emailFormCAPTCHA = document.getElementById('wpfs-enter-email-address-form-recaptcha');
                //noinspection JSUnresolvedVariable
                if (window.grecaptcha !== 'undefined' && emailFormCAPTCHA !== null) {
                    //noinspection JSUnresolvedVariable
                    googleReCAPTCHA = window.grecaptcha;
                    //noinspection JSUnresolvedVariable
                    var parameters = {
                        "sitekey": wpfsGoogleReCAPTCHASiteKey
                    };
                    reCAPTCHAWidgetId = googleReCAPTCHA.render(emailFormCAPTCHA, parameters);
                }
            }, true);

            function scrollToElement($anElement) {
                if ($anElement && $anElement.offset() && $anElement.offset().top) {
                    $('html, body').animate({
                        scrollTop: $anElement.offset().top - 100
                    }, 1000);
                }
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

            function resetCaptcha() {
                if (googleReCAPTCHA != null && reCAPTCHAWidgetId != null) {
                    googleReCAPTCHA.reset(reCAPTCHAWidgetId);
                }
            }

            function showLoadingIcon($form) {
                $form.find('button').addClass('wpfs-btn-primary--loader');
            }

            function hideLoadingIcon($form) {
                $form.find('button').removeClass('wpfs-btn-primary--loader');
            }

            function disableSubmitButton($form) {
                $form.find('button').prop('disabled', true);
            }

            function enableSubmitButton($form) {
                $form.find('button').prop('disabled', false);
            }

            function showFieldError($field, fieldErrorMessage) {
                $field.addClass('wpfs-form-control--error');
                var $fieldError = $('<div>', {
                    class: 'wpfs-form-error-message'
                }).html(fieldErrorMessage);
                $fieldError.insertAfter($field);
            }

            function clearFieldErrors($form) {
                $('.wpfs-form-control--error', $form).removeClass('wpfs-form-control--error');
                $('div.wpfs-form-error-message', $form).remove();
            }

            function getParentForm(element) {
                return $(element).parents('form:first');
            }

            function clearFormFeedBack($form) {
                var $formFeedBack = $form.prev('.wpfs-form-message');
                if ($formFeedBack.length > 0) {
                    $formFeedBack.remove();
                }
            }

            function showFormFeedBackSuccess($form, message) {
                var $formFeedBack = $('<div>', {
                    class: 'wpfs-form-message wpfs-form-message--correct wpfs-form-message--sm-icon '
                }).html(message);
                $formFeedBack.insertBefore($form);
            }

            function showFormFeedBackError($form, message) {
                var $formFeedBack = $('<div>', {
                    class: 'wpfs-form-message wpfs-form-message--incorrect wpfs-form-message--sm-icon '
                }).html(message);
                $formFeedBack.insertBefore($form);
            }

            function stripeTokenHandler($form, token) {

                clearFormFeedBack($form);
                clearFieldErrors($form);

                //noinspection JSUnresolvedVariable
                $.ajax({
                    type: 'POST',
                    url: wpfsAjaxURL,
                    data: {
                        action: 'wp_full_stripe_update_card',
                        sessionId: wpfsCardUpdateSessionData.sessionId,
                        token: token
                    },
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                        if (data.success) {
                            showFormFeedBackSuccess($form, data.message);
                            setTimeout(function () {
                                window.location = window.location.pathname;
                            }, 1000);
                        } else {
                            //noinspection JSUnresolvedVariable
                            if (data.ex_message) {
                                //noinspection JSUnresolvedVariable
                                showFormFeedBackError($form, data.ex_message);
                            } else {
                                console.log(JSON.stringify(data));
                            }
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        logError('stripeTokenHandler', jqXHR, textStatus, errorThrown);
                    },
                    complete: function () {
                        hideLoadingIcon($form);
                        enableSubmitButton($form);
                    }
                });

            }

            function updateCancelSubscriptionSubmitButton() {
                var selectedSubscriptionCount = $('.wpfs-form-check-input:checked').length;
                var cancelSubscriptionSubmitButtonCaption = null;
                if (selectedSubscriptionCount > 0) {
                    $('#wpfs-button-cancel-subscription').prop('disabled', false);
                    if (selectedSubscriptionCount == 1) {
                        //noinspection JSUnresolvedVariable
                        if (wpfsCardUpdateSessionData !== 'undefined') {
                            //noinspection JSUnresolvedVariable
                            cancelSubscriptionSubmitButtonCaption = wpfsCardUpdateSessionData.i18n.cancelSubscriptionSubmitButtonCaptionSingular;
                            $('#wpfs-button-cancel-subscription').html(cancelSubscriptionSubmitButtonCaption);
                        }
                    } else {
                        //noinspection JSUnresolvedVariable
                        if (wpfsCardUpdateSessionData !== 'undefined') {
                            //noinspection JSUnresolvedVariable
                            cancelSubscriptionSubmitButtonCaption = vsprintf(wpfsCardUpdateSessionData.i18n.cancelSubscriptionSubmitButtonCaptionPlural, [selectedSubscriptionCount]);
                            $('#wpfs-button-cancel-subscription').html(cancelSubscriptionSubmitButtonCaption);
                        }
                    }
                } else {
                    $('#wpfs-button-cancel-subscription').prop('disabled', true);
                    //noinspection JSUnresolvedVariable
                    if (wpfsCardUpdateSessionData !== 'undefined') {
                        //noinspection JSUnresolvedVariable
                        cancelSubscriptionSubmitButtonCaption = wpfsCardUpdateSessionData.i18n.cancelSubscriptionSubmitButtonCaptionDefault;
                        $('#wpfs-button-cancel-subscription').html(cancelSubscriptionSubmitButtonCaption);
                    }
                }
            }

            //noinspection JSUnresolvedVariable
            var stripe = Stripe(wpfsStripeKey);

            var WPFS = {};

            WPFS.initEnterEmailAddressForm = function () {
                $('#wpfs-enter-email-address-form').submit(function (e) {

                    e.preventDefault();

                    var $form = $(this);

                    clearFieldErrors($form);
                    disableSubmitButton($form);
                    showLoadingIcon($form);

                    var emailAddress = $form.find('input[name="wpfs-email-address"]').val();
                    var googleReCAPTCHAResponse = $form.find('textarea[name="g-recaptcha-response"]').val();

                    $.ajax({
                        type: 'POST',
                        url: wpfsAjaxURL,
                        data: {
                            action: 'wp_full_stripe_create_card_update_session',
                            emailAddress: emailAddress,
                            googleReCAPTCHAResponse: googleReCAPTCHAResponse
                        },
                        cache: false,
                        dataType: 'json',
                        success: function (data) {
                            if (data.success) {
                                window.location = window.location.pathname;
                            } else {
                                var $field;
                                if (data.fieldError && 'emailAddress' === data.fieldError) {
                                    $field = $('input[name="wpfs-email-address"]', $form);
                                } else if (data.fieldError && 'googleReCAPTCHAResponse' === data.fieldError) {
                                    $field = $('div#wpfs-enter-email-address-form-recaptcha', $form);
                                }
                                showFieldError($field, data.message);
                                resetCaptcha();
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            logError('wpfs-enter-email-address-form.submit', jqXHR, textStatus, errorThrown);
                        },
                        complete: function () {
                            enableSubmitButton($form);
                            hideLoadingIcon($form);
                        }
                    });

                    return false;
                });
            };
            WPFS.initEnterSecurityCodeForm = function () {
                $('.wpfs-nav-back-to-email-address').click(function (e) {

                    e.preventDefault();

                    var $form = getParentForm(this);

                    disableSubmitButton($form);
                    showLoadingIcon($form);

                    $.ajax({
                        type: 'POST',
                        url: wpfsAjaxURL,
                        data: {
                            action: 'wp_full_stripe_reset_card_update_session',
                            sessionId: wpfsCardUpdateSessionData.sessionId
                        },
                        cache: false,
                        dataType: 'json',
                        success: function (data) {
                            window.location = window.location.pathname;
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            logError('.wpfs-nav-back-to-email-address.click', jqXHR, textStatus, errorThrown);
                        },
                        complete: function () {
                            enableSubmitButton($form);
                            hideLoadingIcon($form);
                        }
                    });

                    return false;
                });
                $('#wpfs-enter-security-code-form').submit(function (e) {

                    e.preventDefault();

                    var $form = $(this);

                    disableSubmitButton($form);
                    clearFieldErrors($form);
                    showLoadingIcon($form);

                    var securityCode = $('input[name="wpfs-security-code"]', $form).val();

                    //noinspection JSUnresolvedVariable
                    $.ajax({
                        type: 'POST',
                        url: wpfsAjaxURL,
                        data: {
                            action: 'wp_full_stripe_validate_security_code',
                            sessionId: wpfsCardUpdateSessionData.sessionId,
                            securityCode: securityCode
                        },
                        cache: false,
                        dataType: 'json',
                        success: function (data) {
                            if (data.success) {
                                window.location = window.location.pathname;
                            } else {
                                showFieldError($('input[name="wpfs-security-code"]', $form), data.message);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            logError('#wpfs-enter-security-code-form.submit', jqXHR, textStatus, errorThrown);
                        },
                        complete: function () {
                            enableSubmitButton($form);
                            hideLoadingIcon($form);
                        }
                    });

                    return false;
                });
            };
            WPFS.initUpdateCardForm = function () {
                // tnagy init Stripe Elements Card
                var $card = $('[data-toggle="card"]');
                var elements;
                var card;
                if ($card.length > 0) {
                    elements = stripe.elements();
                    card = elements.create('card', {
                        hidePostalCode: true,
                        classes: {
                            base: 'wpfs-form-card',
                            empty: 'wpfs-form-control--empty',
                            focus: 'wpfs-form-control--focus',
                            complete: 'wpfs-form-control--complete',
                            invalid: 'wpfs-form-control--error'
                        },
                        style: {
                            base: {
                                color: '#2F2F37',
                                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Oxygen-Sans", Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
                                fontSmoothing: 'antialiased',
                                fontSize: '15px',
                                '::placeholder': {
                                    color: '#7F8393'
                                }
                            },
                            invalid: {
                                color: '#2F2F37',
                                iconColor: '#CC3434'
                            }
                        }
                    });

                    card.mount('[data-toggle="card"]');
                    card.addEventListener('change', function (event) {
                        var $form = getParentForm(this);
                        if (event.error) {
                            clearFieldErrors($form);
                            showFieldError($('#wpfs-card', $form), event.error.message);
                        } else {
                            clearFieldErrors($form);
                        }
                    });
                }

                // tnagy init card update form
                $('#wpfs-anchor-logout').click(function (e) {
                    e.preventDefault();
                    //noinspection JSUnresolvedVariable
                    $.ajax({
                        type: 'POST',
                        url: wpfsAjaxURL,
                        data: {
                            action: 'wp_full_stripe_reset_card_update_session',
                            sessionId: wpfsCardUpdateSessionData.sessionId
                        },
                        cache: false,
                        dataType: 'json',
                        success: function (data) {
                            window.location = window.location.pathname;
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            logError('#wpfs-anchor-logout.click', jqXHR, textStatus, errorThrown);
                        },
                        complete: function () {
                        }
                    });
                });
                $('#wpfs-anchor-update-card').click(function () {
                    if (card != null) {
                        card.clear();
                    }
                    $('#wpfs-default-card-form').hide();
                    $('#wpfs-update-card-form').show();
                    card.focus();
                });
                $('#wpfs-anchor-discard-card-changes').click(function () {
                    if (card != null) {
                        card.clear();
                    }
                    $('#wpfs-default-card-form').show();
                    $('#wpfs-update-card-form').hide();
                });
                $('#wpfs-update-card-form').submit(function (e) {

                    e.preventDefault();

                    var $form = $(this);

                    disableSubmitButton($form);
                    showLoadingIcon($form);
                    clearFormFeedBack($form);

                    stripe.createToken(card).then(function (result) {
                        if (result.error) {
                            enableSubmitButton($form);
                            hideLoadingIcon($form);
                            showFieldError($('#wpfs-card', $form), result.error.message);
                        } else {
                            stripeTokenHandler($form, result.token);
                        }
                    });

                    return false;
                });
            };
            WPFS.initCancelSubscriptionForm = function () {
                updateCancelSubscriptionSubmitButton();
                $('.wpfs-form-check-input').on('change', function (e) {
                    updateCancelSubscriptionSubmitButton();
                });
                $('#wpfs-cancel-subscription-form').submit(function (e) {
                    e.preventDefault();

                    var $form = $(this);

                    disableSubmitButton($form);
                    showLoadingIcon($form);
                    clearFormFeedBack($form);

                    // tnagy create form data array
                    var data = $form.serializeArray();
                    // tnagy add action and session ID 
                    data.push({name: "action", value: 'wp_full_stripe_cancel_my_subscription'});
                    //noinspection JSUnresolvedVariable
                    data.push({name: "sessionId", value: wpfsCardUpdateSessionData.sessionId});

                    // tnagy collect selected subscription IDs
                    var selectedSubscriptionIds = [];
                    for (var i = 0; i < data.length; i++) {
                        var item = data[i];
                        if (item && item.name && item.name == 'wpfs-subscription-id[]') {
                            selectedSubscriptionIds.push(item.value);
                        }
                    }

                    // tnagy validate selection
                    var valid = true;
                    if (selectedSubscriptionIds.length == 0) {
                        valid = false;
                        //noinspection JSUnresolvedVariable
                        showFormFeedBackError($form, wpfsCardUpdateSessionData.i18n.selectAtLeastOneSubscription);
                    }

                    if (valid) {

                        //noinspection JSUnresolvedVariable
                        var confirmationResult = confirm(wpfsCardUpdateSessionData.i18n.confirmSubscriptionCancellationMessage);

                        if (confirmationResult == true) {
                            //noinspection JSUnresolvedVariable
                            $.ajax({
                                type: 'POST',
                                url: wpfsAjaxURL,
                                data: $.param(data),
                                cache: false,
                                dataType: 'json',
                                success: function (data) {
                                    if (data.success) {
                                        showFormFeedBackSuccess($form, data.message);
                                        setTimeout(function () {
                                            window.location = window.location.pathname;
                                        }, 1000);
                                    } else {
                                        showFormFeedBackError($form, data.message);
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    logError('#wpfs-cancel-subscription-form.submit', jqXHR, textStatus, errorThrown);
                                },
                                complete: function () {
                                    enableSubmitButton($form);
                                    hideLoadingIcon($form);
                                }
                            });
                        } else {
                            enableSubmitButton($form);
                            hideLoadingIcon($form);
                        }
                    } else {
                        enableSubmitButton($form);
                        hideLoadingIcon($form);
                    }

                    return false;

                });
            };
            WPFS.ready = function () {
                // tnagy scroll to forms gently
                var $wpfsEnterEmailAddressForm = $('#wpfs-enter-email-address-form');
                var $wpfsEnterSecurityCodeForm = $('#wpfs-enter-security-code-form');
                var $wpfsManageSubscriptionsContainer = $('#wpfs-manage-subscriptions-container');
                if ($wpfsEnterEmailAddressForm.length > 0) {
                    scrollToElement($wpfsEnterEmailAddressForm);
                }
                if ($wpfsEnterSecurityCodeForm.length > 0) {
                    scrollToElement($wpfsEnterSecurityCodeForm);
                }
                if ($wpfsManageSubscriptionsContainer.length > 0) {
                    scrollToElement($wpfsManageSubscriptionsContainer);
                }
            };

            WPFS.initEnterEmailAddressForm();
            WPFS.initEnterSecurityCodeForm();
            WPFS.initUpdateCardForm();
            WPFS.initCancelSubscriptionForm();

            WPFS.ready();
        }
    );

})(jQuery);