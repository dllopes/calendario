//Assegura que o documento esteja pronto antes de executar scripts
jQuery(function($){

    //Funções para manipular a janela modal
    var fx = {
        //Verifica se há uma janela modal e a retorna, ou cria uma nova e retorna
        "initModal" : function(){
            //Se nenhum elemento corresponder, a propriedade legth retornará 0
            if($(".modal-window").length==0)
            {
                //Cria um div, adiciona uma classe e a insere ao rótulo body
                return $("<div>")
                        .addClass("modal-window")
                        .appendTo("body");

            }else{
                //Retorna a janela modal se uma já existir no DOM
                return $(".modal-window");
            }
        }
    };

    //Traz eventos em uma janela modal
    $("li>a").live("click",function(event){

        //Impede que o link carregue view.php
        event.preventDefault();

        //Adiciona uma classe "ativa" ao link
        $(this).addClass("active");

        //Obtém a string de consulta do href do link
        var data = $(this)
            .attr("href")
            .replace(/.+?\?(.*)$/, "$1"),

        //Verifica se a janela modal existe e a selecina ou cria uma nova
        modal = fx.initModal();
    });

})