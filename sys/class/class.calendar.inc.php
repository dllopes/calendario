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
  public  function __construct($dbo=null, $useDate=null){
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
     * Determine em que dia da semana o mês começa
     */
    $ts = mktime(0, 0, 0, $this->_m, 1, $this->_y);
    $this->_startDay = date('w',$ts);
  }

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
    $weekdays = array('Dom','Seg','Ter','Qua','Quin','Sex','Sab');

    /*
     * Adiciona um cabeçalho à marcação do calendário
     */
    $html = "\n\t<h2>$cal_month</h2>";
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
     * Retorna a marcação para a sáida
     */
    return $html;
  }
}
