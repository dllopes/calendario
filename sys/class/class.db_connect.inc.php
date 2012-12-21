<?php
  class DB_Connect {
    /**
     * Armazena um objeto de banco de dados
     *
     * @var object Um objeto de banco de dados
     */
    protected $db;

      /**
       * Verifica um objeto DB ou cria se não encontrar
       *
       * @param object $dbo Um objeto de banco de dados
       */
    protected  function __construct($dbo=NULL){
      if(is_object($dbo)){
        $this->db = $dbo;
      }else{
        // Constantes são definidas em /sys/config/db-cred.inc.php
        $dsn = "mysql:host=". DB_HOST . ";dbname=" . DB_NAME . ";charset=UTF-8";
        try{
          $this->db = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
          $this->db->exec("SET CHARACTER SET uft8");
        }catch (Exception $e){
          //Se a conexão com o banco falhar, imprime o erro
          die ($e->getMessage());
        }

//        $conexao = mysql_connect(DB_HOST, DB_USER, DB_PASS);
//        mysql_select_db(DB_NAME, $conexao);
//        mysql_query("SET NAMES 'utf8'");
//        mysql_query("SET character_set_connection=utf8?");
//        mysql_query("SET character_set_client=utf8?");
//        mysql_query("SET character_set_results=utf8?");
//
//        $this->db = $conexao;
      }
    }
  }