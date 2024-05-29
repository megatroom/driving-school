<?php
include_once('connection.php');

$dataExecucao = $_GET["dataexecucao"];

$dataArray = explode("/", $dataExecucao);
$dataExecucao = $dataArray[2] ."-". $dataArray[1] ."-". $dataArray[0];

$dataNome = date("YmdHi");

$sql  = "CREATE EVENT ".$db_host.$data." ";
$sql .= "ON SCHEDULE AT  ".$dataExecucao."";

$exec = mysql_query($sql) or die ("ERRO: ".mysql_error());


?>