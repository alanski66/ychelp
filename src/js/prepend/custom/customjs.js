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
