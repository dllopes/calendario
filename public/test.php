<?php
////Inclui os arquivos necessários
//include_once('../sys/core/init.inc.php');
//
////Carrega o objeto admin
//$obj = new Admin($dbo);
//
////Gera um objeto com sal de "admin"
//$pass = $obj->testSaltedhash("admin");
//echo 'Hash of "admin":<br />',$pass,"<br /><br />";
//
//
////Carrega um HASH da palavra test e imprima-o
//$hash1 = $obj->testSaltedhash("test");
//echo "Hash 1 without a Salt:<br />",$hash1, "<br /><br />";
//
////Pause a execução por um segundo para obter um timestamp diferente
//sleep(1);
//
////Carrega um segundo hash da palavra test
//$hash2 = $obj->testSaltedHash("test");
//echo "Hash 2 without a salt:<br />",$hash2,"<br /><br />";
//
////Pause a execução por um segundo para obter um timestamp diferente
//sleep(1);
//
////Executa o hash novamente na palavra test com o sal existente
//$hash3=$obj->testSaltedhash("test",$hash2);
//
//echo "Hash 3 with the salt from hash 2:<br />",$hash3;
//
//?>