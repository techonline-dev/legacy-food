// ====================================================================
// Legacy Food — Checkout & Payment Engine
// ====================================================================

document.addEventListener('DOMContentLoaded', () => {
    initCheckoutCoupon();
    initBillingToggle();
    initCreateAccountToggle();
    initCheckoutForm();
});

function initCreateAccountToggle() {
    const toggle = document.getElementById('create_account_toggle');
    const container = document.getElementById('account_password_container');
    if (!toggle || !container) return;

    toggle.addEventListener('change', () => {
        if (toggle.checked) {
            container.classList.remove('hidden');
            const passInput = container.querySelector('input[name="account_password"]');
            if (passInput) passInput.focus();
        } else {
            container.classList.add('hidden');
        }
    });
}

function initBillingToggle() {
    const sameCheckbox = document.getElementById('same_billing');
    const billingFields = document.getElementById('billing-address-fields');
    if (!sameCheckbox || !billingFields) return;

    sameCheckbox.addEventListener('change', () => {
        if (sameCheckbox.checked) {
            billingFields.classList.add('hidden');
        } else {
            billingFields.classList.remove('hidden');
        }
    });
}

function initCheckoutCoupon() {
    const applyBtn = document.getElementById('checkout-apply-coupon-btn');
    const input = document.getElementById('checkout-coupon-code');
    const removeBtn = document.getElementById('checkout-remove-coupon-btn');

    if (applyBtn && input) {
        applyBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            const code = input.value.trim();
            if (!code) {
                showToast('Please enter a coupon code', 'error');
                return;
            }

            try {
                const formData = new FormData();
                formData.append('coupon_code', code);

                const res = await fetch(window.APP_URL + '/cart/coupon/apply', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const json = await res.json();

                if (json.success) {
                    showToast(json.message);
                    window.location.reload();
                } else {
                    showToast(json.error || 'Failed to apply coupon', 'error');
                }
            } catch (err) {
                console.error(err);
            }
        });
    }

    if (removeBtn) {
        removeBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            try {
                const res = await fetch(window.APP_URL + '/cart/coupon/remove', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const json = await res.json();
                if (json.success) {
                    showToast(json.message);
                    window.location.reload();
                }
            } catch (err) {
                console.error(err);
            }
        });
    }
}

function initCheckoutForm() {
    const form = document.getElementById('checkout-form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn ? submitBtn.innerHTML : 'Place Order';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-current inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg> Processing Order...
            `;
        }

        try {
            const formData = new FormData(form);
            const res = await fetch(window.APP_URL + '/checkout/process', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const json = await res.json();

            if (!json.success) {
                showToast(json.error || 'Checkout validation failed.', 'error');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
                return;
            }

            const paymentMethod = form.querySelector('input[name="payment_method"]:checked')?.value || 'cod';

            // If online payment (Razorpay / UPI) and key provided
            if (paymentMethod !== 'cod' && typeof Razorpay !== 'undefined') {
                const options = {
                    key: json.key_id || 'rzp_test_legacyfood123',
                    amount: Math.round(json.total_amount * 100),
                    currency: 'INR',
                    name: 'Legacy Food',
                    description: 'Heritage Ghee & Oils Order #' + json.order_number,
                    image: window.APP_URL + '/assets/images/branding/header-logo-dark.svg',
                    handler: async function (response) {
                        // Server-side payment verification
                        const verifyData = new FormData();
                        verifyData.append('order_id', json.order_id);
                        verifyData.append('razorpay_payment_id', response.razorpay_payment_id);
                        verifyData.append('razorpay_signature', response.razorpay_signature || 'sig_demo');

                        const vRes = await fetch(window.APP_URL + '/checkout/verify-payment', {
                            method: 'POST',
                            body: verifyData,
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const vJson = await vRes.json();
                        if (vJson.success) {
                            window.location.href = vJson.redirect;
                        } else {
                            showToast(vJson.error || 'Payment verification failed', 'error');
                        }
                    },
                    prefill: {
                        name: form.querySelector('input[name="full_name"]')?.value || '',
                        email: form.querySelector('input[name="email"]')?.value || '',
                        contact: form.querySelector('input[name="phone"]')?.value || ''
                    },
                    theme: {
                        color: '#bc944c'
                    }
                };

                const rzp = new Razorpay(options);
                rzp.on('payment.failed', function (response) {
                    showToast('Payment was not completed: ' + response.error.description, 'error');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
                rzp.open();
            } else {
                // Cash on Delivery or redirect
                window.location.href = json.redirect;
            }
        } catch (err) {
            console.error(err);
            showToast('An unexpected error occurred. Please try again.', 'error');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }
    });
}
