<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Dllopes
 * Date: 10/09/12
 * Time: 13:53
 * To change this template use File | Settings | File Templates.
 */
class Calendar extends DB_Connect{
  /**
   * A data a partir da qual o calendário deve ser criado
   * Armazenado no formato AAAA-MM-DD HH:MM:SS
   * @var string a data a usar para o calendário
   */
  private $_useDate;

  /**
   * O mês para o qual o calendário está sendo criado
   *
   * @var int o mês sendo usado
   */
  private $_m;

  /**
   * O ano a partir do qual o dia inicial do mês é selecionado
   *
   * @var int o ano sendo usado
   */
  private $_y;

  /**
   * O número de dias no mês sendo usado
   *
   * @var int o número de dias do mês
   */
  private $_daysInMonth;

  /**
   * O índice do dia da semana em que o mês começa (0-6)
   *
   * @var int o dia da semana em que o mês começa
   */
  private $_startDay;

  /**
   * Array de meses em português (Código pessoal)
   */
  private $_meses = array(1 => "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
  "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");

  /**
   * Array dos dias da semana em portugês (Código pessoal)
   */
  private $_semanas = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sábado");

  /**
   * Cria um objeto de banco de dados e armazena dados relevantes
   *
   * Na instanciação, esta classe recebe um objeto de banco de dados que, se não for nulo, é armazenado na
   * propriedade privada $_db do objeto. Se for nulo, um novo objeto DBO é criado e armazenado
   *
   * Informações adicionais são coletadas e armazenadas neste método, incluindo o mês a partir dp qual o calendário
   * deve ser criado, quantos dias há nesse mês, em que dia o mês inicia e qual é o dia atual.
   *
   * @param object $dbo um objeto de banco de dados
   * @param string $useDate a dada a usar para criar o calendário
   * @return void
   */
  public function __construct($dbo=null, $useDate=null){
    /**
     * Chama o construtor da classe mãe para verificar a existência de um objeto de banco de dados
     */
    parent::__construct($dbo);

    /*
     * Junta e armazena dados relevantes para o mês
     */
    if(isset($useDate)){
      $this->_useDate = $useDate;
    }
    else{
      $this->_useDate = date('Y-m-d H:i:s');
    }

    /*
     * Converte para um timestamp e depois determina o mês e o ano a usar ao criar o calendário
     */
    $ts = strtotime($this->_useDate);
    $this->_m = date('m',$ts);
    $this->_y = date('Y',$ts);

    /*
     * Determine quantos dias há no mês
     */
    $this->_daysInMonth = cal_days_in_month(
      CAL_GREGORIAN,
      $this->_m,
      $this->_y
    );

    /*
     * Determina em que dia da semana o mês começa
     */
    $ts = mktime(0, 0, 0, $this->_m, 1, $this->_y);
    $this->_startDay = date('w',$ts);
  }

