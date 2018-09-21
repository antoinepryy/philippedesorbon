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
            unHideProduct(id);
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
            //$(code_html).appendTo("#commentaires"); // On passe code_html à jQuery() qui va nous créer l'arbre DOM !
        },


    });
});

function renderCart(cart){
    console.log(cart);
    for (var i = 0; i < cart.length; i++) {
        var block = document.getElementById('champagne-'+cart[i][0]);
        var quantity = document.getElementById('quantity-'+cart[i][0]);
        block.style.display = "flex";
        quantity.innerHTML = cart[i][1].toString();
    }
}
function increaseNumber(id){
    var element = document.getElementById('quantity-'+id);
    var idBefore = parseInt(element.innerHTML.toString());
    var idAfter = idBefore + 1;
    element.innerHTML = idAfter.toString();
    console.log(element);
}

function reduceNumber(id){
    var element = document.getElementById('quantity-'+id);
    var idBefore = parseInt(element.innerHTML.toString());
    var idAfter = idBefore - 1;
    element.innerHTML = idAfter.toString();
    console.log(element);
}


function hideProduct(id){
    var block = document.getElementById('champagne-'+id);
    block.style.display = "none";
}

function unHideProduct(id) {
    var block = document.getElementById('champagne-'+id);
    block.style.display = "flex";
}
