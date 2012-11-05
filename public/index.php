<?php

/*
 * Inclui os arquivos necessários
 */
include_once '../sys/core/init.inc.php';

/*
 * Carrega o calendário de janeiro
 */

//dbo foi comentado no init
$cal = new Calendar($dbo, "2010-01-01 12:00:00");

/*
 * Configura o título da página e os arquivos CSS
 */
$page_title = "Events Calendar";
$css_files = array('style.css','admin.css','ajax.css');

/*
 * Inclui o cabeçalho
 */
include_once 'assets/common/header.inc.php';

?>

<div id="content">
<?php
/*
 * Exibe o HTML do calendário
 */
echo $cal->buildCalendar();
?>

</div><!-- end #content -->
<?php
/**
 * Inclui o rodapé
 */
include_once 'assets/common/footer.inc.php';

?>