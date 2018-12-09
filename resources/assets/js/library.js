$(function(){
    $('input[name="isbn"]').on('input propertychange', function(){
        var isbn = $(this).val().replace(/[^+0-9x]/i, '');
        if (/^(97(8|9))?\d{9}(\d|X)$/.test(isbn)) {
            $.get("/library/books/findIsbn/" + isbn, function(data) {
                $('input[name="title"]').val(data.title);
                $('input[name="author"]').val(data.author);
            });
        }
    });
});