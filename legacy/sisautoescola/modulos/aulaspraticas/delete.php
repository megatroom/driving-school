<?php
include_once("../../configuracao.php");

$idaulapratica = $_POST["idaulapratica"];

$retorno = null;
$pFields = null;

if (!isset ($idaulapratica) or strlen($idaulapratica) == 0) {
    $retorno["status"] = "erro";
    $retorno["msg"] = "Aula não informada.";
    echo json_encode($retorno);
    exit;
}

$mysql = new modulos_global_mysql();

$countExistente = $mysql->getValue('count(id) as total', 'total',
        'aulaspraticas',
        "id = '".$idaulapratica."'");

if ($countExistente == 0) {
    $retorno["status"] = "ok";
} else {
    
    $validado = $mysql->getValue('validado', NULL, 'aulaspraticas', "id = '".$idaulapratica."'");
    
    if ($validado == 'S') {   
        $retorno["status"] = "erro";
        $retorno["msg"] = "Impossível excluir. Aula validada.";
    } else {
        $deleted = $mysql->delete('aulaspraticas', "id = '".$idaulapratica."'");
        if ($deleted) {
            $retorno["status"] = "ok";
        } else {
            $retorno["status"] = "erro";
            $retorno["msg"] = "Erro ao excluir. SQL: ".$mysql->getMsgErro();
        }
    }
}

echo json_encode($retorno);

?>