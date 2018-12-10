const ISBN = require( 'isbn-validate' );
$(function(){
    $('input[name="isbn"]').on('input propertychange', function(){
        $(this).removeClass('is-valid').removeClass('is-invalid');
        var isbn = $(this).val().toUpperCase().replace(/[^+0-9X]/gi, '');
        if (/^(97(8|9))?\d{9}(\d|X)$/.test(isbn) && ISBN.Validate(isbn)) {
            $(this).addClass('is-valid');
            $('input[name="title"]').val('');
            $('input[name="author"]').val('');
            $('input[name="title"]').attr('placeholder', 'Searching for title...');
            $('input[name="author"]').attr('placeholder', 'Searching for author...');
            $.get("/library/books/findIsbn/" + isbn, function(data) {
                $('input[name="title"]').val(data.title);
                $('input[name="author"]').val(data.author);
            }).fail(function() {
                $('input[name="title"]').attr('placeholder', 'Title');
                $('input[name="author"]').attr('placeholder', 'Author');
            });
        } else if ($(this).val().length > 0) {
            $(this).addClass('is-invalid');
        }
    });
});