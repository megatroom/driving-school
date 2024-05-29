<?php
include_once("../../configuracao.php");

$id = $_POST["idaulapratica"];

$validacao = true;
$msgValidacao = "";

$mysql = new modulos_global_mysql();

if (!isset ($id) or $id == "") {
    $msgValidacao["msg"][] = "O código não foi informado.";
    $validacao = false;
}

$countAulasValidadas = $mysql->getValue(
        'count(*) as total', 'total', 
        'aulaspraticas', 
        "id in (".$id.") and validado='S'");

if ($countAulasValidadas > 0) {
    $msgValidacao["msg"][] = "Não é possível excluir aula validada.";
    $validacao = false;
}

if ($validacao) {
    if ($mysql->delete('aulaspraticas', "id in (".$id.")")) {
        $msgValidacao["retornoStatus"][] = "delete";
    } else {
        $msgValidacao["retornoStatus"][] = "erro";
        $msgValidacao["titulo"][] = "Ocorreu um erro no banco de dados.";
        $msgValidacao["msg"][] = "Mensagem técnica: ". $mysql->getMsgErro();
    }
} else {
    $msgValidacao["retornoStatus"][] = "validacao";
    $msgValidacao["titulo"][] = "Aviso.";
};

echo json_encode($msgValidacao);

?>