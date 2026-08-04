/**
 * VK Logistics - Ganesh Statue Booking JS Application
 */

$(document).ready(function () {
    // Dynamic Settings & Pricing Data
    let unitPrice = 14.99;
    let shippingCharge = 3.99;
    let currencySymbol = '£';

    // Fetch live settings on load
    fetchSettings();

    // Mobile Navigation Toggle
    $('#mobile-menu-toggle').on('click', function () {
        $('#nav-menu').toggleClass('active');
    });

    // Quantity Increment / Decrement
    $('.qty-btn.minus').on('click', function () {
        let currentVal = parseInt($('#quantity-input').val()) || 1;
        if (currentVal > 1) {
            updateQuantity(currentVal - 1);
        }
    });

    $('.qty-btn.plus').on('click', function () {
        let currentVal = parseInt($('#quantity-input').val()) || 1;
        if (currentVal < 20) {
            updateQuantity(currentVal + 1);
        }
    });

    $('#quantity-input').on('change keyup', function () {
        let val = parseInt($(this).val()) || 1;
        if (val < 1) val = 1;
        if (val > 20) val = 20;
        updateQuantity(val);
    });

    function updateQuantity(qty) {
        $('#quantity-input').val(qty);
        $('#form-quantity').val(qty);
        recalculateTotals(qty);
    }

    function recalculateTotals(qty) {
        let subtotal = (qty * unitPrice).toFixed(2);
        let total = (parseFloat(subtotal) + shippingCharge).toFixed(2);

        // Update UI displays
        $('.display-qty').text(qty);
        $('.display-subtotal').text(currencySymbol + subtotal);
        $('.display-shipping').text(currencySymbol + shippingCharge.toFixed(2));
        $('.display-total').text(currencySymbol + total);
        
        // Update product showcase summary
        $('#calc-breakdown').text(`${qty} × ${currencySymbol}${unitPrice.toFixed(2)} + ${currencySymbol}${shippingCharge.toFixed(2)} shipping`);
        $('#calc-grand-total').text(currencySymbol + total);
    }

    // Payment Tab Switcher
    $('.payment-tab-btn').on('click', function () {
        let targetTab = $(this).data('tab');
        $('.payment-tab-btn').removeClass('active');
        $(this).addClass('active');

        $('.payment-tab-content').removeClass('active');
        $('#' + targetTab).addClass('active');

        $('#payment_method').val(targetTab === 'paypal-tab' ? 'paypal' : 'bank_transfer');
    });

    // Smooth Scroll to Booking Form
    $('.scroll-to-booking').on('click', function (e) {
        e.preventDefault();
        let target = $('#booking-section');
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 80
            }, 600);
        }
    });

    // UK Postcode Formatting & Live Validation
    $('#postcode').on('blur keyup', function () {
        let raw = $(this).val().toUpperCase();
        $(this).val(raw);
    });

    // FAQ Accordion Toggle
    $('.faq-question').on('click', function () {
        let parent = $(this).closest('.faq-item');
        parent.toggleClass('active');
        parent.find('.faq-answer').slideToggle(300);
    });

    // Bank Transfer Booking Form Submission
    $('#bank-transfer-form').on('submit', function (e) {
        e.preventDefault();
        
        // Front-end validation
        if (!validateBookingForm()) {
            return;
        }

        let submitBtn = $('#btn-submit-bank');
        let originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('Creating your booking...');

        let formData = {
            csrf_token: $('#csrf_token').val(),
            customer_name: $('#customer_name').val().trim(),
            mobile: $('#mobile').val().trim(),
            email: $('#email').val().trim(),
            address_line_1: $('#address_line_1').val().trim(),
            address_line_2: $('#address_line_2').val().trim(),
            city: $('#city').val().trim(),
            county: $('#county').val().trim(),
            postcode: $('#postcode').val().trim(),
            quantity: $('#quantity-input').val(),
            payment_method: 'bank_transfer',
            payment_reference: $('#payment_reference').val().trim()
        };

        if (!formData.payment_reference) {
            showToast('Please enter your Bank Transfer payment reference number.', 'error');
            submitBtn.prop('disabled', false).html(originalText);
            return;
        }

        $.ajax({
            url: 'ajax/create-booking.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    showToast('Booking created! Redirecting to confirmation...', 'success');
                    setTimeout(function () {
                        window.location.href = res.redirect_url;
                    }, 1200);
                } else {
                    showToast(res.message || 'Error creating booking', 'error');
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function (xhr) {
                let err = xhr.responseJSON ? xhr.responseJSON.message : 'Server error occurred.';
                showToast(err, 'error');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Helper: Form Validation
    function validateBookingForm() {
        let name = $('#customer_name').val().trim();
        let mobile = $('#mobile').val().trim();
        let email = $('#email').val().trim();
        let addr1 = $('#address_line_1').val().trim();
        let city = $('#city').val().trim();
        let postcode = $('#postcode').val().trim();

        if (!name) {
            showToast('Please enter your full name.', 'error');
            $('#customer_name').focus();
            return false;
        }

        // UK Mobile regex
        let mobileRegex = /^(?:\+44|0)7\d{9}$/;
        let cleanMobile = mobile.replace(/[\s\-\(\)]/g, '');
        if (!mobileRegex.test(cleanMobile)) {
            showToast('Please enter a valid UK mobile number (+44 7... or 07...).', 'error');
            $('#mobile').focus();
            return false;
        }

        // Email regex
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showToast('Please enter a valid email address.', 'error');
            $('#email').focus();
            return false;
        }

        if (!addr1) {
            showToast('Please enter your address line 1.', 'error');
            $('#address_line_1').focus();
            return false;
        }

        if (!city) {
            showToast('Please enter your town or city.', 'error');
            $('#city').focus();
            return false;
        }

        // UK Postcode regex
        let pcRegex = /^(GIR0AA|(?:[A-PR-UWYZ][0-9][0-9]?|[A-PR-UWYZ][A-HK-Y][0-9][0-9]?|[A-PR-UWYZ][0-9][A-HJKPSTUW]|[A-PR-UWYZ][A-HK-Y][0-9][ABEHMNPRVW-Y])[0-9][ABD-HJLNP-UW-Z]{2})$/;
        let cleanPostcode = postcode.replace(/\s+/g, '').toUpperCase();
        if (!pcRegex.test(cleanPostcode)) {
            showToast('Please enter a valid UK postcode (e.g. SW1A 1AA).', 'error');
            $('#postcode').focus();
            return false;
        }

        return true;
    }

    // Copy Booking Reference Code
    $('#btn-copy-ref').on('click', function () {
        let code = $('#booking-ref-text').text();
        if (navigator.clipboard) {
            navigator.clipboard.writeText(code).then(function () {
                showToast('Booking reference copied to clipboard!', 'success');
            });
        } else {
            let temp = $('<input>');
            $('body').append(temp);
            temp.val(code).select();
            document.execCommand('copy');
            temp.remove();
            showToast('Booking reference copied to clipboard!', 'success');
        }
    });

    // Helper: Toast Notifications
    function showToast(msg, type = 'info') {
        let container = $('.toast-container');
        if (!container.length) {
            container = $('<div class="toast-container"></div>');
            $('body').append(container);
        }

        let toast = $(`<div class="toast ${type}">${msg}</div>`);
        container.append(toast);

        setTimeout(function () {
            toast.fadeOut(400, function () {
                $(this).remove();
            });
        }, 4000);
    }

    // Helper: Fetch Dynamic Settings via AJAX
    function fetchSettings() {
        $.ajax({
            url: 'ajax/get-settings.php',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res.success && res.settings) {
                    unitPrice = parseFloat(res.settings.unit_price) || 14.99;
                    shippingCharge = parseFloat(res.settings.shipping_charge) || 3.99;
                    currencySymbol = res.settings.currency_symbol || '£';
                    if (res.settings.csrf_token) {
                        $('#csrf_token').val(res.settings.csrf_token);
                    }
                    recalculateTotals(parseInt($('#quantity-input').val()) || 1);
                }
            }
        });
    }

    // Expose helpers for external modules like PayPal integration
    window.VKBooking = {
        validateBookingForm: validateBookingForm,
        showToast: showToast,
        getUnitPrice: () => unitPrice,
        getShippingCharge: () => shippingCharge
    };
});
