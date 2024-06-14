<?php
include_once("../../configuracao.php");

$id = $_POST["idtela"];
$icone = $_POST["icone"];

$mysql = new modulos_global_mysql();

$fields = null;
if ($icone == "") {
    $fields["icone"] = "null";
} else {
    $fields["icone"] = "'". str_replace("icones/", "", $icone) ."'";
}

$id = $mysql->save($id, 'telas', $fields, "id = '".$id."'");
if ($id) {
	$msgValidacao["retornoStatus"][] = "save";
} else {
	$msgValidacao["retornoStatus"][] = "erro";
	$msgValidacao["titulo"][] = "Ocorreu um erro no banco de dados.";
	$msgValidacao["msg"][] = "Mensagem técnica: ". $mysql->getMsgErro();
}

echo json_encode($msgValidacao);

?>