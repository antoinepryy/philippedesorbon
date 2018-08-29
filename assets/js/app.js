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
        console.log('Sorry, we are out of ' + expr + '.');
}


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
// var $ = require('jquery');

