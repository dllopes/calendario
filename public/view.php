<?php
/*
 * Assegura que o ID do evento tenha sido passado
 */
if(isset($_GET['event_id'])){
  /*
   * Assegura que o ID seja um número inteiro
   */
  $id = preg_replace('/[^0-9]/','', $_GET['event_id']);

  /*
   * Se o ID não for válido, evia o usuário para a página principal
   */
  if(empty($id)){
    header("Location: ./");
    exit;
  }
}else{
  /*
   * Envia o usuário para a página principal se nenhum ID for fornecido
   */
  header("Location: ./");
  exit;
}

/*
 * Inclui os arquivos necesários
 */
include_once '../sys/core/init.inc.php';

/*
 * Produz o cabeçalho
 */
$page_title = "View Event";
$css_files = array("style.css");
include_once 'assets/common/header.inc.php';

/*
 * Carrega o calendário
 */
$cal = new Calendar($dbo);

?>

<div id="content">
    <?php echo $cal->displayEvent($id) ?>

      <a href="./">&laquo; Voltar ao calendário</a>
</div><!-- end #content -->

<?php

/*
 * Reproduz o rodapé
 */
include_once 'assets/common/footer.inc.php';
?>