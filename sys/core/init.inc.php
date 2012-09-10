<?php
/*
 * Inclua as informações de configuração necessárias
 */
include_once '../sys/config/db_cred.inc.php';

/*
 * Defina as constantes para as informações de configuração
 */
foreach( $C as $name => $val){
  define($name,$val);
}

/*
 * Crie um objeto PDO
 */
$dsn= "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
$dbo = new PDO($dsn, DB_USER, DB_PASS);

/*
 * Defina a função de carga automática para classes
 */
function __autoload($class){
  $filename = "../sys/class/class." . $class . ".inc.php";
  if(file_exists($filename)){
    include_once $filename;
  }
}