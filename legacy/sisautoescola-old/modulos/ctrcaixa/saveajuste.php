<?php
include_once("../../configuracao.php");

$pId = $_POST["id"];
$pAjuste = $_POST["ajuste"];

$mysql = new modulos_global_mysql();

$pFields = null;
$pFields["ajuste"] = float_to_db($pAjuste);
$id = $mysql->save($pId, 'caixa', $pFields, "id = '".$pId."'");

if ($id) {
    $msgValidacao["retornoStatus"][] = "save";
} else {
    $msgValidacao["retornoStatus"][] = "erro";
    $msgValidacao["titulo"][] = "Ocorreu um erro no banco de dados.";
    $msgValidacao["msg"][] = "Mensagem técnica: ". $mysql->getMsgErro();
}

echo json_encode($msgValidacao);

?>