<?php
include_once("../../configuracao.php");

$idaulapratica = $_POST["idaulapratica"];

$mysql = new modulos_global_mysql();

$fields["abonomotivo"] = "null";
$fields["abono"] = "'N'";
$idaulapratica = $mysql->save($idaulapratica, 'aulaspraticas', $fields, "id = '".$idaulapratica."'");
if ($idaulapratica) {
    $retorno["status"][] = "ok";
} else {
    $retorno["status"][] = "erro";
    $retorno["msg"][] = "SQL: ". $mysql->getMsgErro();
}

echo json_encode($retorno);

?>