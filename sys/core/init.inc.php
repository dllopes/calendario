<?php
/*
 * Inclua as informações de configuração necessárias
 */
include_once '../config/db-cred.inc.php';
/*
 * Defina as constantes para as informações de configuração
 */
foreach($C as $name => $val){
  define($name,$val);
}

/*
 * Crie um objeto PDO
 */
echo DB_HOST;
$dsn= "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
$dbo = new PDO($dsn, DB_USER, DB_PASS);

/*
 * Defina a função de carga automática para classes
 */
function __autoload($class){
  $filename = "../class/class." . $class . ".inc.php";
  if(file_exists($filename)){
    include_once $filename;
  }
}