<?php
include_once("../../configuracao.php");

$data = $_POST["data"];
$hora = $_POST["hora"];
$idaluno = $_POST["idaluno"];
$idcarro = $_POST["idcarro"];

$retorno = null;
$pFields = null;

$validacao = true;

$idaulapratica = 0;

if (!isset ($idaluno) or strlen($idaluno) == 0) {
    $retorno["status"] = "erro";
    $retorno["msg"] = "Escolha um aluno.";
    echo json_encode($retorno);
    exit;
}

$mysql = new modulos_global_mysql();

$countExistente = $mysql->getValue('count(id) as total', 'total', 
        'aulaspraticas',
        "data = '".date_to_db($data)."' and hora = '".$hora."' and idaluno = '".$idaluno."'");

if ($countExistente > 0) {
    $countExistente = $mysql->getValue('count(id) as total', 'total',
        'aulaspraticas',
        "data = '".date_to_db($data)."' and hora = '".$hora."' and idcarro = '".$idcarro."' and idaluno = '".$idaluno."'");

    if ($countExistente > 0) {
        $idaulapratica = $mysql->getValue('id', null, 'aulaspraticas',
            "data = '".date_to_db($data)."' and hora = '".$hora."' and idcarro = '".$idcarro."' and idaluno = '".$idaluno."'");
    } else {
        $retorno["status"] = "erro";
        $retorno["msg"] = "Esse aluno já está tendo aula nesse horário em outro carro.";
        $validacao = false;
    }
}

$countTotalLancada = $mysql->getValue('coalesce(sum(qtaulaspraticas), 0) as total', 'total',
        'valunoservico',
        "idaluno = '".$idaluno."'");

$countTotalFeita = $mysql->getValue('coalesce(count(id), 0) as total', 'total',
        'aulaspraticas',
        "idaluno = '".$idaluno."'");

if ($countTotalLancada <= $countTotalFeita) {
    $retorno["status"] = "erro";
    $retorno["msg"] = "É preciso lançar mais serviços para lançar mais aulas práticas para este aluno.";
    $validacao = false;
}

$nomealuno = $mysql->getValue('b.nome', 'nome', 'alunos a, pessoas b',
        "a.idpessoa = b.id and a.id = '".$idaluno."'");

$countExistente = $mysql->getValue('count(id) as total', 'total',
        'aulaspraticas',
        "data = '".date_to_db($data)."' and hora = '".$hora."' and idcarro = '".$idcarro."' and idaluno = '".$idaluno."'");

if ($countExistente > 0) {
    $retorno["status"] = "ok";
    $retorno["idaulapratica"] = $idaulapratica;
    $retorno["msg"] = '<a href="#" id="btnExcAlunoAulaPratica" style="color:blue;">'. $nomealuno .'<input type="hidden" value="'. $idaulapratica .'" /></a>';
} else {
    if ($validacao) {

        $pFields["idaluno"] = "'".$idaluno."'";
        $pFields["idcarro"] = "'".$idcarro."'";
        $pFields["data"] = "'".date_to_db($data)."'";
        $pFields["hora"] = "'".$hora."'";
        $pFields["falta"] = "'N'";
        $pFields["abono"] = "'N'";

        $idaulapratica = $mysql->save(0, 'aulaspraticas', $pFields);

        if ($idaulapratica) {
            $retorno["status"] = "ok";
            $retorno["idaulapratica"] = $idaulapratica;
            $retorno["msg"] = '<a href="#" id="btnExcAlunoAulaPratica" style="color:blue;">'. $nomealuno .'<input type="hidden" value="'. $idaulapratica .'" /></a>';
        } else {
            $retorno["status"] = "erro";
            $retorno["msg"] = "Erro ao gravar data. SQL: ".$mysql->getMsgErro();
        }
    }
}

echo json_encode($retorno);

?>
