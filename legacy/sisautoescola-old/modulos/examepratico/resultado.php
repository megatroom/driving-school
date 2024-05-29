<?php
include_once("../../configuracao.php");

$idepaluno = $_POST["idepaluno"];
$resultado = $_POST["resultado"];

$validacao = true;
$msg = "";
if (!is_numeric($idepaluno) and $idepaluno <= 0) {
    $validacao = false;
    $msg = "Id inválido!";
}
if ($resultado != "A" and
        $resultado != "R" and
        $resultado != "N" and
        $resultado != "M" and
        $resultado != "F" and
        $resultado != "T" and
        $resultado != "C") {
    $validacao = false;
    $msg = "Resultado inválido!";
}

$msgValidacao = null;

$mysql = new modulos_global_mysql();

if ($validacao) {
    $pFields = null;
    $pFields["resultado"] = "'".$resultado."'";
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