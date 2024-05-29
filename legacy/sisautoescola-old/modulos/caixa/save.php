<?php
ob_start(); session_start(); ob_end_clean();
include_once("../../configuracao.php");

$id     = 0;
$valor  = $_POST["valor"];

$idusuario = $_SESSION["IDUSUARIO"];

$validacao = true;
$msgValidacao = "";
$tmp_value = "";

$fields = null;

$mysql = new modulos_global_mysql();

$countexistente = $mysql->getValue(
                        'count(id) as total',
                        'total',
                        'caixa',
                        "idusuario = '".$idusuario."' and data = CURDATE()");
if ($countexistente > 0) {
    $msgValidacao["msg"][] = "Caixa já fechado.";
    $validacao = false;
}

$fields["idusuario"] = $idusuario;
$fields["valor"] = float_to_db($valor);
$fields["data"] = "CURDATE()";
$fields["hora"] = "CURTIME()";

if ($validacao) {
    $id = $mysql->save($id, 'caixa', $fields, "id = '".$id."'");
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