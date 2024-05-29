<?php
include_once("../../configuracao.php");

$id = $_POST["id"];

$validacao = true;
$msgValidacao = "";

$mysql = new modulos_global_mysql();

if (!isset ($id) or !is_numeric($id) or $id < 1) {
    $msgValidacao["msg"] = "O código não foi informado.";
    $validacao = false;
}

if ($validacao) {
    if ($mysql->delete('alunoservicoparcelas', "id = '".$id."'")) {
        $msgValidacao["retornoStatus"] = "delete";
    } else {
        $msgValidacao["retornoStatus"] = "erro";
        $msgValidacao["titulo"] = "Ocorreu um erro no banco de dados.";
        $msgValidacao["msg"] = "Mensagem técnica: ". $mysql->getMsgErro();
    }
} else {
    $msgValidacao["retornoStatus"] = "validacao";
    $msgValidacao["titulo"] = "Aviso.";
}

echo json_encode($msgValidacao);

?>