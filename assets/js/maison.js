var $ = require('jquery');


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

$('.circle').click(function(){
    var indexAnim = $(this).attr('id').substr(7);
    $('#anim-picture').fadeOut();
    $('#anim-picture').fadeOut('slow');
    $('#anim-picture').promise().done(function(){
        $('#anim-picture').attr('src','/ressource/image/maison/slide_'+ indexAnim +'.jpg');
    }).promise().done(function() {
        $('#anim-picture').fadeIn('slow');
    });
    $('.circle').removeClass('circle-active');
    $('.anim-txt').css('display', 'none');
    $('#anim-txt-'+indexAnim).css('display', 'block');
    $('#anim-txt-'+indexAnim).addClass('fadein');
    $(this).addClass('circle-active');
});