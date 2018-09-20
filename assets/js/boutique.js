$(".shop-button").click(function(){
    var path = $("#add-product").attr("data-path");
    var id = $(this).attr('id');
    $.ajax({
        url : path,
        type : 'GET',
        dataType : 'json',
        data : 'bottleId=' + id,
        success : function(code_html, statut){
            alert(code_html);
            //$(code_html).appendTo("#commentaires"); // On passe code_html à jQuery() qui va nous créer l'arbre DOM !
        },


    });
});

