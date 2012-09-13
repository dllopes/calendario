<?php

/*
 * Inclui os arquivos necessários
 */
include_once '../sys/core/init.inc.php';

/*
 * Carrega o calendário de janeiro
 */
$cal = new Calendar($dbo, "2010-01-01 12:00:00");

/*
 * Exibe o HTML do calendário
 */
echo $cal->buildCalendar();