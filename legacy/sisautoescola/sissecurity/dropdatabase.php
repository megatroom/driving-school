<?php
include_once('connection.php');

$database = $_GET["database"];

$result = mysql_query("DROP DATABASE ".$database);
if (!$result) {
	$sqlErrorCode = mysql_errno();
	$sqlErrorText = mysql_error();
	$sqlStmt = $stmt;
	break;
}

if (!($sqlErrorCode == 0)) {
  echo "<h2>Ocorreu um erro ao tentar excluir o banco de dados!</h2><br/>";
  echo "Error code: $sqlErrorCode<br/>";
  echo "Error text: $sqlErrorText<br/>";
  echo "Statement: $sqlStmt<br/>";
  exit;
}
	
?>
<table width="100%" cellspacing="20" style="font-size:10pt;">
	<tr>
		<td align="center">Banco de Dados excluído com sucesso!</td>
	</tr>
</table>