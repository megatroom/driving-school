<?php
include_once("../../configuracao.php");

$id = $_POST["id"];

$validacao = true;
$msgValidacao = "";

$mysql = new modulos_global_mysql();

if (!isset ($id) or !is_numeric($id) or $id < 1) {
    $msgValidacao["msg"][] = "O código não foi informado.";
    $validacao = false;
}

$idaluno = $mysql->getValue('idaluno', null, 'alunoservico', "id = '".$id."'");

$total = $mysql->getValue('count(*) as total', 'total', 'aulaspraticas', "idaluno = '".$idaluno."'");

$totServ = $mysql->getValue('coalesce(sum(qtaulaspraticas),0) as total', 'total', 'alunoservico', "idaluno = '".$idaluno."' and id != '".$id."'");

if ($total > $totServ) {
    $msgValidacao["msg"][] = "Não é possível excluir o serviço pois as aulas práticas foram lançadas para este serviço.";
    $validacao = false;
}

if ($validacao) {
    if (!$mysql->delete('contasareceber', "idalunoservico = '".$id."'")) {
        $msgValidacao["retornoStatus"][] = "erro";
        $msgValidacao["titulo"][] = "Ocorreu um erro no banco de dados.";
        $msgValidacao["msg"][] = "Mensagem técnica: ". $mysql->getMsgErro();
        echo json_encode($msgValidacao);
        exit;
    }
}

if ($validacao) {
    if ($mysql->delete('alunoservico', "id = '".$id."'")) {
        $msgValidacao["retornoStatus"][] = "delete";
    } else {
        $msgValidacao["retornoStatus"][] = "erro";
        $msgValidacao["titulo"][] = "Ocorreu um erro no banco de dados.";
        $msgValidacao["msg"][] = "Mensagem técnica: ". $mysql->getMsgErro();
        echo json_encode($msgValidacao);
        exit;
    }
} else {
    $msgValidacao["retornoStatus"][] = "validacao";
    $msgValidacao["titulo"][] = "Aviso.";
    echo json_encode($msgValidacao);
    exit;
}

echo json_encode($msgValidacao);

?>