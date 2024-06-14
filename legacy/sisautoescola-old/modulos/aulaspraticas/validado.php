<?php
include_once("../../configuracao.php");

$idaulapratica = $_POST["idaulapratica"];
$validado = $_POST["validado"];

$retorno = null;
$pFields = null;

if (!isset ($idaulapratica) or strlen($idaulapratica) == 0) {
    $retorno["status"] = "erro";
    $retorno["msg"] = "Escolha um aluno.";
    echo json_encode($retorno);
    exit;
}

$mysql = new modulos_global_mysql();

$pFields["validado"] = "'".$validado."'";

$idaulapratica = $mysql->save($idaulapratica, 'aulaspraticas', $pFields, "id = '".$idaulapratica."'");

if ($idaulapratica) {
    $retorno["status"] = "ok";
    $retorno["idaulapratica"] = $idaulapratica;
    $retorno["msg"] = '<a href="#" id="btnExcAlunoAulaPratica" style="color:blue;">'. $nomealuno .'<input type="hidden" value="'. $idaulapratica .'" /></a>';
} else {
    $retorno["status"] = "erro";
    $retorno["msg"] = "Erro ao gravar data. SQL: ".$mysql->getMsgErro();
}

echo json_encode($retorno);

?>