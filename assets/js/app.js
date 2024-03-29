/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)

require('../css/accueil.css');
require('../css/maison.css');
require('../css/savoirfaire.css');
require('../css/boutique.css');
require('../css/vignoble.css');
require('../css/champagnes.css');
require('../css/contact.css');
require('../css/mentionslegales.css');
require('../css/champagneshow.css');
require('../css/checkout.css');
require('../css/account.css');
require('../css/app.css');

var $ = require('jquery');
var path = window.location.pathname;
var fromTop = getScrollTop();
var selectCountry = document.getElementById('country');
if (path !== '/Boutique' && path !== '/Panier' && path !== '/Checkout' && path !== '/ModifierInformations'){
    $('#fixed-hd').toggleClass("down", (fromTop > 185));
    $('#header-nav').toggleClass("fixed", (fromTop > 185));
    $('.void-fill').toggleClass("fill", (fromTop > 185));
    $(window).on("scroll", function() {
        var fromTop = getScrollTop();
        $('#fixed-hd').toggleClass("down", (fromTop > 200));
        $('#header-nav').toggleClass("fixed", (fromTop > 200));
        $('.void-fill').toggleClass("fill", (fromTop > 200));
    });
}

$(".champagne-bottle").hover(function() {
    $(".champagne-bottle").toggleClass("champagne-bottle-hide");
    $(this).toggleClass("champagne-bottle-show");
});

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
        return pageYOffset;
    }
    else{
        var B= document.body;
        var D= document.documentElement;
        D= (D.clientHeight)? D: B;
        return D.scrollTop;
    }
}
verifyCookieAndUpdateLegalAge();

$(selectCountry).change(function(){
    verifyCookieAndUpdateLegalAge();
});


function verifyCookieAndUpdateLegalAge() {
    if (readCookie('isAgeOK') === null) {
        var choiceCountry = selectCountry.value;
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear() - choiceCountry;
        if (dd < 10) {
            dd = '0' + dd
        }
        if (mm < 10) {
            mm = '0' + mm
        }
        var limitDate = dd + '/' + mm + '/' + yyyy;
        document.getElementById('limit-date').innerText = limitDate;
        $('#age-verification').css('display', 'block');
    }
}

    $('#age-yes').on("click", function () {
        $("#age-verification").css('display', 'none');
        console.log(selectCountry.value);
        createCookie('isAgeOK', 1);
    });

    $('.chg-lg').on("click", function () {
        createCookie('lg', this.id);
        location.reload();
    });


    function defineActive(element) {
        element = element.substr(1);
        document.getElementById(element).className = "active";
    }


    function createCookie(name, value, days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = "; expires=" + date.toGMTString();
        }
        else var expires = "";
        document.cookie = name + "=" + value + expires + "; path=/";
    }

    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