  /**
   * Retorna marcação HTML para exibir o calendário e os eventos
   *
   * Usando as informações armazenadas nas propriedades de classe, os eventos do mês especificado
   * são carregados, o calendário é gerado e tudo é retornado como marcação válida.
   *
   * @return string a marcação HTML do calendário
   */
  public function buildCalendar(){
    /*
     * Determina o mês do calendário e cria uma matriz de abreviações de dias da semana para rotular
     * as colunas do calendário
     */
    $cal_month = date('F Y', strtotime($this->_useDate));
    $cal_id = date('Y-m', strtotime($this->_useDate));

    //Código adicionado (pessoal) para mostrar a data em português
    $mes = date('n',strtotime($this->_useDate));
    $ano = date('Y',strtotime($this->_useDate));
    $cal_month = $this->_meses[$mes] . " " .date('Y', strtotime($this->_useDate));
    //fim código pessoal

    $weekdays = array('Dom','Seg','Ter','Qua','Quin','Sex','Sab');

    /*
     * Adiciona um cabeçalho à marcação do calendário
     */
    $html = "\n\t<h2 id=\"month-$cal_id\">$cal_month</h2>";
    for($d=0, $labels=NULL; $d<7; ++$d){
      $labels .= "\n\t\t<li>". $weekdays[$d] . "</li>";
    }
    $html .= "\n\t<ul class=\"weekdays\">". $labels . "\n\t</ul>";

    /*
     * Carrega os dados do evento
     */
    $events = $this->_createEventObj();

    /*
     * Cria a marcação do calendário
     */
    $html .= "\n\t<ul>"; //Inicia uma nova lista não ordenada
    /*
     * $i -> posição atual no calendário (36 posições) // $c -> data que se encontra na posição $i
     * // $t -> data de hoje // $m -> mes de hoje // $y -> ano de hoje
     */
    for($i=1,$c=1,$t=date('j'),$m=date('m'),$y=date('Y'); $c<=$this->_daysInMonth; ++$i)
    {
      /*
       * Aplica uma classe de "preenchimento" às caixas que ocorrem antes do primeiro mês
       */
      $class = $i <= $this->_startDay ? "fill" : NULL;

      /*
       * Adiciona uma classe "hoje" se a data atual corresponder à data corrente
       */
      //echo $c+1 . "=" . $t . " / " . $m . "=" . $this->_m. " / " . $y . "=" . $this->_y . "<br />";
      if($c+1==$t && $m==$this->_m && $y==$this->_y){
        $class = "today";
      }

      /*
       * Cria rótulos de item de lista abertura e fechamento
       */
      $ls = sprintf("\n\t\t<li class=\"%s\">", $class);
      $le = "\n\t\t</li>";

      /*
       * Adiciona o dia do mês para identificar a caixa do calendário
       * (o dia corrente se encontra depois do primeiro dia do mês? &&  )
       */
      if($this->_startDay<$i && $this->_daysInMonth>=$c){
        /*
         * Formate os dados dos eventos events data
         */
        $event_info = NULL; //limpa a variável
        if(isset($events[$c])){
          foreach($events[$c] as $event){
            $link = '<a href="view.php?event_id='
              . $event->id.'">'. $event->title
              . '</a>';
            $event_info .= "\n\t\t\t$link";
          }
        }
        $date = sprintf("\n\t\t\t<strong>%02d</strong>",$c++);
      }else{
        $date = "&nbsp;";
      }

      /*
       * Se o dia corrente for um sábado, passa para a próxima linha
       */
      $wrap = $i!=0 && $i%7==0 ? "\n\t</ul>\n\t<ul>" : NULL;

      /*
       * Junta as partes em um item pronto
       */
      $html .= $ls . $date . $event_info . $le . $wrap;
    }

    /*
     * Adiciona um preenchimento para completar a última semana'
     */
    while($i%7!=1){
      $html .="\n\t\t<li class=\"fill\">&nbsp;</li>";
      ++$i;
    }

    /*
     * Fecha a lista não ordenada final
     */
    $html .= "\n\t</ul>\n\n";

    /*
     * Se não estiver conectado, exibi as opções administrativas
     */
    $admin= $this->_adminGeneralOptions();

    /*
     * Retorna a marcação para a sáida
     */
    return $html . $admin;
  }

  /**
   * Exibe informações de um determinado evento
   *
   * @param int $id o ID do evento
   * @return string marcação básica para exibir informações de eventos
   */
  public function displayEvent($id){
    /*
     * Assegura de que um ID foi passado
     */
    if(empty($id)){ return null;}

    /*
     * Assegura de que o ID seja um número inteiro
     */
    $id = preg_replace('/[^0-9]/','',$id);

    /*
     * Carrega os dados dos eventos a partir do BD
     */
    $event = $this->_loadEventById($id);

    /*
     * Gera strings para a data, horário inicial e final
     */
    $ts = strtotime($event->start);
    $date = date('F d, Y', $ts);
    $start = date('g:ia',$ts);
    $end = date('g:ia', strtotime($event->end));

    /*
     * Carrega opções adimnistrativas se o usuário estiver conectado
     */
    $admin = $this->_adminEntryOptions($id);

    /*
     * Gera e retorna a marcação
     */
    return "<h2>$event->title</h2>"
          ."\n\t<p class=\"dates\">$date, $start&mdash;$end</p>"
          ."\n\t<p>$event->description</p>$admin";
  }

  /**
   * Gera um formulário para editar ou criar eventos
   *
   * @retorna string a marcação HTML para o formulário de edição
   */
  public function displayForm(){
    /*
     * Verifica se um ID foi passado
     */
    if(isset($_POST['event_id'])){
      $id = (int) $_POST['event_id'];
        //Impõe o tipo inteiro para limpar os dados
    }else{
      $id = NULL;
    }

    /*
     * Instancia o texto do botão de submissão/cabeçalho
     */
    $submit = "Criar Novo Formulário";

    /*
     * Se for passado um ID, carrega o evento associado
     */
    if(!empty($id)){
       $event = $this->_loadEventById($id);

      /*
       * Se nenhum objeto for retornado, retorna NULL;
       */
      if(!is_object($event)) {return NULL;}

      $submit = "Editar Este Evento";
    }

    /*
    * Cria a marcação
    */
    return <<<FORM_MARKUP

  <form action="assets/inc/process.inc.php" method="post">
    <Fieldset>
      <legend>$submit</legend>
      <label for="event_title">Título do Evento</label>
      <input type="text" name="event_title" id="event_title" value="$event->title"/>
      <label for="event_start">Hora de Início</label>
      <input type="text" name="event_start" id="event_start" value="$event->start" />
      <label for="event_end">Hora de Término</label>
      <input type="text" name="event_end" id="event_end" value="$event->end" />
      <label for="event_description">Descrição do Evento</label>
      <textarea name="event_description" id="event_description">$event->description</textarea>
      <input type="hidden" name="event_id" value="$event->id" />
      <input type="hidden" name="token" value="$_SESSION[token]" />
      <input type="hidden" name="action" value="event_edit" />
      <input type="submit" name="event_submit" value="$submit" />
      ou <a href="./">Cancelar</a>
    </Fieldset>
  </form>
FORM_MARKUP;

  }

