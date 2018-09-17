/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');
require('../css/accueil.css');
require('../css/maison.css');
require('../css/savoirfaire.css');
require('../css/boutique.css');
require('../css/vignoble.css');
require('../css/champagnes.css');
require('../css/contact.css');
require('../css/mentionslegales.css');
require('../css/champagneshow.css');

var $ = require('jquery');
var path = window.location.pathname;
var fromTop = getScrollTop();
$('#fixed-hd').toggleClass("down", (fromTop > 185));
$('#header-nav').toggleClass("fixed", (fromTop > 185));
$('.void-fill').toggleClass("fill", (fromTop > 185));
switch (path) {
    case '/LaMaison':
        defineActive(path);
        break;
    case '/SavoirFaire':
        defineActive(path);
        break;
    case '/Champagnes':
        defineActive(path);
        break;
    case '/Boutique':
        defineActive(path);
        break;
    case '/Vignoble':
        defineActive(path);
        break;
    default:
        break;
}
if (path.substr(0,12)=='/Champagnes/'){
    defineActive('/Champagnes');
}



function getScrollTop(){
    if(typeof pageYOffset!= 'undefined'){
        //most browsers except IE before #9
        return pageYOffset;
    }
    else{
        var B= document.body; //IE 'quirks'
        var D= document.documentElement; //IE with doctype
        D= (D.clientHeight)? D: B;
        return D.scrollTop;
    }
}


$(window).on("scroll", function() {
    var fromTop = getScrollTop();
    $('#fixed-hd').toggleClass("down", (fromTop > 200));
    $('#header-nav').toggleClass("fixed", (fromTop > 200));
    $('.void-fill').toggleClass("fill", (fromTop > 200));
});

if (readCookie('isAgeOK') === null){
    $('#age-verification').css('display','block');
};

$('#age-yes').on("click", function() {
    $("#age-verification").css('display', 'none');
    createCookie('isAgeOK', 1);
});


// function lowFooter() {
//     document.getElementById("footer").style.position= "absolute";
//     document.getElementById("footer").style.bottom= "0";
//     document.getElementById("footer").style.left= "0";
//     document.getElementById("footer").style.right= "0";
// }

function defineActive(element){
    element = element.substr(1);
    document.getElementById(element).className = "active";
}


var fadein_tween = TweenMax.to('#fadein-trigger', .375,{ opacity: 1 });
var fadeout_tween = TweenMax.to('#fadein-trigger', .375,{ opacity: 0 });

var controller = new ScrollMagic.Controller();

var fadein_scene = new ScrollMagic.Scene({
    triggerElement: '#fadein-trigger',
    reverse: true
})
    .setTween(fadein_tween)
    .addTo(controller);

var fadeout_scene = new ScrollMagic.Scene({
    triggerElement: '#fadeout-trigger',
    reverse: true
})
    .setTween(fadeout_tween)
    .addTo(controller);

function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}