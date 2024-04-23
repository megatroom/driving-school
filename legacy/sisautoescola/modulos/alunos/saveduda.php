<?php
include_once("../../configuracao.php");

$idaluno = $_POST["idaluno"];
$duda = $_POST["duda"];

$validacao = true;

if ($duda == "") {
    $msgValidacao["msg"] = "O campo duda deve ser preenchido!";
    $validacao = false;
}

$fields = null;
$fields["idaluno"] = $idaluno;
$fields["duda"] = "'".$duda."'";
$fields["data"] = "CURDATE()";

$mysql = new modulos_global_mysql();

if ($validacao) {
    $id = $mysql->save(0, 'alunosdudas', $fields);
    if ($id) {
        $msgValidacao["retornoStatus"][] = "save";
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