var passEmail = null;
var amount = null;
var chimpEmail = null;

$('input.pass-email').keyup(function () {
    // $chimpEmail = ($(this).val());
    chimpEmail = this.value;
    console.log("chimpEmail =" + chimpEmail);
    updateChimpEmailValue(chimpEmail);
});

function updateChimpEmailValue(chimpEmail){
    console.log("chimpupdate func");
    $target = $('input#mce-EMAIL');
    console.log($target);
    $('#mce-EMAIL').val(chimpEmail);
}

$(document).ready(function(){
    $("#offcanvas").click(function(){
        $(".offcanvas-collapse").fadeToggle();
        $('.navbar-toggler-icon').toggleClass('open');
        // $("#div2").fadeToggle("slow");
        // $("#div3").fadeToggle(3000);
    });
    $("#offcanvas2").click(function(){
        $(".offcanvas-collapse").fadeToggle();
        $('.navbar-toggler-icon').toggleClass('open');
        // $("#div2").fadeToggle("slow");
        // $("#div3").fadeToggle(3000);
    });
});
    $('[data-toggle="offcanvas"]').on('click', function () {

        // $('.navbar-toggler-icon').toggleClass('open');
       // $('.offcanvas-collapse').fadeToggle('slow');
        // $('body').toggleClass("offcanvas-active");
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