<?php

/*
 * Assegura que o ID do evento tenha sido passado
 */
if(isset($_POST['event_id'])){
  /*
   * Pega o ID do evento da string da URL
   */
  $id = (int) $_POST['event_id'];

}else{
  /*
   * Envia o usuário para a página principal se nenhum ID tiver sido fornecido
   */
  header("Location:./");
  exit;
}

/*
 * Inclui os arquivos necessários
 */
include_once '../sys/core/init.inc.php';

/*
 * Carrega o canlendário
 */
$cal = new Calendar($dbo);
$markup = $cal->confirmDelete($id);

/*
 * Imprime o cabeçalho
 */
$page_title = "View Event";
$css_files = array("style.css","admin.css");
include_once 'assets/common/header.inc.php';

?>

<div id="content">
    <?php echo $markup; ?>
</div><!-- end #content -->

<?php
/*
 * Imprime o rodapé
 */
include_once 'assets/common/footer.inc.php';

?>