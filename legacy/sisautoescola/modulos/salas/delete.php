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

$countUso = $mysql->getValue('count(id) as total', 'total', 'turmas', "idsala = '".$id."'");

if ($countUso > 0) {
    $msgValidacao["msg"][] = "Esta sala já foi usada em uma turma e não pode ser excluída.";
    $validacao = false;
}

if ($validacao) {
    if ($mysql->delete('salas', "id = '".$id."'")) {
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