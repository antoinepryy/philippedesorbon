var path = $("#get-cart").attr("data-path");
$.ajax({
    url : path,
    type : 'GET',
    dataType : 'json',
    success : function(response, statut){
        renderCart(response);
        },
});

$(".shop-button").click(function(){
    var path = $("#add-product").attr("data-path");
    var id = $(this).attr('id');
    $.ajax({
        url : path,
        type : 'GET',
        dataType : 'json',
        data : 'bottleId=' + id,
        success : function(response, statut){
            if (response[0]===true){
                unHideProduct(id, response[1]);
            }
            else{
                increaseNumber(id);
            }
        },
    });
});


$(".add-button").click(function(){
    var path = $("#add-product").attr("data-path");
    var id = $(this).attr('id');
    $.ajax({
        url : path,
        type : 'GET',
        dataType : 'json',
        data : 'bottleId=' + id,
        success : function(response, statut){
            increaseNumber(id);
        },
    });
});

$(".remove-one-button").click(function(){
    var path = $("#remove-one").attr("data-path");
    var id = $(this).attr('id');
    $.ajax({
        url : path,
        type : 'GET',
        dataType : 'json',
        data : 'bottleId=' + id,
        success : function(response, statut){
            reduceNumber(id);
        },


    });
});

$(".remove-all-button").click(function(){
    var path = $("#remove-all").attr("data-path");
    var id = $(this).attr('id');
    $.ajax({
        url : path,
        type : 'GET',
        dataType : 'json',
        data : 'bottleId=' + id,
        success : function(response, statut){
            hideProduct(id, response);
        },


    });
});

function renderCart(cart){
    if (cart.length===0 || cart.length===undefined){
        document.getElementById('empty-cart').style.display = "block";
        document.getElementById('cart-section').style.display = "none";
        document.getElementById('cart-recap').style.display = "none";
    }

    for (var i = 0; i < cart.length; i++) {
        var block = document.getElementById('champagne-'+cart[i][0]);
        var quantity = document.getElementById('quantity-'+cart[i][0]);
        var price = document.getElementById('price-'+cart[i][0]);
        block.style.display = "flex";
        quantity.innerHTML = cart[i][1].toString();
        var totalPrice = parseInt(quantity.innerHTML) * parseFloat(price.innerHTML);
        document.getElementById('total-price-'+cart[i][0]).innerHTML = totalPrice.toString()
    }
    totalCalculation();
}
function increaseNumber(id){
    var element = document.getElementById('quantity-'+id);
    var idBefore = parseInt(element.innerHTML.toString());
    var idAfter = idBefore + 1;
    var price = document.getElementById('price-'+id);
    var totalPrice = parseInt(idAfter) * parseFloat(price.innerHTML);
    document.getElementById('total-price-'+id).innerHTML = totalPrice.toString()
    element.innerHTML = idAfter.toString();
    totalCalculation();
}

function reduceNumber(id){
    var element = document.getElementById('quantity-'+id);
    var idBefore = parseInt(element.innerHTML.toString());
    if(idBefore > 1){
        var idAfter = idBefore - 1;
    }
    element.innerHTML = idAfter.toString();
    var price = document.getElementById('price-'+id);
    var totalPrice = parseInt(idAfter) * parseFloat(price.innerHTML);
    document.getElementById('total-price-'+id).innerHTML = totalPrice.toString()
    element.innerHTML = idAfter.toString();
    totalCalculation();


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
    document.getElementById('total-price-'+id).innerHTML = totalPrice.toString();
    totalCalculation();
}

function unHideProduct(id, quantity) {
    document.getElementById('empty-cart').style.display = "none";
    document.getElementById('cart-section').style.display = "block";
    document.getElementById('cart-recap').style.display = "block";
    var block = document.getElementById('champagne-'+id);
    var element = document.getElementById('quantity-'+id);
    block.style.display = "flex";
    element.innerHTML = quantity.toString();
    var price = document.getElementById('price-'+id);
    var totalPrice = parseInt(quantity) * parseFloat(price.innerHTML);
    document.getElementById('total-price-'+id).innerHTML = totalPrice.toString();
    totalCalculation();
}

function totalCalculation(){
    var priceList = document.getElementsByClassName("total-price");
    console.log(priceList);
    var sousTotal = 0;
    for (var i = 0; i < priceList.length; i++){
        console.log(priceList[i].innerHTML);
        if (!isNaN(parseFloat(priceList[i].innerHTML))){
            sousTotal += parseFloat(priceList[i].innerHTML);
        }
    }
    document.getElementById('sous-total').innerHTML = sousTotal.toString();
    document.getElementById('total-all').innerHTML = sousTotal.toString();
}
