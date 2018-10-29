var pathGetCart = $("#get-cart").attr("data-path");
var pathPreOrder = $("#pre-order").attr("data-path");
var pathAddProduct = $("#add-product").attr("data-path");
var pathRemoveOne = $("#remove-one").attr("data-path");
var pathRemoveAll = $("#remove-all").attr("data-path");
var id;




$.ajax({
    url : pathGetCart,
    type : 'GET',
    dataType : 'json',
    success : function(response, statut){
        renderCart(response);
        totalCalculation(response);
    }
});

$(".add-button").click(function(){
    id = $(this).attr('id');
    $.ajax({
        url : pathAddProduct,
        type : 'GET',
        dataType : 'json',
        data : 'bottleId=' + id,
        success : function(response, statut){
            increaseNumber(id, response[4]);
            totalCalculation(response[2]);
        },
    });
});

$(".remove-one-button").click(function(){
    id = $(this).attr('id');
    $.ajax({
        url : pathRemoveOne,
        type : 'GET',
        dataType : 'json',
        data : 'bottleId=' + id,
        success : function(response, statut){
            reduceNumber(id, response[1]);
            totalCalculation(response[0]);
        },


    });
});

$(".remove-all-button").click(function(){
    id = $(this).attr('id');
    $.ajax({
        url : pathRemoveAll,
        type : 'GET',
        dataType : 'json',
        data : 'bottleId=' + id,
        success : function(response, statut){
            hideProduct(id, response[0]);
            refreshCartQtt(response[0]);
            totalCalculation(response[0]);
        },


    });
});

function renderCart(cart){
    if (cart.length===0 || cart.length===undefined){
        document.getElementById('empty-cart').style.display = "block";
        document.getElementById('cart-section').style.display = "none";
        document.getElementById('cart-recap').style.display = "none";
    }
    var block;
    var quantity;
    var price;
    var totalPrice;
    for (var i = 0; i < cart.length; i++) {
        if (cart[i].length===3){
            block = document.getElementById('champagne-'+cart[i][0]);
            quantity = document.getElementById('quantity-'+cart[i][0]);
            price = document.getElementById('price-'+cart[i][0]).innerText = cart[i][2].toFixed(2);
            block.style.display = "flex";
            quantity.innerHTML = cart[i][1].toString();
            totalPrice = parseInt(quantity.innerHTML) * parseFloat(cart[i][2]);
            document.getElementById('total-price-'+cart[i][0]).innerHTML = totalPrice.toFixed(2);
        }
        else if(cart[i].length===2){
            block = document.getElementById('champagne-'+cart[i][0]);
            quantity = document.getElementById('quantity-'+cart[i][0]);
            price = document.getElementById('price-'+cart[i][0]);
            block.style.display = "flex";
            quantity.innerHTML = cart[i][1].toString();
            totalPrice = parseInt(quantity.innerHTML) * parseFloat(price.innerHTML);
            document.getElementById('total-price-'+cart[i][0]).innerHTML = totalPrice.toFixed(2);
        }
    }
}
function increaseNumber(id, step){
    var element = document.getElementById('quantity-'+id);
    var idBefore = parseInt(element.innerHTML.toString());
    var idAfter = idBefore + step;
    var price = document.getElementById('price-'+id);
    var totalPrice = parseInt(idAfter) * parseFloat(price.innerHTML);
    document.getElementById('total-price-'+id).innerHTML = totalPrice.toFixed(2);
    element.innerHTML = idAfter.toString();
}

function reduceNumber(id, step){
    var element = document.getElementById('quantity-'+id);
    var idBefore = parseInt(element.innerHTML.toString());
    var idAfter;
    if(idBefore !== step){
        idAfter = idBefore - step;
    }
    else{
        idAfter = idBefore;
    }
    element.innerHTML = idAfter.toString();
    var price = document.getElementById('price-'+id);
    var totalPrice = parseInt(idAfter) * parseFloat(price.innerHTML);
    document.getElementById('total-price-'+id).innerHTML = totalPrice.toFixed(2);
    element.innerHTML = idAfter.toString();

}


function hideProduct(id, cart){

    var block = document.getElementById('champagne-'+id);
    block.style.display = "none";

    if (cart.length===0){
        document.getElementById('empty-cart').style.display = "block";
        document.getElementById('cart-section').style.display = "none";
    }
    if(cart.length === 0){
        document.getElementById("cart-recap").style.display = "none";
    }
    var element = document.getElementById('quantity-'+id);
    var totalPrice = 0;
    element.innerHTML = "0";
    document.getElementById('total-price-'+id).innerHTML = totalPrice.toFixed(2);
}

function totalCalculation(cart){
    var priceList = document.getElementsByClassName("total-price");
    var deliveryField = document.getElementById('livraison');
    var sousTotal = 0;
    var nbrBottle = 0;
    for (var i = 0; i < priceList.length; i++){
        if (!isNaN(parseFloat(priceList[i].innerHTML))){
            sousTotal += parseFloat(priceList[i].innerHTML);
        }
    }
    for (var j = 0; j < cart.length; j++){
        nbrBottle += parseInt(cart[j][1]);
    }

    if ( 0 < parseInt(nbrBottle) && parseInt(nbrBottle)< 12){
        deliveryField.innerHTML = "18.00";
    }
    else if (11 < parseInt(nbrBottle) && parseInt(nbrBottle)< 18){
        deliveryField.innerHTML = "24.00";
    }
    else if (17 < parseInt(nbrBottle) && parseInt(nbrBottle)< 24){
        deliveryField.innerHTML = "36.00";
    }
    else{
        deliveryField.innerHTML = "00.00";
    }
    document.getElementById('sous-total').innerHTML = sousTotal.toFixed(2);
    document.getElementById('total-all').innerHTML = (parseFloat(sousTotal) + parseInt(deliveryField.innerHTML)).toFixed(2);
}

function refreshCartQtt(cart){
    $('.cart-quantity').each(function(){
            this.innerHTML = cart.length.toString();
        }
    )

}


