<?php
include_once("../../configuracao.php");

$mysql = new modulos_global_mysql();

$totAlunos = $mysql->getValue(
        'count(id) as total',
        'total',
        'valunos',
        "cpf = '".$_POST["cpf"]."' and id != '".$_POST["idaluno"]."'");

if ($totAlunos > 0) {
    $retorno["status"] = "no";
    $retorno["idusercpf"] = $mysql->getValue(
        'id',
        'id',
        'valunos',
        "cpf = '".$_POST["cpf"]."' and id != '".$_POST["idaluno"]."'");
} else {
    $retorno["status"] = "ok";
}

echo json_encode($retorno);

?>