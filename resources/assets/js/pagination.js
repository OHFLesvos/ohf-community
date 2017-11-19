module.exports = {
    updatePagination: updatePagination
};

function updatePagination( container, result, callback ) {
    container.empty();

    // First page
    if (result.current_page > 1) {
        container.append( createPaginationItem( '&laquo;', 1, null, callback ) );
    } else {
        container.append( createPaginationItem( '&laquo;', null, 'disabled', callback ) );
    }

    // Previous page
    if (result.current_page > 1) {
        container.append( createPaginationItem( '&lsaquo;', result.current_page - 1, null, callback ) );
    } else {
        container.append( createPaginationItem( '&lsaquo;', null, 'disabled', callback ) );
    }

    // Pages before
    for (i = 2 + Math.max(2 - ( result.last_page - result.current_page ), 0); i >= 1; i--) {
        if (result.current_page > i) {
            container.append( createPaginationItem( result.current_page - i, result.current_page - i, null, callback ) );
        }
    }

    // Current page
    container.append( createPaginationItem( result.current_page, null, 'active', callback ) );

    // Pages after
    for (i = 1; i <= 2 + (Math.max(0, 3 - result.current_page)); i++) {
        if ( result.current_page  + i - 1 < result.last_page ) {
            container.append( createPaginationItem( result.current_page + i, result.current_page + i, null, callback ) );
        }
    }

    // Next page
    if (result.current_page < result.last_page) {
        container.append( createPaginationItem( '&rsaquo;', result.current_page + 1, null, callback ) );
    } else {
        container.append( createPaginationItem( '&rsaquo;', null, 'disabled', callback ) );
    }

    // Last page
    if (result.current_page < result.last_page) {
        container.append( createPaginationItem( '&raquo;', result.last_page, null, callback ) );
    } else {
        container.append( createPaginationItem( '&raquo;', null, 'disabled', callback ) );
    }
}

function createPaginationItem( content, pageTarget, elemClass, callback ) {
    var elem = $( '<li>' )
        .addClass( 'page-item' )
    if ( pageTarget != null ) {
        elem.append($( '<a>' )
            .addClass( 'page-link' )
            .attr('href', 'javascript:;')
            .html( content )
            .on('click', function(){
                callback( pageTarget );
            })
        )
    } else {
        elem.append($( '<span>' )
            .addClass( 'page-link' )
            .html( content )
        )
    }
    if ( elemClass != null ) {
        elem.addClass( elemClass )
    }
    return elem;
}
