var $ = require('jquery');

$( ".vignes" ).mouseenter(
    function() {
        $( this).children().addClass( "slideup" );
        $( this ).children().removeClass("text-hidden");
    }
);

$( ".vignes" ).mouseleave(
    function() {
        $( this).children().addClass( "text-hidden" );
        $( this ).children().removeClass("slideup");
    }
);

$('a[href^="#"]').click(function(){
    var the_id = $(this).attr("href");
    if (the_id === '#') {
        return;
    }
    $('html, body').animate({
        scrollTop:$(the_id).offset().top
    }, 'slow');
    return false;
});