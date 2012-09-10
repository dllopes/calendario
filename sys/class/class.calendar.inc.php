<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Dllopes
 * Date: 10/09/12
 * Time: 13:53
 * To change this template use File | Settings | File Templates.
 */
class Calendar extends DB_Connect
{
   /**
    * A data a partir da qual o calendário deve ser criado
    * Armazenado no formato AAAA-MM-DD HH:MM:SS
    * @var string a data a usar para o calendário
    */
     private $_userDate;

    /**
     * O mês para o qual o calendário está sendo criado
     *
     * @var int o mês sendo usado
     */
     private $_m;

    /**
     * O ano a partir do qual o dia inicial do mês é selecionado
     *
     * @var int o ano sendo usado
     */
     private $_y;

    /**
     * O número de dias no mês sendo usado
     *
     * @var int o número de dias do mês
     */
     private $_daysInMonth;

    /**
     * O índice do dia da semana em que o mês começa (0-6)
     *
     * @var int o dia da semana em que o mês começa
     */
     private $_startDay;

    /**
     * Cria um objeto de banco de dados e armazena dados relevantes
     *
     * Na instanciação, esta classe recebe um objeto de banco de dados que, se não for nulo, é armazenado na
     * propriedade privada $_db do objeto. Se for nulo, um novo objeto DBO é criado e armazenado
     *
     * Informações adicionais são coletadas e armazenadas neste método, incluindo o mês a partir dp qual o calendário
     * deve ser criado, quantos dias há nesse mês, em que dia o mês inicia e qual é o dia atual.
     *
     * @param object $dbo um objeto de banco de dados
     * @param string $useDate a dada a usar para criar o calendário
     * @return void
     */
      public  function __construct($dbo=null, $useDate=null){
        /**
         * Chama o construtor da classe mãe para verificar a existência de um objeto de banco de dados
         */
        parent::__construct($dbo);
      }
}
