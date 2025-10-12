jQuery(document).ready(function ($) {
    $('#test-button').click(function (e) {
        e.preventDefault();
        $('#test-button').html('<span class="spinner-border spinner-border-sm me-2"></span>Checking...');
        var username = $('#username-input').val();
        var password = $('#password-input').val();
        var data = {
            action: 'my_test_action',
            nonce: test_ajax_vars.nonce,
            username: username,
            password: password,
        };
        $.ajax({
            type: 'POST',
            url: test_ajax_vars.ajaxurl,
            data: data,
            success: function (response) {
                $('#test-button').html('Checked!');
                $('#results').html(response.massage);
                console.log('Response from server:', response);
            },
            error: function (error) {
                $('#test-button').html('Checked!');
                $('#results').html('<pre>' + JSON.stringify(response, null, 2) + '</pre>');
                console.error('Error from server:', error);
            }
        })
    });
});