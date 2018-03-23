$('.hideView').on('click', function () {
    if ($(this).hasClass('fa-plus')) {
        $(this).removeClass('fa-plus').addClass('fa-minus');
        $(this).siblings('div:not(.hidden)').show();
    } else {
        $(this).parent('div').find('.fa-minus').removeClass('fa-minus').addClass('fa-plus');
        $(this).parent('div').find('div').hide();
    }
});

$('#localization').on('click', '.edit', function () {
    $(this).parent('.local').hide().next('.hidden').show();
});

$('#localization').on('click', '.save', function () {
    var elem = $(this).parent('.local').prev('.local');
    $(this).parent('.local').hide().prev('.local').show();

    var sdata = {};
    sdata['id'] = $(this).attr('id');
    sdata['lang'] = $(this).attr('lang');
    sdata['text'] = $(this).siblings('.text').val();
    $.ajax({
        url: '/admin/administration/set_localization',
        type: 'post',
        data: sdata,
        dataType: 'json',
        success: function (data) {
            if (data == 'ok') {
                elem.html('<i class="fas fa-pencil-alt edit" aria-hidden="true"></i> <span>' + sdata['lang'] + ':</span> ' + sdata['text']);
            }
        },
        beforeSend: function () {
        },
        error: function () {
        }
    });
    return false;
});