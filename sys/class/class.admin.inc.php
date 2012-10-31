<?php
/*
 * Gerencia ações administrativas
 */
class Admin extends DB_Connect{
  /**
   * Determina o comprimento do sal a usar em senhas com hash
   *
   * @var int o comprimento do sal de senha a usar
   */
  private $_saltLength = 7;

  /**
   * Armazena ou cria um objeto DB e configura o comprimento do sal
   *
   * @param object $db um objeto de banco de dados
   * @param int $salLength comprimento do hash da senha
   */
  public function __construct($db=NULL,$saltLenght=NULL){
    parent::__construct($db);

    /*
     * Se um número inteiro tiver sido passado, configura o comprimento do sal
     */
    if(is_int($saltLenght)){
      $this->_saltLenth = $saltLenght;
    }
  }

  /**
   * Verifica credenciais de conexão de um usuário para ver se são válidas
   *
   * @retorna mixed TRUE em caso de sucesso, mensagem em caso de erro
   */
  public function processLoginForm(){
    /*
     * Falha se a ação apropriada não tiver sido submetida
     */
    if($_POST['action']!='user_login'){
      return "Invalid action supplied for processLoginForm.";
    }

    /*
     * Desvencilha-se da entrada do usuário por motivo de segunrança
     */
    $uname = htmlentities($_POST['uname'], ENT_QUOTES);
    $pword = htmlentities($_POST['pword'], ENT_QUOTES);
    /*
     * Recupera informações correspondentes do BD se existirem
     */
    $sql = "SELECT `user_id`,`user_name`,`user_email`,`user_pass`
            FROM `users`
            WHERE `user_name` = :uname
            LIMIT 1";

    try{
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':uname',$uname, PDO::PARAM_STR);
      $stmt->execute();
      $user = array_shift($stmt->fetchAll());
      $stmt->closeCursor();
    }catch (Exception $e){
      die($e->getMessage());
    }
    /*
     * Falha se o nome do usuário não esconder a uma entrada do BD
     */
    if(!isset($user)){
      return "Usuário não encontrado com este ID";
    }
    /*
     * Obtém o hash da senha fornecida pelo usuário
     */
    $hash = $this->_getSaltedHash($pword, $user['user_pass']);

    /*
     * Verifica se a senha com hash corresponde à senha armazenada
     */
    if($user['user_pass']==$hash){
      /*
       * Armazena informações de usuário na sessão como uma matriz
       */
      $_SESSION['user'] = array(
              'id'=>$user['user_id'],
              'name'=> $user['user_name'],
              'email'=> $user['user_email']
      );
      return TRUE;
    }

    /*
     * Falha se as senhas não corresponderem
     */
    else{
      return "Seu nome de usuário ou senha é inválido.";
    }

    // termina o processamento ...
  }

  /**
   * Desconecta o usuário
   *
   * @retorna mixed TRUE em caso de sucesso ou uma mensagem em caso de falha
   */
  public function processLogout(){
    /*
     * Falha se a ação apropriada não tiver sido submetida
     */
    if($_POST['action']!='user_logout'){
      return "Ação inválida fornecida para o processLogout.";
    }

    /*
     * Remove a matriz de usuário da sessão corrente
     */
    session_destroy();
    return TRUE;
  }

  /*
     * Gera um hash com o sal de uma string fornecida
     *
     * @param String $string  a sofrer o hash
     * @param String $sal extrair o hash daqui
     * @retorna string o hash com sal
     */
  private function _getSaltedHash($string,$salt=NULL){
    /*
     * Gera um salt se nenhum tiver sido passado
     */
    if($salt == NULL){
      $salt = substr(md5(time()),0,$this->_saltLength);
    }

    /*
     * Extrai o sal da string se um for passado
     */
    else{
      $salt = substr($salt,0,$this->_saltLength);
    }

    /*
     * Adiciona sal ao hash e retorna
     */
    return $salt . sha1($salt . $string);
  }

  public function testSaltedhash($string, $salt=NULL){
    return $this->_getSaltedHash($string,$salt);
  }
}

?>