<?php
session_start();

$host = $_POST["host"];
$user = $_POST["user"];
$pwd  = $_POST["pwd"];
$database = $_POST["database"];
$criar = $_POST["criar"];

$validacao = true;

if (!isset($host) or $host == "") {
	echo "Campo 'Host' de preenchimento obrigatório! <br />";
	$validacao = false;
}
if (!isset($user) or $user == "") {
	echo "Campo 'Usuário' de preenchimento obrigatório! <br />";
	$validacao = false;
}
if (!isset($database) or $database == "") {
	echo "Campo 'Database' de preenchimento obrigatório! <br />";
	$validacao = false;
}

$isDatabaseSelected = false;
if ($validacao) {
	$con = mysql_connect($host, $user, $pwd);
	
	if ($con) {
		$db_selected = mysql_select_db($database, $con);
		
		if ($db_selected) {
			$isDatabaseSelected = true;
		} else {
			if ($criar == "S") {
				mysql_query('CREATE DATABASE '.$database, $con);
				$db_selected = mysql_select_db($database, $con);
				$isDatabaseSelected = true;
			} else {
				echo "Database não encontrado!";
				exit;
			}
		}
		
		if ($isDatabaseSelected) {
			$sql_create  = 'CREATE TABLE IF NOT EXISTS sistema (';
			$sql_create .= 'id int(10) not null auto_increment,';
			$sql_create .= 'campo varchar(30) NOT NULL, ';
			$sql_create .= 'valor varchar(255) NOT NULL, ';
			$sql_create .= "primary key (id))";
			mysql_query($sql_create , $con);
		}
	}
}

if ($validacao) {
	$filename = "config.ini";
	
	if (file_exists($filename)) {
		unlink ($filename);
	}
	
	$file = fopen($filename, 'a');
	fwrite($file, '[db]'.chr(10));
	fwrite($file, 'host='.$host.chr(10));
	fwrite($file, 'user='.$user.chr(10));
	fwrite($file, 'pwd='.base64_encode($pwd).chr(10));
	fwrite($file, 'database='.$database.chr(10));
	fclose($file);		
	
	echo "ok";
}

?>