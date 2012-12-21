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

        //Adiciona um novo evento à marcação após gravar
        "addevent": function(data, formData){
            //Converte a query string para um objeto
            var entry = fx.deserialize(formData),

            //Cria um objeto de data para o mês atual
                cal = new Date(NaN),

            //Cria um objeto de data para o novo evento
                event = new Date(NaN),

            //Extrai o mês do calendário a partir do ID do H2
                cdata = $("h2").attr("id").split('-'),

            //Extrai o dia, mês e ano do evento
                date = entry.event_start.split(' ')[0],

            //Divide a data em partes
                edata = date.split('-');

            //Configura a data do objeto de data do calendário
            cal.setFullYear(cdata[1], cdata[2], 1);

            //Configura a data do objeto de data do evento
            event.setFullYear(edata[0], edata[1], edata[2]);

            //Já que o objeto de data é criado usando
            //GMT e então ajustado para o fuso horário local,
            //ajuste a diferença para assegurar uma data apropriada
            event.setMinutes(event.getTimezoneOffset());

            //Se o ano e o mês corresponderem, inicie o processo
            //de adicionar o novo evento ao calendário
            if(cal.getFullYear()==event.getFullYear()
                && cal.getMonth()==event.getMonth()){

                //Obtém o dia do mês para o evento
                var day = String(event.getDate());

                //Adiciona um zero à esqueda de dias com um único dígito
                day = day.length==1 ? "0" + day : day;

                //Adiciona o link para a nova data
                $("<a>")
                    .hide()
                    .attr("href", "view.php?event_id="+data)
                    .text(entry.event_title)
                    .insertAfter($("strong:contains("+day+")"))
                    .delay(1000)
                    .fadeIn("slow");
            }
        },

        //Remove um evento da marcação após a exclusão
        "removeevent" : function(){
            //Remove qualquer evento com classe "active"
            $(".active")
                .fadeOut("slow",function(){
                    $(this).remove();
                })
        },

        //Desserializa a string de consulta e retorna um objeto de evento
        "deserialize": function(str){
            //Separa cada par nome-valor
            var data = str.split("&"),

            //Declara variáveis para usar no laço
                pairs=[], entry={}, key, val;

            //Percorre cada par nome-valor
            for(x in data){
                //Divide cada par em uma matriz
                pairs = data[x].split("=");

                //O primeiro elemento é o nome
                key = pairs[0];

                //O segundo elemento é o valor
                val = pairs[1];

                //Reverte a codificação URL e armazena
                //cada valor como uma propriedade de objeto
                entry[key] = fx.urldecode(val);
            }
            return entry;
        },

        //Decodifica um valor da string de consulta
        "urldecode" : function(str){
            //Converte sinais de soma em espaços
            var converted = str.replace(/\+/g,' ');

            //Converte de volta quaisquer entidades codificadas
            return decodeURIComponent(converted);
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
    $(".admin-options form, .admin").live("click", function(event){

        //Evita que o formulário seja submetido
        event.preventDefault();
        //Carrega a ação para o arquivo de processamento
        var action = $(event.target).attr("name") || "edit_event";

        //Grava o valor de entrada do event_id
        id=$(event.target)
            .siblings("input[name=event_id]")
            .val();

        //Cria um parâmetro adicional para o ID se configurado
        id=(id!=undefined) ? "&event_id="+id : "";

        //Carrega o formulário de edição e exibi
        $.ajax({
            type: "POST",
            url: processFile,
            data: "action="+action+id,
            success: function(data){
                //Esconde o formulário
                var form = $(data).hide();

                //Assegura que a janela modal exista
                modal = fx.initModal()
                    .children(":not(.modal-close-btn)")
                    .remove()
                    .end();

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
        var formData = $(this).parents("form").serialize(),

        //Armazena o valor do botão de submissão
            submitVal = $(this).val(),

        //Determina se o evento deve ser removido
            remove = false;

        //Se for o formulário de exclusão, insere uma ação
        if( $(this).attr("name")=="confirm_delete"){
            //Adiciona informações necessárias à string de consulta
            formData += "&action=confirm_delete"
                + "&confirm_delete="+submitVal;

            //Se o evento estiver realmente sendo excluído, configura
            //um flag para removê-lo da marcação
            if(submitVal=="Sim, pode deletar"){
                remove=true;
            }
        }

        //Envia os dados para o arquivo de processamento;
        $.ajax({
            type: "POST",
            url: processFile,
            data: formData,
            success: function(data){
                //Se este for um evento excluído, remove-o da marcação
                if(remove===true){
                    fx.removeevent();
                }

                //Executa fade out na janela modal
                fx.boxout();

                //Se este for um evento novo, adciona-o ao calendário
                if( $("[name=event_id]").val().length==0 && remove===false){
                    fx.addevent(data, formData);
                }
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