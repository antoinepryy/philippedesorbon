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

var $ = require('jquery');
var path = window.location.pathname;
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
    $('#fixed-hd').toggleClass("down", (fromTop > 185));
    $('#header-nav').toggleClass("fixed", (fromTop > 185));
    $('.void-fill').toggleClass("fill", (fromTop > 185));
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