<?php
include_once("../../configuracao.php");

$idaulapratica = $_POST["idaulapratica"];
$comentario = $_POST["comentario"];

$mysql = new modulos_global_mysql();

$pFields = null;
$pFields["comentario"] = "'".$comentario."'";

if ($mysql->save($idaulapratica, 'aulaspraticas', $pFields, "id = '".$idaulapratica."'")) {

} else {
    echo $mysql->getMsgErro();
}

?>