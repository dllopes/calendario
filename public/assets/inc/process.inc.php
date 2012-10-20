<?php
/*
 * Habilita sessões
 */
session_start();

/*
 * Inclui os arquivos necessários
 */
include_once '../../../sys/config/db-cred.inc.php';

/*
 * Define constantes para informações de configuração
 */
foreach($C as $name => $val){
  define($name, $val);
}

/*
 * Cria uma matriz de busca para ações de formulário
 */
$actions = array(
  'event_edit' => array(
    'object' => 'Calendar',
    'method' => 'processForm',
    'header' => 'Location: ../../'
  )
);

/*
 * Assegura que o token anti-CSRF foi passaado e que a ação solicitada existe na matriz de busca
 */
if($_POST['token']==$_SESSION['token'] && isset($actions[$_POST['action']])){
  $use_array = $actions[$_POST['action']];
  $obj = new $use_array['object']($dbo);
  if(TRUE == $msg=$obj->$use_array['method']()){
    header($use_array['header']);
    exit;
  }else{
    //Se um erro tiver ocorrido, imprime-o e termina a execução
    die($msg);
  }
}else{
  //Redireciona para índice principal se o token/ação for inválido
  header("Location: ../../");
  exit;
}

function __autoload($class_name){
  $filename = '../../../sys/class/class.'
      . strtolower($class_name) . '.inc.php';
  if(file_exists($filename)){
    include_once $filename;
  }

}