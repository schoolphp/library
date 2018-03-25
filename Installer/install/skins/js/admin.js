$('#exit').on('click', function () {
    localStorage.clear();
});

$('.subMenu').on('click', function () {
    var nav = $(this).attr('subMenu');
    subMenu(nav);
});

function subMenu(id) {
    var el = $('#' + id);
	el.toggle('slow');
    var tmp = el.prev('.subMenu').children('.submenu');
    if(tmp.hasClass('fa-caret-left')) {
        tmp.removeClass('fa-caret-left');
        tmp.addClass('fa-caret-down');
    } else {
        tmp.removeClass('fa-caret-down');
        tmp.addClass('fa-caret-left');
    }
}

$(function() {
    $('form').append('<input type="hidden" name="xsrf" value="' + antixsrf + '">');
    $('#mobile-menu-icon').click(function () {
        var mobile_freezone = $('#mobile-freezone');
        if(mobile_freezone.css('display') === 'none') {
			mobile_freezone.css('display','block');
			$('#main-nav').css('left','0');
        }
	});
    $('#mobile-freezone').click(function () {
		$('#mobile-freezone').css('display','none');
		$('#main-nav').css('left','-400px');
	});
});
