/**
 * Griots Lounge Publishing - Universal Form Submit Spinner & Disable Engine
 * Automatically prevents double-submission and displays a loading spinner
 * across all Admin and User/Author forms throughout the platform.
 */
(function () {
    'use strict';

    function initFormSubmitSpinner() {
        document.addEventListener('submit', function (event) {
            var form = event.target;
            if (!form || form.tagName !== 'FORM') return;

            // Allow forms to opt out by adding class 'no-auto-spinner'
            if (form.classList.contains('no-auto-spinner')) return;

            // HTML5 Form Validation check
            if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                return; // Let native browser validation display without disabling
            }

            // Find the submit button
            var submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            if (!submitBtn) {
                // If button was clicked outside or no explicit type, check active element
                if (document.activeElement && document.activeElement.form === form) {
                    submitBtn = document.activeElement;
                }
            }
            if (!submitBtn || submitBtn.disabled || submitBtn.classList.contains('is-submitting')) return;

            // Determine appropriate loading text
            var loadingText = submitBtn.getAttribute('data-loading-text');
            if (!loadingText) {
                var rawText = (submitBtn.innerText || submitBtn.value || '').trim();
                var lower = rawText.toLowerCase();

                if (/sign in|log in/i.test(lower)) {
                    loadingText = 'Signing in...';
                } else if (/create account|register/i.test(lower)) {
                    loadingText = 'Creating account...';
                } else if (/approve/i.test(lower)) {
                    loadingText = 'Approving...';
                } else if (/reject|decline/i.test(lower)) {
                    loadingText = 'Rejecting...';
                } else if (/save|update/i.test(lower)) {
                    loadingText = 'Saving changes...';
                } else if (/upload/i.test(lower)) {
                    loadingText = 'Uploading file...';
                } else if (/pay|checkout|transfer|deposit/i.test(lower)) {
                    loadingText = 'Processing payment...';
                } else if (/delete|remove|destroy/i.test(lower)) {
                    loadingText = 'Deleting...';
                } else if (/send|dispatch|mail/i.test(lower)) {
                    loadingText = 'Sending...';
                } else if (/verify|check/i.test(lower)) {
                    loadingText = 'Verifying...';
                } else if (/generate|publish/i.test(lower)) {
                    loadingText = 'Publishing...';
                } else if (/submit/i.test(lower)) {
                    loadingText = 'Submitting...';
                } else if (rawText.length > 0 && rawText.length < 25) {
                    loadingText = rawText + '...';
                } else {
                    loadingText = 'Processing...';
                }
            }

            // Keep original markup in case form submission is cancelled or fails
            submitBtn.setAttribute('data-original-html', submitBtn.innerHTML);

            // Small 15ms timeout allows native form submit event to dispatch clean POST data
            setTimeout(function () {
                submitBtn.disabled = true;
                submitBtn.classList.add('disabled', 'is-submitting');
                submitBtn.style.pointerEvents = 'none';

                if (submitBtn.tagName === 'INPUT') {
                    submitBtn.value = loadingText;
                } else {
                    submitBtn.innerHTML = 
                        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display:inline-block; width:1rem; height:1rem; vertical-align:-0.125em; border:0.16em solid currentColor; border-right-color:transparent; border-radius:50%; animation:0.75s linear infinite spinner-border;"></span>' +
                        '<span>' + loadingText + '</span>';
                }
            }, 15);

            // Safety fail-safe: re-enable after 30 seconds if page has not navigated
            setTimeout(function () {
                if (submitBtn && submitBtn.classList.contains('is-submitting')) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('disabled', 'is-submitting');
                    submitBtn.style.pointerEvents = '';
                    var orig = submitBtn.getAttribute('data-original-html');
                    if (orig) submitBtn.innerHTML = orig;
                }
            }, 30000);
        }, true);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFormSubmitSpinner);
    } else {
        initFormSubmitSpinner();
    }
})();