  /**
   * Valida o formulário e grava/edita o evento
   *
   * @Retorna mixed TRUE em caso de sucesso ou uma mensagem de erro em caso de falha
   */
  public function processForm(){
    /*
     * Sai se a ação não for configurada apropriadamente
     */
    if($_POST['action']!='event_edit'){
      return "O método processForm foi acessado incorretamente";
    }

    /*
     * Tira os dados do formulário
     */
    $title = htmlentities($_POST['event_title'], ENT_QUOTES);
    $desc = htmlentities($_POST['event_description'], ENT_QUOTES);
    $start = htmlentities($_POST['event_start'], ENT_QUOTES);
    $end = htmlentities($_POST['event_end'], ENT_QUOTES);

    /*
     * Se nenhum Id de evento for passado, cria um novo evento
     */
    if(empty($_POST['event_id'])){
      $sql = "INSERT INTO `events` (`event_title`,`event_desc`,`event_start`,`event_end`)
              VALUES
                (:title, :description, :start, :end)";
    }

    /*
     * Atualiza o evento se estiver sendo editado
     */
    else{
      /*
       * Converte o ID do evento para inteiro por motivo de segurança
       */
      $id = (int) $_POST['event_id'];
      $sql= "UPDATE `events`
             SET
              `event_title`=:title,
              `event_desc`=:description,
              `event_start`=:start,
              `event_end`=:end
              WHERE `event_id`=$id";
    }

    /*
     * Executa a consulta de criação ou edição após conectar os dados
     */
    try{
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(":title", $title, PDO::PARAM_STR);
      $stmt->bindParam(":description", $desc, PDO::PARAM_STR);
      $stmt->bindParam(":start", $start, PDO::PARAM_STR);
      $stmt->bindParam(":end", $end, PDO::PARAM_STR);
      $stmt->execute();
      $stmt->closeCursor();

      /*
       * Retorna o ID do evento
       */
      return $this->db->lastInsertId();
    }catch(Exception $e){
      return $e->getMessage();
    }
  }

  /**
   * Confirma se um evento deve ser excluído
   *
   * Ao clicar o botão para excluir um evento, isto gera uma caixa de confirmação. Se o usuário confirmar,
   * será exluído o evento do banco de dados e envia o usuário de volta para a visualização do calendário. Se o usuário
   * decidir não excluir o evento, é enviado de volta para a visualização principal do calendário sem excluir nada.
   */
  public function confirmDelete($id){
    /*
     * Assegura que um ID tenha sido passado
     */
    if(empty($id)){return null;}

    /*
     * Assegura que o id é um número inteiro
     */
    $id = preg_replace('/[^0-9]/','',$id);


    /*
     * Se o formulário de confirmação tiver sido submetido e o formulário tiver um
     * token válido, verifica a submissão do formulário
     */
    if(isset($_POST['confirm_delete']) && $_POST['token']==$_SESSION['token']){
      /*
       * Se a exclusão for confirmada, remove o evento do banco de dados
       */
      if($_POST['confirm_delete']=="Sim, pode deletar"){
        $sql = "DELETE FROM `events`
                WHERE `event_id`=:id
                LIMIT 1";
        try{
          $stmt = $this->db->prepare($sql);
          $stmt->bindParam(":id", $id, PDO::PARAM_INT);
          $stmt->execute();
          $stmt->closeCursor();
          header("Location: ./");
          return;
        }catch (Exception $ex){
          return $ex->getMessage();
        }
      }

      /*
       * Se não confirmado, envia o usuário para a visualização principal
       */
      else{
        header("Location: ./");
        return;
      }
    }


    /*
     * Se o formulário de confirmação não tiver sido submetido, exibe-o
     */
    $event = $this->_loadEventById($id);
    /*
     * Se nenhum objeto for retornado, retorna a visualização principal
     */
    if(!is_object($event)){header("Location: ./");}

    return <<<CONFIRM_DELETE

  <form action="confirmdelete.php" method="post">
    <h2>
        Você quer realmente deletar o evento: "$event->title"?
    </h2>
    <p>
        <input type="submit" name="confirm_delete" value="Sim, pode deletar" />
        <input type="submit" name="confirm_delete" value="Não, estava brincando!"/>
        <input type="hidden" name="event_id" value="$event->id"/>
        <input type="hidden" name="token" value="$_SESSION[token]"/>
    </p>
  </form>
CONFIRM_DELETE;
  }


