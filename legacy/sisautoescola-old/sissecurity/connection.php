<?php
include_once('config.php');

$db_connection = mysql_connect($db_host,$db_user,$db_password,$db_database) or die ("Erro de Conexão:".mysql_error());

mysql_select_db($db_database,$db_connection);

?>