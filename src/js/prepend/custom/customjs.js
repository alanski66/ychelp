$('input#pass-email').keyup(function () {
    $chimpEmail = ($(this).val());
    updateChimpEmailValue($chimpEmail);
});

function updateChimpEmailValue($chimpEmail){
    $('input#mce-EMAIL').val($chimpEmail);
}