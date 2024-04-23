<?php
include_once("../../configuracao.php");

$idturno = $_POST["idturno"];

$mysql = new modulos_global_mysql();

$idtipocarro["idtipocarro"] = $mysql->getValue('idtipocarro', null, 'turnos', 'id = '.$idturno);

echo json_encode($idtipocarro);

?>