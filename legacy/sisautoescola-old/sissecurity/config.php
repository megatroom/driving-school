<?php
include_once('../dbconfig.php');

$dbconfiguration = new dbconfig();

/* Vari�veis de conex�o com o banco de dados */
$db_host = $dbconfiguration->getHost();
$db_user = $dbconfiguration->getUser();
$db_password = $dbconfiguration->getPassword();
$db_database = $dbconfiguration->getDatabase();

?>