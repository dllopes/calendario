<?php

/*
 * Habilita sessões
 */

session_start();

/*
 * Gera o token anti-CSRF se não existir um
 */
if(!isset($_SESSION['token'])){
  $_SESSION['token'] = sha1(uniqid(mt_rand(), true));
}

/*
* Inclua as informações de configuração necessárias
*/
include_once '../sys/config/db-cred.inc.php';

/*
 * Defina as constantes para as informações de configuração
 */
foreach($C as $name => $val){
  define($name,$val);
}

/*
 * Cria um objeto PDO
 */
$dsn= "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=UTF-8";
try{
  $dbo = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
  $dbo->exec("SET CHARACTER SET uft8");
}catch (Exception $e){
  //Se a conexão com o banco falhar, imprime o erro
  die ($e->getMessage());
}


/*
 * Defina a função de carga automática para classes
 */
function __autoload($class){
  $filename = "../sys/class/class." . $class . ".inc.php";
  if(file_exists($filename)){
    include_once $filename;
  }
}