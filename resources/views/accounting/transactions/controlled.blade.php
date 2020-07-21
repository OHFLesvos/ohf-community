    $('.mark-controlled').off('click');
    $('.mark-controlled').on('click', function () {
        var url = $(this).data('url');
        var btn = $(this);
        btn.attr('disabled', true);
        $.post(url)
            .done(function () {
                btn.text('Done');
                btn.addClass('btn-success');
                btn.removeClass('btn-primary');
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                var message;
                if (jqXHR.responseJSON.message) {
                    if (jqXHR.responseJSON.errors) {
                        message = "";
                        var errors = jqXHR.responseJSON.errors;
                        Object.keys(errors).forEach(function (key) {
                            message += errors[key] + "\n";
                        });
                    } else {
                        message = jqXHR.responseJSON.message;
                    }
                } else {
                    message = textStatus + ': ' + jqXHR.responseText;
                }
                alert(message);
                btn.attr('disabled', false);
            })
        });

    $('.undo-controlled').off('click');
    $('.undo-controlled').on('click', function () {
        var url = $(this).data('url');
        var btn = $(this);
        btn.attr('disabled', true);
        $.ajax({
            url: url,
            type: 'DELETE',
            success: function () {
                btn.text('Done');
                btn.addClass('btn-success');
                btn.removeClass('btn-secondary');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var message;
                if (jqXHR.responseJSON.message) {
                    if (jqXHR.responseJSON.errors) {
                        message = "";
                        var errors = jqXHR.responseJSON.errors;
                        Object.keys(errors).forEach(function (key) {
                            message += errors[key] + "\n";
                        });
                    } else {
                        message = jqXHR.responseJSON.message;
                    }
                } else {
                    message = textStatus + ': ' + jqXHR.responseText;
                }
                alert(message);
                btn.attr('disabled', false);
            }
        })
    });
