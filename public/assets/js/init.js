//Assegura que o documento esteja pronto antes de executar scripts
jQuery(function($){

    //Arquivo ao qual as solicitações AJAX devem ser enviadas
    var processFile = "assets/inc/ajax.inc.php",

    //Funções para manipular a janela modal
    fx = {
        //Verifica se há uma janela modal e a retorna, ou cria uma nova e retorna
        "initModal" : function(){
            //Se nenhum elemento corresponder, a propriedade legth retornará 0
            if($(".modal-window").length==0)
            {
                //Cria um div, adiciona uma classe e a insere ao rótulo body
                return $("<div>")
                    .hide()
                    .addClass("modal-window")
                    .appendTo("body");

            }else{
                //Retorna a janela modal se uma já existir no DOM
                return $(".modal-window");
            }
        },

        //Adiciona a janela à marcação e executa fade in
        "boxin": function(data, modal){
            //Cria uma sobreposição para o site, adiciona uma classe e um manipulador
            //de evento click e então insere no elemento body
            $("<div>")
                .hide()
                .addClass("modal-overlay")
                .click(function(event){
                    //Removes event
                    fx.boxout(event);
                })
                .appendTo("body");

            //Carrega dados na janela modal e insere no elemento body
            modal
                .hide()
                .append(data)
                .appendTo("body");

            //Executa fade in na janel modal e a sobreposição
            $(".modal-window, .modal-overlay")
                .fadeIn("slow");
        },

        //Executa o efeito de fade out na janela e a remove do DOM
        "boxout": function(event){
            //Se um evento tiver sido disparado pelo elemento que chamou esta função,
            //impede que a função defaul seja executada
            if(event!=undefined){
                event.preventDefault();
            }

            //Remove a classe ativa de todos os links
            $("a").removeClass("active");

            //Executa o efeito de fade out na janela modal e então remove-a completamente do DOM
            //Executa fad out na janela modal e sobreposição e a seguir remove ambas completamente do DOM
            $(".modal-window,.modal-overlay")
                .fadeOut("slow", function(){
                    $(this).remove();
                });
        },
        "addevent": function(data, formData){
            //Codigo para adicionar um novo evento vem aqui.
        },
        //Desserializa a string de consulta e retorna um objeto de evento
        "deserialize": function(str){
            //Deserializa os dados aqui
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

        //Cria um botão para fechar a janela
        $("<a>")
            .attr("href","#")
            .addClass("modal-close-btn")
            .html("&times;")
            .click(function(event){
                //Remove a janela modal
                fx.boxout(event);
            })
            .appendTo(modal);

        //Carrega os dados dos eventos a partir do BD
        $.ajax({
            type: "POST",
            url: processFile,
            data: "action=event_view&" + data,
            success: function(data){
                fx.boxin(data, modal);
//                //Dados de evento de alerta por enquanto
//                modal.append(data);
            },
            $error: function(msg){
                modal.append(msg);
            }
        });
    });

    //Exibe o formulário de edição como uma janela modal
    $(".admin").live("click", function(event){

        //Evita que o formulário seja submetido
        event.preventDefault();

        //Carrega a ação para o arquivo de processamento
        var action = "edit_event";

        //Carrega o formulário de edição e exibi
        $.ajax({
            type: "POST",
            url: processFile,
            data: "action="+action,
            success: function(data){
                //Esconde o formulário
                var form = $(data).hide();

                //Assegura que a janela modal exista
                modal = fx.initModal();

                //Chama a função boxin para criar a superposição modal e fade in
                fx.boxin(null, modal);

                //Carrega o formulário para a janela
                //Executa o fade in no seu conteúdo e adiciona uma classe ao formulário
                form
                    .appendTo(modal)
                    .addClass("edit-form")
                    .fadeIn("slow");
            },
            error: function(msg){
                alert(msg);
            }
        })

        //Registra uma mensagem para provar que o manipulador foi disparado
        console.log("Add a New Event button clicked!");

    });

    //Edita os evento sem recarregar
    $(".edit-form input[type=submit]").live("click", function(event){

        //Impede a execução da ação do formulário default
        event.preventDefault();

        //Serializa os dados do formulário para uso com $.ajax()
        var formData = $(this).parents("form").serialize();

        //Envia os dados para o arquivo de processamento;
        $.ajax({
            type: "POST",
            url: processFile,
            data: formData,
            success: function(data){
                //Executa fade out na janela modal
                fx.boxout();

                //Registra uma mensagem no console
                console.log("Evento Salvo!");
            },
            error: function(msg){
                alert(msg);
            }
        })

        //Imprime a mensagem para indicar que o script está funcionando
        console.log(formData);
    });

    //Assegure-se que o batão cancel em formulários de edição se comportem como o botão
    //close e e execute o fade out em janelas modais e superposições.
    $(".edit-form a:contains(Cancelar)").live("click",function(event){
        fx.boxout(event);
    })

})