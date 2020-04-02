const isIsbn = require('is-isbn')

$(function(){
    $('#lendBookModal').on('shown.bs.modal', function (e) {
        $('input[name="book_id"]').val('').trigger('change');
        $('input[name="book_id_search"]').val('').focus();
        $('input[name="person_id"]').val('').trigger('change');
        $('input[name="person_id_search"]').val('').focus();
    });

    $('#registerBookModal').on('shown.bs.modal', function (e) {
        $('input[name="isbn"]').focus();
        var book_search = $('input[name="book_id_search"]').val().toUpperCase().replace(/[^+0-9X]/gi, '');
        if (isIsbn.validate(book_search)) {
            $('input[name="isbn"]').val(book_search).trigger('propertychange');
        }
    });

    $('input[name="isbn"]').on('input propertychange', function(){
        $(this).removeClass('is-valid').removeClass('is-invalid');
        var isbn = $(this).val().toUpperCase().replace(/[^+0-9X]/gi, '');
        if (/^(97(8|9))?\d{9}(\d|X)$/.test(isbn) && isIsbn.validate(isbn)) {
            $(this).addClass('is-valid');
            $('input[name="title"]').val('');
            $('input[name="author"]').val('');
            $('input[name="language"]').val('');
            $('input[name="title"]').attr('placeholder', 'Searching for title...');
            $('input[name="author"]').attr('placeholder', 'Searching for author...');
            $('input[name="language"]').attr('placeholder', 'Searching for language...');
            $.get("/api/library/books/findIsbn/" + isbn, function(data) {
                $('input[name="title"]').val(data.title);
                $('input[name="author"]').val(data.author);
                $('input[name="language"]').val(data.language);
            }).fail(function() {
                $('input[name="title"]').attr('placeholder', 'Title');
                $('input[name="author"]').attr('placeholder', 'Author');
                $('input[name="language"]').attr('placeholder', 'Language');
            });
        } else if ($(this).val().length > 0) {
            $(this).addClass('is-invalid');
        }
    });
});
