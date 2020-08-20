$(document).ready(function ($) {
    $('.banner-owl').owlCarousel({
        loop: false,
        margin: 0,
        items: 1,
        dotted: true,
        nav: false
    });
});


$('.close-search').on('click',function() {
    $('.search-drop').slideUp();
    //document.getElementById("searchoverlay").style.display = "none";
    $('.searchoverlay').fadeOut();
});
$('.open-search').on('click',function(e) {
    e.preventDefault();
    $('.search-drop').slideDown()
    $('.searchoverlay').fadeIn();

        $('input.search-input').focus();

    //document.getElementById("searchoverlay").style.display = "block";
    //console.log("slide down search");
});

//overlay cliked

$('.searchoverlay').on('click',function() {
    $('.search-drop').slideUp();
    //document.getElementById("searchoverlay").style.display = "none";
    $('.searchoverlay').fadeOut();
});

// search input autotrack
$('input.search-input').keyup(function () {
    $searchVal = ($(this).val());
    updateAutoTrackLabelValue($searchVal);
});

function updateAutoTrackLabelValue($searchVal){
    $('.search-submit').attr('data-event-label', $searchVal);
}

// event handlers



//$('.mailing_list i.fa-check').on('click',function(e) {
//    e.preventDefault();
//    if($(this).hasClass('fa-check')){
//        $(this).removeClass('fa-check');
//        $(this).addClass('fa-square');
//        console.log('unchecked');
//        $('button.btn').attr('disabled', true);
//        $('button.btn').html('Join disabled', true);
//    }else if($(this).hasClass('fa-square')){
//        $(this).removeClass('fa-square');
//        $(this).addClass('fa-check');
//        console.log('checked');
//        $('button.btn').removeAttr('disabled');
//        $('button.btn').html('Join', true);
//    }
//
//});



//form filed upload display filename fix


$('input[type="file"]').change(function(e){
    var fileName = e.target.files[0].name;
    console.log("replace");
    $('.custom-file-label').html(fileName);
});

//id=companyLogo
//$('#companyLogo').on('change',function(){
//    //get the file name
//    var fileName = $(this).val();
//    //replace the "Choose a file" label
//
//    $(this).next('.custom-file-label').html(fileName);
//})

//$('.mailing_list i.fa-square').on('click',function(e) {
//    e.preventDefault();
//
//});


//header
// When the user scrolls the page, execute myFunction
//$(window).scroll(function() {
//    if( $(this).scrollTop() > 12 ) {
//        $(".header").addClass("fixed-top");
//    } else {
//        $(".header").removeClass("fixed-top");
//    }
//});

/*
 * Replace all SVG images with inline SVG
 */
//document.querySelectorAll('img.svg').forEach(function(img){
//    var imgID = img.id;
//    var imgClass = img.className;
//    var imgURL = img.src;
//
//    fetch(imgURL).then(function(response) {
//        return response.text();
//    }).then(function(text){
//
//        var parser = new DOMParser();
//        var xmlDoc = parser.parseFromString(text, "text/xml");
//
//        // Get the SVG tag, ignore the rest
//        var svg = xmlDoc.getElementsByTagName('svg')[0];
//
//        // Add replaced image's ID to the new SVG
//        if(typeof imgID !== 'undefined') {
//            svg.setAttribute('id', imgID);
//        }
//        // Add replaced image's classes to the new SVG
//        if(typeof imgClass !== 'undefined') {
//            svg.setAttribute('class', imgClass+' replaced-svg');
//        }
//
//        // Remove any invalid XML tags as per http://validator.w3.org
//        svg.removeAttribute('xmlns:a');
//
//        // Check if the viewport is set, if the viewport is not set the SVG wont't scale.
//        if(!svg.getAttribute('viewBox') && svg.getAttribute('height') && svg.getAttribute('width')) {
//            svg.setAttribute('viewBox', '0 0 ' + svg.getAttribute('height') + ' ' + svg.getAttribute('width'))
//        }
//
//        // Replace image with new SVG
//        img.parentNode.replaceChild(svg, img);
//
//    });
//
//});

//svg inline
/*
 * Replace all SVG images with inline SVG
 */
//$(document).ready(function() {
//    $('img[src$=".svg"]').each(function() {
//        var $img = $(this);
//        var imgURL = $img.attr('src');
//        var attributes = $img.prop("attributes");
//
//        jQuery.get(imgURL, function(data) {
//            // Get the SVG tag, ignore the rest
//            var $svg = jQuery(data).find('svg');
//
//            // Remove any invalid XML tags
//            $svg = $svg.removeAttr('xmlns:a');
//
//            // Loop through IMG attributes and apply on SVG
//            $.each(attributes, function() {
//                $svg.attr(this.name, this.value);
//            });
//
//            // Replace IMG with SVG
//            $img.replaceWith($svg);
//        }, 'xml');
//    });
//});