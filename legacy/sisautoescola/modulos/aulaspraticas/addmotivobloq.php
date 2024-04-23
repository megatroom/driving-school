<?php
include_once("../../configuracao.php");

$idbloqueio = $_POST["idbloqueio"];
$motivo = $_POST["motivo"];

$mysql = new modulos_global_mysql();

$pFields = null;
$pFields["motivo"] = "'".$motivo."'";

if ($mysql->save($idbloqueio, 'aulaspraticasbloqueio', $pFields, "id = '".$idbloqueio."'")) {

} else {
    echo $mysql->getMsgErro();
}

?>