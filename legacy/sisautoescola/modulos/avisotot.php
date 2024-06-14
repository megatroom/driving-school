<?php
session_start();
include_once("../configuracao.php");

$mysql = new modulos_global_mysql();
$total = $mysql->getValue(
        'count(id) as total',
        'total',
        'avisos',
        "iddestinatario = '".$_SESSION["IDUSUARIO"]."' and status = 'A'");
if ($total == 0) {
    echo '';
} else if ($total == 1) {
    echo "Você possui $total aviso";
} else {
    echo "Você possui $total avisos";
}

?>