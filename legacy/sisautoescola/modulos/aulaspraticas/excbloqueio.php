<?php
include_once("../../configuracao.php");

$idcarro = $_POST["idcarro"];
$data = $_POST["data"];
$hora = $_POST["hora"];

$mysql = new modulos_global_mysql();

$mysql->delete(
        'aulaspraticasbloqueio',
        "idcarro = '$idcarro' and data = '".date_to_db($data)."' and hora = '$hora'");

?>