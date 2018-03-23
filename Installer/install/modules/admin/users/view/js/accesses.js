jQuery(document).ready(function ($) {
    var elements = $("input:checked").closest('tr:not(.course)');
    elements.each(function(i,elem) {
        if ($(elem).hasClass('block')) {
            $(elem).prevAll('.course:first').addClass('part');
        } else {
            $(elem).prevAll('.course:first').addClass('part');
            $(elem).prevAll('.block:first').addClass('part');
        }
    });
});

$('#users-table').on('click', '.u-list', function () {
    $(this).children('td').children('.check').prop('checked', true);
    $('#foerm-users').submit();
});

$('#table-courses').on('change', '.check', function () {
    if ($(this).closest('tr').hasClass('course')) {
        setClass($(this), '.course');
    } else if ($(this).closest('tr').hasClass('block')) {
        setClass($(this), '.block');
    } else {
        setClass($(this), '.task');
    }
});

function setClass(e, type) {
    if (type == '.block') {
        e.closest(type).prevAll('.course:first').addClass('part');
    } else if (type == '.task') {
        e.closest(type).prevAll('.course:first').addClass('part');
        e.closest(type).prevAll('.block:first').addClass('part');
    }
    if (e.is(":checked")) {
        e.closest(type).addClass('yes').nextUntil(type + (type == '.block' ? ', .course' : (type == '.task' ? ', .course, .block' : ''))).addClass('yes').find('.check').prop('checked', false);
    } else {
        e.closest(type).removeClass('yes part').nextUntil(type + (type == '.block' ? ', .course' : (type == '.task' ? ', .course, .block' : ''))).removeClass('yes part').find('.check').prop('checked', false);
        e.closest(type).prevAll('.course:first').removeClass('part');
        e.closest(type).prevAll('.block:first').removeClass('part');
    }
}

$('main').on('click', '.remove', function () {
    var text = $(this).closest('td').siblings('.name').find('span').text().replace(": ", "");
    return confirm('Вы уверены, что хотите удалить ' + text + '?');
});

$('#table-courses').on('click', '.hide-view', function () {
    if ($(this).attr('course') || $(this).attr('course') == 0) {
        var elements = $('.block[course = '+$(this).attr('course')+']');
        if ($(this).hasClass('fa-minus')) {
            elements.each(function(i,elem) {
                var el = $('.task[block = '+$(elem).attr('bl')+']');
                el.hide();
            });
            elements.find('.fa-minus').removeClass('fa-minus').addClass('fa-plus');
        }
    } else {
        var elements = $('.task[block = '+$(this).attr('block')+']');
    }
    if(elements.is(':visible')) {
        elements.hide();
    } else {
        elements.show();
    }
    if ($(this).hasClass('fa-plus')) {
        $(this).removeClass('fa-plus').addClass('fa-minus');
    } else {
        $(this).removeClass('fa-minus').addClass('fa-plus');
    }
});

$('main').on('click', '.trig-stat', function () {
    $(this).toggleClass('green');
    $('.stat').toggleClass('hidden');
});