<?php
include_once("../../configuracao.php");

$pTelas = $_POST["telas"];

$msgValidacao = null;

$mysql = new modulos_global_mysql();

$lstTelas = explode("|", $pTelas);
$ordem = 1;
$lastModulo = "";
foreach ($lstTelas as $tela) {
    $valor = explode(",", $tela);
    $idmodulo = $valor[0];
    $idtela = $valor[1];
    if ($lastModulo == $idmodulo) {
        $ordem++;
    } else {
        $ordem = 1;
        $lastModulo = $idmodulo;
    }
    $pFields["ordem"] = $ordem;
    $id = $mysql->save($idtela, "telas", $pFields, "id = '".$idtela."'");
    if (!$id) {
        $msgValidacao["retornoStatus"][] = "erro";
        $msgValidacao["titulo"][] = "Ocorreu um erro no banco de dados.";
        $msgValidacao["msg"][] = "Mensagem técnica: ". $mysql->getMsgErro();
        echo json_encode($msgValidacao);
        exit;
    }
}

$msgValidacao["retornoStatus"][] = "save";

echo json_encode($msgValidacao);

?>