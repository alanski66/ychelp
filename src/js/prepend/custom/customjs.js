$('input#pass-email').keyup(function () {
    $chimpEmail = ($(this).val());
    updateChimpEmailValue($chimpEmail);
});

function updateChimpEmailValue($chimpEmail){
    $('input#mce-EMAIL').val($chimpEmail);
}


    $('[data-toggle="offcanvas"]').on('click', function () {

        $('.navbar-toggler-icon').toggleClass('open');
        $('.offcanvas-collapse').toggleClass('open');
        $('body').toggleClass("offcanvas-active");
    })

    $('.navbar-nav>li>.nav-link').on('click', function(){
        // $('.navbar-toggler-icon').toggleClass('open');
        // $('.offcanvas-collapse').toggleClass('open');
    })

// animation scroll js
var html_body = $('html, body');
$('.scroll').on('click', function () { //use page-scroll class in any HTML tag for scrolling
    if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        if (target.length) {
            html_body.animate({
                scrollTop: target.offset().top - 90
            }, 1500, 'easeInOutExpo');
            return false;
        }
    }
});