jQuery(document).ready(function($) {
    $('#guest-post-form').on('submit', function(e) {
        e.preventDefault();

        var formData = {
            action: 'gps_submit_post',
            post_title: $('#post_title').val(),
            post_content: $('#post_content').val(),
            author_name: $('#author_name').val(),
            author_email: $('#author_email').val(),
        };

        $.post(gps_ajax_object.ajax_url, formData, function(response) {
            if (response.success) {
                $('#response-message').html('<p>Post submitted successfully!</p>');
                $('#guest-post-form')[0].reset();
            } else {
                $('#response-message').html('<p>Error: ' + response.data + '</p>');
            }
        });
    });
});
