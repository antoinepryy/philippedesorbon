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
    var slideContainer = document.getElementById('slide-container');
    var activeSlide = document.getElementById('pict-' + indexAnim);
    $('.slide-maison').each(function () {
        this.classList.remove("slide-active");
    })
    switch(indexAnim){
        case '1':
            slideContainer.style.transform = "translateX(calc(-50% + 46vw))";
            break;
        case '2':
            slideContainer.style.transform = "translateX(calc(-50% + 23vw))";
            break;
        case '3':
            slideContainer.style.transform = "translateX(calc(-50%))";
            break;
        case '4':
            slideContainer.style.transform = "translateX(calc(-50% - 23vw))";
            break;
        case '5':
            slideContainer.style.transform = "translateX(calc(-50% - 46vw))";
            break;
    }
    activeSlide.classList.add("slide-active");
    $('.circle').removeClass('circle-active');
    $('.anim-txt').css('display', 'none');
    $('#anim-txt-'+indexAnim).css('display', 'block');
    $('#anim-txt-'+indexAnim).addClass('fadein');
    $(this).addClass('circle-active');
});