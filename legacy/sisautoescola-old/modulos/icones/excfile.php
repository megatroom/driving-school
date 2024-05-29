<?php
include_once("../../configuracao.php");

$icone = $_POST["icone"];

$icone = substr($icone, strrpos($icone, "/") + 1);

unlink(str_replace("modulos\\", "", getcwd()).'\\'.$icone);

?>