<?php
/*
 * Habilida sessões
 */
session_start();

/*
 * Inclui os arquivos necessários
 */
include_once '../../../sys/config/db-cred.inc.php';

/*
 * Define constantes para as informações de configuração
 */
foreach ($C as $name => $val){
  define($name, $val);
}

/*
 * Cria uma matriz de busca para ações de formulário
 */
$actions = array(
  'event_view' => array(
    'object' => 'Calendar',
    'method' => 'displayEvent'
  ),
  'edit_event' => array(
    'object' => 'Calendar',
    'method' => 'displayForm'
  ),
  'event_edit' => array(
    'object' => 'Calendar',
    'method' => 'processForm',
  ),
  'delete_event' => array(
  'object' => 'Calendar',
  'method' => 'confirmDelete'
  ),
  'confirm_delete' => array(
    'object' => 'Calendar',
    'method' => 'confirmDelete'
  )
);

/*
 * Assegura que o token anti-CSRF tenha sido passado e que a ação
 *  solicidada exista na matriz de busca
 */
if(isset($actions[$_POST['action']])){
  $use_array = $actions[$_POST['action']];
  $obj = new $use_array['object']($dbo);

  /*
   * Procura um ID e o limpa se encontrar
   */
  if(isset($_POST['event_id'])){
    $id = (int) $_POST['event_id'];
  }else{ $id = null; }

  echo $obj->$use_array['method']($id);
}

function __autoload($class_name){
  $filename = '../../../sys/class/class.'. strtolower($class_name) . '.inc.php';
  if(file_exists($filename)){
    include_once($filename);
  }
}














?>