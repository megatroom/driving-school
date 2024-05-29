<?php
include_once("../../configuracao.php");

$idepaluno = $_POST["idepaluno"];
$horario = $_POST["horario"];

$validacao = true;
$msg = "";
if (!is_numeric($idepaluno) and $idepaluno <= 0) {
    $validacao = false;
    $msg = "Id inválido!";
}

$msgValidacao = null;

$mysql = new modulos_global_mysql();

if ($validacao) {
    $pFields = null;
    $pFields["horario"] = "'".$horario."'";
    $id = $mysql->save($idepaluno, 'examepraticoalunos', $pFields, "id = '".$idepaluno."'");
    if ($id) {
        $msgValidacao["retornoStatus"][] = "save";
    } else {
        $msgValidacao["retornoStatus"][] = "erro";
        $msgValidacao["titulo"][] = "Ocorreu um erro no banco de dados.";
        $msgValidacao["msg"][] = "Mensagem técnica: ". $mysql->getMsgErro();
    }
} else {
    $msgValidacao["retornoStatus"][] = "validacao";
    $msgValidacao["titulo"][] = "Atenção!";
    $msgValidacao["msg"][] = $msg;
}

echo json_encode($msgValidacao);

?>