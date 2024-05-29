<?php
include_once("../../configuracao.php");

$idcarro = $_POST["idcarro"];
$data = $_POST["data"];
$hora = $_POST["hora"];

$mysql = new modulos_global_mysql();

$total = $mysql->getValue(
        "count(*) as total",
        'total',
        'aulaspraticasbloqueio',
        "idcarro = '$idcarro' and data = '".date_to_db($data)."' and hora = '$hora'");

$idbloqueio = 0;
if ($total == 0) {

    $pFields = NULL;
    $pFields["idcarro"] = $idcarro;
    $pFields["data"] = "'".date_to_db($data)."'";
    $pFields["hora"] = "'".$hora."'";

    $idbloqueio = $mysql->save(0, 'aulaspraticasbloqueio', $pFields, NULL);
} else {
    $idbloqueio = $mysql->getValue(
        "max(id) total",
        'total',
        'aulaspraticasbloqueio',
        "idcarro = '$idcarro' and data = '".date_to_db($data)."' and hora = '$hora'");
}

$retorno["idbloqueio"] = $idbloqueio;

echo json_encode($retorno);
?>