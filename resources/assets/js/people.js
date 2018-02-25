pagination = require('./pagination.js');

var delayTimer;
var orderByField = 'family_name';
var lastOrderByField = 'family_name';
var orderByDirection = 'asc';

$(function(){
    $('#filter input').on('change keyup', function(e){
        var keyCode = e.keyCode;
        if (keyCode == 0 || keyCode == 8 || keyCode == 13 || keyCode == 27 || keyCode == 46 || (keyCode >= 48 && keyCode <= 90) || (keyCode >= 96 && keyCode <= 111)) {
            var elem = $(this);

            $('#filter-status').html('');
            var tbody = $('#results-table tbody');
            tbody.empty();
            tbody.append($('<tr>')
                .append($('<td>')
                    .text('Searching...')
                    .attr('colspan', 16))
            );

            clearTimeout(delayTimer);
            delayTimer = setTimeout(function(){
                if (keyCode == 27) {  // ESC
                    elem.val('').focus();
                }
                if (keyCode == 13) {  // Enter
                    elem.blur();
                }
                filterTable(1);
            }, 300);
        }
    });

    $('a.sort').on('click', function(){
        orderByField = $(this).attr('data-field');
        if ((lastOrderByField == orderByField && orderByDirection != 'desc')) {
            orderByDirection = 'desc';
        } else {
            orderByDirection = 'asc';
        }
        lastOrderByField = orderByField;
        filterTable(1);
    });

    $('#reset-filter').on('click', function(){
        $('#filter input[name="family_name"]').val('');
        $('#filter input[name="name"]').val('');
        $('#filter input[name="date_of_birth"]').val('');
        $('#filter input[name="nationality"]').val('');
        $('#filter input[name="police_no"]').val('');
        $('#filter input[name="case_no"]').val('');
        $('#filter input[name="medical_no"]').val('');
        $('#filter input[name="registration_no"]').val('');
        $('#filter input[name="section_card_no"]').val('');
        $('#filter input[name="temp_no"]').val('');
        $('#filter input[name="languages"]').val('');
        $('#filter input[name="skills"]').val('');
        $('#filter input[name="remarks"]').val('');
        filterTable(1);
    });

    filterTable(1);
});

function filterTable(page) {
    $('#filter-status').html('');
    var tbody = $('#results-table tbody');
    tbody.empty();
    tbody.append($('<tr>')
        .append($('<td>')
            .text('Searching...')
            .attr('colspan', 16))
    );

    var paginator = $('#paginator');
    paginator.empty();

    var paginationInfo = $( '#paginator-info' );
    paginationInfo.empty();
    $.post( filterUrl, {
        "_token": csrfToken,
        "family_name": $('#filter input[name="family_name"]').val(),
        "name": $('#filter input[name="name"]').val(),
        "date_of_birth": $('#filter input[name="date_of_birth"]').val(),
        "nationality": $('#filter input[name="nationality"]').val(),
        "police_no": $('#filter input[name="police_no"]').val(),
        "case_no": $('#filter input[name="case_no"]').val(),
        "medical_no": $('#filter input[name="medical_no"]').val(),
        "registration_no": $('#filter input[name="registration_no"]').val(),
        "section_card_no": $('#filter input[name="section_card_no"]').val(),
        "temp_no": $('#filter input[name="temp_no"]').val(),
        "languages": $('#filter input[name="languages"]').val(),
        "skills": $('#filter input[name="skills"]').val(),
        "remarks": $('#filter input[name="remarks"]').val(),
        "page": page,
        'orderByField': orderByField,
        'orderByDirection': orderByDirection,
    }, function(result) {
        tbody.empty();
        if (result.data.length > 0) {
            $.each(result.data, function(k, v){
                tbody.append(writeRow(v));
            });
            pagination.updatePagination(paginator, result, filterTable);
            paginationInfo.html( result.from + ' - ' + result.to + ' of ' + result.total );
        } else {
            tbody.append($('<tr>')
                .addClass('warning')
                .append($('<td>')
                    .text('No results')
                    .attr('colspan', 16))
            );
        }
    })
        .fail(function(jqXHR, textStatus) {
            tbody.empty();
            tbody.append($('<tr>')
                .addClass('danger')
                .append($('<td>')
                    .text(textStatus)
                    .attr('colspan', 16))
            );
        });
}

function writeRow(person) {
    var icon = '';
    if (person.gender == 'f') {
        icon = 'female';
    }
    if (person.gender == 'm') {
        icon = 'male';
    }
    return $('<tr>')
        .attr('id', 'person-' + person.id)
        .append($('<td>').html(icon != '' ? '<i class="fa fa-' + icon + '"></i>' : ''))
        .append($('<td>')
            .append($('<a>')
                .attr('href', 'people/' + person.id)
                .text(person.family_name)
            )
        )
        .append($('<td>')
            .append($('<a>')
                .attr('href', 'people/' + person.id)
                .text(person.name)
            )
        )
        .append($('<td>').text(person.date_of_birth))
        .append($('<td>').text(person.nationality))
        .append($('<td>').text(person.police_no))
        .append($('<td>').text(person.case_no))
        .append($('<td>').text(person.medical_no))
        .append($('<td>').text(person.registration_no))
        .append($('<td>').text(person.section_card_no))
        .append($('<td>').text(person.temp_no))
        .append($('<td>').text(person.languages))
        .append($('<td>').text(person.skills))
        .append($('<td>').text(person.remarks))
        .append($('<td>').html(person.worker ? '<i class="fa fa-check"></i>' : '-'))
        .append($('<td>').text(person.created_at));
}