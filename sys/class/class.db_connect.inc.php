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
        $dsn = "mysql:host=". DB_HOST . ";dbname=" . DB_NAME;
        try{
          $this->db = new PDO($dsn, DB_USER, DB_PASS);
        }catch (Exception $e){
          //Se a conexão com o banco falhar, imprime o erro
          die ( $e->getMessage());
        }
      }
    }
  }