$("#more_com").click(function(){

    $.ajax({
        url : '{{ path(\'addProduct\') }}',
        type : 'GET',
        dataType : 'html',
        success : function(code_html, statut){
            $(code_html).appendTo("#commentaires"); // On passe code_html à jQuery() qui va nous créer l'arbre DOM !
        },


    });

});