<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Dllopes
 * Date: 13/09/12
 * Time: 16:11
 * To change this template use File | Settings | File Templates.
 */
class Event
{
  /**
   * O ID do evento
   * @var int
   */
  public $id;

  /**
   * O Título do evento
   * @var string
   */
  public $title;

  /**
   * A descrição do evento
   * @var String
   */
  public $description;

  /**
   * Horário de início do evento
   * @var string
   */
  public $start;

  /**
   * Horário de término do evento
   * @var string
   */
  public $end;

  /**
   * Recebe uma matriz de dados de evento e os armazena
   * @param array $event Matriz assosciativa de dados de evento
   * @return void
   */
  public function __construct($event){
    if(is_array($event)){
      $this->id=$event['event_id'];
      $this->title=$event['event_title'];
      $this->description=$event['event_desc'];
      $this->start=$event['event_start'];
      $this->end=$event['event_end'];
    }else{
      throw new Exception("Nenhum dado de evento foi fornecido.");
    }
  }
}
