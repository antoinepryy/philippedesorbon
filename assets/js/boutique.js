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
        },
});

$(".shop-button").click(function(){
    id = $(this).attr('id');
    $.ajax({
        url : pathPreOrder,
        type : 'GET',
        dataType : 'json',
        data : 'bottleId=' + id,
        success : function(response, statut){
            if (response[0]){
               var selectOptions = "";
               for (var i = 0; i < response[1].length; i++){
                   selectOptions = selectOptions + "<option value='"+ response[1][i][0]+"'><span class='option-name'>"+response[1][i][1]+"</span> | <span class='option-price'>"+ response[1][i][2] + "</span> &euro;</option>";
               }
               document.getElementById('modal-option').style.display = "block";
               document.getElementById('option-select').innerHTML = selectOptions;
            }
            else{
                $.ajax({
                    url : pathAddProduct,
                    type : 'GET',
                    dataType : 'json',
                    data : 'bottleId=' + id,
                    success : function(response, statut){
                        if (response[0]){
                            unHideProduct(id, response[1]);
                            refreshCartQtt(response[2]);
                        }
                        else{
                            increaseNumber(id);
                        }
                    },
                });
            }
        },
    });
});

$("#add-product-option").click(function() {
    var addOption = document.getElementById('option-select');
    var value = addOption[addOption.selectedIndex].value;
    console.log(addOption[addOption.selectedIndex]);
    $.ajax({
        url : pathAddProduct,
        type : 'GET',
        dataType : 'json',
        data : 'bottleId=' + id + '&champagneOption=' + value,
        success : function(response, statut){
            document.getElementById('modal-option').style.display = "none";
            if (response[0]){
                unHideProductWithOption(id, response[1], response[3]);
                refreshCartQtt(response[2]);
            }
            else{
                increaseNumber(id);
            }
        },
    });
});


$(".add-button").click(function(){
    id = $(this).attr('id');
    $.ajax({
        url : pathAddProduct,
        type : 'GET',
        dataType : 'json',
        data : 'bottleId=' + id,
        success : function(response, statut){
            increaseNumber(id);
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
            reduceNumber(id);
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
            hideProduct(id, response);
            refreshCartQtt(response[0]);
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
    var idAfter = idBefore + 6;
    var price = document.getElementById('price-'+id);
    var totalPrice = parseInt(idAfter) * parseFloat(price.innerHTML);
    document.getElementById('total-price-'+id).innerHTML = totalPrice.toString()
    element.innerHTML = idAfter.toString();
    totalCalculation();
}

function reduceNumber(id){
    var element = document.getElementById('quantity-'+id);
    var idBefore = parseInt(element.innerHTML.toString());
    var idAfter;
    if(idBefore !== 6){
        idAfter = idBefore - 6;
    }
    else{
        idAfter = idBefore;
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

function unHideProductWithOption(id, quantity, optionPrice) {
    document.getElementById('empty-cart').style.display = "none";
    document.getElementById('cart-section').style.display = "block";
    document.getElementById('cart-recap').style.display = "block";
    var block = document.getElementById('champagne-'+id);
    var element = document.getElementById('quantity-'+id);
    block.style.display = "flex";
    element.innerHTML = quantity.toString();
    //var price = document.getElementById('price-'+id);
    document.getElementById('price-'+id).innerHTML = optionPrice;
    var totalPrice = parseInt(quantity) * parseFloat(optionPrice);
    document.getElementById('total-price-'+id).innerHTML = totalPrice.toString();
    totalCalculation();
}

function totalCalculation(){
    var priceList = document.getElementsByClassName("total-price");
    var sousTotal = 0;
    for (var i = 0; i < priceList.length; i++){
        if (!isNaN(parseFloat(priceList[i].innerHTML))){
            sousTotal += parseFloat(priceList[i].innerHTML);
        }
    }
    document.getElementById('sous-total').innerHTML = sousTotal.toString();
    document.getElementById('total-all').innerHTML = sousTotal.toString();
}

function refreshCartQtt(cart){
    $('.cart-quantity').each(function(){
            this.innerHTML = cart.length.toString();
        }
    )

}

$('#modal-cross').click(function(){
    document.getElementById('modal-option').style.display = "none";
});