  /**
   * Carrega informações sobre evento(s) em uma matriz
   *
   * @param int $id um ID opcional de eventos para filtrar resultados
   * @return array uma matriz de eventos do banco de dados
   */
  private function _loadEventData($id=NULL){
    $sql = "SELECT
              `event_id`, `event_title`, `event_desc`, `event_start`, `event_end`
            FROM `events`";

    /*
     * Se um ID de evento for fornecido, adiciona uma cláusula WHERE de modo que apenas esse evento seja retornado
     */
    if(!empty($id)){
      $sql .= "WHERE `event_id`=:id LIMIT 1";
    }
    /*
     * Em caso contrário, carrega todos os eventos para o mês em uso
     */
    else{
      /*
       * Encontre o primeiro e o último dia do mês
       */
      $start_ts = mktime(0,0,0,$this->_m,1,$this->_y);
      $end_ts = mktime(23,59,59,$this->_m+1,0,$this->_y);
      $start_date = date('Y-m-d H:i:s',$start_ts);
      $end_date = date('Y-m-d H:i:s', $end_ts);

      /*
       * Filtra eventos para apenas os que acontecerem
       * mes corrente selecionado
       */
      $sql .= "WHERE `event_start`
               BETWEEN '$start_date'
               AND '$end_date'
              ORDER BY `event_start`";
    }

    try{
      $stmt = $this->db->prepare($sql);

      /*
       * Associa o parâmetro se um ID tiver sido passado
       */
      if(!empty($id)){
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
      }

      $stmt->execute();
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt->closeCursor();
      return $results;
    }
    catch(Exception $e){
      die($e->getMessage());
    }
  }

  /*
   * Carrega todos os eventos para o mês em uma matriz
   *
   * @return informações de eventos da matriz
   */
  private function _createEventObj(){
    /*
     * Carrega a matriz de eventos
     */
    $arr = $this->_loadEventData();

    /*
     * Cria uma nova matriz e então organiza os eventos pelo dia do mês em que ocorrem
     */
    $events = array();
    foreach($arr as $event){
      $day = date('j',strtotime($event['event_start']));
      try{
        $events[$day][] = new Event($event);
      }catch (Exception $e){
        die($e->getMessage());
      }
    }
    return $events;
  }

  /**
   * Retorna um único objeto event
   *
   * @param int $id um ID de evento
   * @return object o objeto event
   */
  private function _loadEventById($id){
    /*
     * Se nenhum ID for passado, retorna NULL
     */
    if(empty($id)){
      return NULL;
    }

    /*
     * Carrega a matriz de informações de eventos
     */
    $event = $this->_loadEventData($id);

    /*
     * Retorna um objeto evento
     */
    if(isset($event[0])){
      return new Event($event[0]);
    }else{
      return NULL;
    }
  }

  /**
   * Gera marcação paraexibir links administrativos
   */
  private function _adminGeneralOptions(){
    /*
     * Se o usuário estiver conectado, exibe os controles administrativos
     */
    if(isset($_SESSION['user'])){
      return <<<ADMIN_OPTIONS

      <a href="admin.php" class="admin">+ Add Novo Evento</a>
      <form action="assets/inc/process.inc.php" method="post">
          <div>
              <input type="submit" value="Log Out" class="admin"/>
              <input type="hidden" name="token" value="$_SESSION[token]"/>
              <input type="hidden" name="action" value="user_logout"/>
          </div>
      </form>

ADMIN_OPTIONS;
    }else{
      return <<<ADMIN_OPTIONS

      <a href="login.php">Log In</a>

ADMIN_OPTIONS;


    }
  }

  /**
   * Gera as opções de edição e exclusão para um determinado ID de evento
   *
   * @param int $id o ID do evento para o qual gerar as opções
   * @retorna string a marcação para as opções de edição/exclusão
   */
  private function _adminEntryOptions($id){
    /*
     * Se o usuário estiver logado
     */
    if (isset($_SESSION['user'])){
    return <<<ADMIN_OPTIONS

      <div class="admin-options">
        <form action="admin.php" method="post">
            <p>
                <input type="submit" name="edit_event" value="Editar Este Evento" />
                <input type="hidden" name="event_id" value="$id" />
            </p>
        </form>
        <form action="confirmdelete.php" method="post">
            <p>
                <input type="submit" name="delete_event" value="Deletar Este Evento" />
                <input type="hidden" name="event_id" value="$id">
            </p>
        </form>
      </div><!-- end .admin-options -->

ADMIN_OPTIONS;
    }else{
      return NULL;
    }
  }

}
