<?php
include_once("../../configuracao.php");

$pId = $_POST["id"];

$mysql = new modulos_global_mysql();

$diasavencer = $mysql->getValue('diasavencer', null, "tiposervicos", "id = '".$pId."'");

if (is_numeric($diasavencer) and $diasavencer > 0) {
    $vencimento = strftime("%d/%m/%Y", strtotime("+ ".$diasavencer." day"));
} else {
    $vencimento = "";
}

$resultado["vencimento"] = $vencimento;

echo json_encode($resultado);

?>