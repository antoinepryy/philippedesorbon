$(".shop-button").click(function(){
    var path = $("#add-product").attr("data-path");
    $.ajax({
        url : path,
        type : 'GET',
        dataType : 'html',
        success : function(code_html, statut){
            alert('ok');
            //$(code_html).appendTo("#commentaires"); // On passe code_html à jQuery() qui va nous créer l'arbre DOM !
        },


    });

});