<?php
/*
 * Inclui os arquivos necessários
 */
include_once '../sys/core/init.inc.php';

/*
 * Se o usuário não estiver conectado, envia-o para o arquivo principal
 */
if(!isset($_SESSION['user'])){
  header("Location: ./");
  exit;
}

/*
 * Produz o cabeçalho
 */
$page_title = "Add/Edit Event";
$css_files = array("style.css", "admin.css");
include_once 'assets/common/header.inc.php';

/*
 * Carrega o calendário
 */
$cal = new Calendar($dbo);

?>

<div id="content">
    <?php echo $cal->displayForm(); ?>
</div> <!-- end #content -->

<?php
/*
 * Produz o rodapé
 */
include_once 'assets/common/footer.inc.php';

?>

