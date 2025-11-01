jQuery(document).ready(function($) {

    // Update header after login/register
    function updateHeaderUI(displayName, accountUrl) {
        // Close modal
        var authModal = bootstrap.Modal.getInstance(document.getElementById('authModal'));
        if (authModal) authModal.hide();

        // Update header buttons
        $('.auth-buttons').html(
    '<a href="' + accountUrl + '" class="icon-link account-btn">' +
        '<i class="fas fa-user-circle"></i>' +
        '<span>My Account</span>' +
    '</a>' +
    '<a href="' + ajax_auth_params.logout_url + '" class="icon-link logout-btn">' +
        '<i class="far fa-sign-out"></i>' +
        '<span>Logout</span>' +
    '</a>'
);


        // Optional: show toast
        var toast = new bootstrap.Toast(document.getElementById('loginToast'), { delay: 3000 });
        $('#loginToast .toast-body').text('Welcome back, ' + displayName + '!');
        toast.show();
    }

    function showError(selector, message) {
        $(selector).html('<div class="alert alert-danger">' + message + '</div>').show();
        setTimeout(() => $(selector).fadeOut(), 5000);
    }

    function clearErrors() {
        $('#login-message, #register-message').empty().hide();
    }

    function resetForm(selector) {
        $(selector)[0].reset();
    }

    // ------------------ LOGIN ------------------
    $('#ajax-login-form').on('submit', function(e) {
        e.preventDefault();
        clearErrors();

        var $btn = $(this).find('button[type="submit"]');
        var originalText = $btn.text();
        $btn.prop('disabled', true).text('Signing In...');

        $.post(ajax_auth_params.ajax_url, {
            action: 'ajaxlogin',
            email: $('#loginEmail').val().trim(),
            password: $('#loginPassword').val(),
            remember: $('#rememberMe').is(':checked') ? '1' : '0',
            security: ajax_auth_params.login_nonce
        }, function(response) {
            if (response.success) {
                updateHeaderUI(response.data.display_name, ajax_auth_params.myaccount_url || '#');
                resetForm('#ajax-login-form');
            } else {
                showError('#login-message', response.data.message || 'Login failed.');
            }
        }).fail(() => showError('#login-message', 'Connection error.'))
          .always(() => $btn.prop('disabled', false).text(originalText));
    });

    // ------------------ REGISTER ------------------
    $('#ajax-register-form').on('submit', function(e) {
        e.preventDefault();
        clearErrors();

        var $btn = $(this).find('button[type="submit"]');
        var originalText = $btn.text();
        $btn.prop('disabled', true).text('Creating Account...');

        $.post(ajax_auth_params.ajax_url, {
            action: 'ajax_register',
            name: $('#registerName').val().trim(),
            email: $('#registerEmail').val().trim(),
            password: $('#registerPassword').val(),
            security: ajax_auth_params.register_nonce
        }, function(response) {
            if (response.success) {
                updateHeaderUI(response.data.display_name, ajax_auth_params.myaccount_url || '#');
                resetForm('#ajax-register-form');
            } else {
                showError('#register-message', response.data.message || 'Registration failed.');
            }
        }).fail(() => showError('#register-message', 'Connection error.'))
          .always(() => $btn.prop('disabled', false).text(originalText));
    });

    // Clear errors when typing
    $('#loginEmail, #loginPassword').on('input', () => $('#login-message').fadeOut());
    $('#registerName, #registerEmail, #registerPassword').on('input', () => $('#register-message').fadeOut());
});
