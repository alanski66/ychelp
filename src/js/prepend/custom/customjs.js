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


// URL updates and the element focus is maintained for keyboard navigation
// originally found via in Update 3 on http://www.learningjquery.com/2007/10/improved-animated-scrolling-script-for-same-page-links

// filter handling for a /dir/ OR /indexordefault.page
function filterPath(string) {
    return string
        .replace(/^\//, '')
        .replace(/(index|default).[a-zA-Z]{3,4}$/, '')
        .replace(/\/$/, '');
}

var locationPath = filterPath(location.pathname);
$('a.scroll').each(function () {
    var thisPath = filterPath(this.pathname) || locationPath;
    var hash = this.hash;
    if ($("#" + hash.replace(/#/, '')).length) {
        if (locationPath == thisPath && (location.hostname == this.hostname || !this.hostname) && this.hash.replace(/#/, '')) {
            var $target = $(hash), target = this.hash;
            if (target) {
                $(this).click(function (event) {
                    event.preventDefault();
                    $('html, body').animate({scrollTop: $target.offset().top}, 1000, function () {
                        location.hash = target;
                        $target.focus();
                        if ($target.is(":focus")){ //checking if the target was focused
                            return false;
                        }else{
                            $target.attr('tabindex','-1'); //Adding tabindex for elements not focusable
                            $target.focus(); //Setting focus
                        };
                    });
                });
            }
        }
    }
});