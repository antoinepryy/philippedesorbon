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
            unHideProduct(id, response);
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
            hideProduct(id);
        },


    });
});

function renderCart(cart){
    for (var i = 0; i < cart.length; i++) {
        var block = document.getElementById('champagne-'+cart[i][0]);
        var quantity = document.getElementById('quantity-'+cart[i][0]);
        var price = document.getElementById('price-'+cart[i][0]);
        block.style.display = "flex";
        quantity.innerHTML = cart[i][1].toString();
        var totalPrice = parseInt(quantity.innerHTML) * parseFloat(price.innerHTML);
        console.log(totalPrice, quantity.innerHTML, price.innerHTML);
        document.getElementById('total-price-'+cart[i][0]).innerHTML = totalPrice.toString()
    }
}
function increaseNumber(id){
    var element = document.getElementById('quantity-'+id);
    var idBefore = parseInt(element.innerHTML.toString());
    var idAfter = idBefore + 1;
    var price = document.getElementById('price-'+id);
    var totalPrice = parseInt(idAfter) * parseFloat(price.innerHTML);
    document.getElementById('total-price-'+id).innerHTML = totalPrice.toString()
    element.innerHTML = idAfter.toString();
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


}


function hideProduct(id){
    var block = document.getElementById('champagne-'+id);
    block.style.display = "none";
}

function unHideProduct(id, quantity) {
    var block = document.getElementById('champagne-'+id);
    var element = document.getElementById('quantity-'+id);
    block.style.display = "flex";
    element.innerHTML = quantity.toString();
}
