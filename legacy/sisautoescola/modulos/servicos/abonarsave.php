<?php
include_once("../../configuracao.php");

$idaulapratica = $_POST["idaulapratica"];
$abonomotivo   = $_POST["abonomotivo"];

$mysql = new modulos_global_mysql();

$validacao = true;
$retorno = null;

if (!isset ($abonomotivo) or $abonomotivo == "") {
    $validacao = false;
    $retorno["status"] = 'erro';
    $retorno["msg"] = 'O campo motivo é obrigatório!';
}

if ($validacao) {
    $fields = null;
    $fields["abonomotivo"] = "'".$abonomotivo."'";
    $fields["abono"] = "'S'";
    $idaulapratica = $mysql->save($idaulapratica, 'aulaspraticas', $fields, "id = '".$idaulapratica."'");
    if ($idaulapratica) {
        $retorno["status"][] = "ok";
    } else {
        $retorno["status"][] = "erro";
        $retorno["msg"][] = "SQL: ". $mysql->getMsgErro();
    }
}

echo json_encode($retorno);

?>