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