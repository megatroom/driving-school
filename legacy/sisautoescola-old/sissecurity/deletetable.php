<?php
include_once('connection.php');

$table = $_GET["table"];

$result = mysql_query("DROP TABLE ".$table);
if (!$result) {
	$sqlErrorCode = mysql_errno();
	$sqlErrorText = mysql_error();
	break;
}

if (!($sqlErrorCode == 0)) {
  echo "<h2>Ocorreu um erro ao tentar excluir a tabela ".$table."!</h2><br/>";
  echo "Error code: $sqlErrorCode<br/>";
  echo "Error text: $sqlErrorText<br/>";
  echo "Statement: $sqlStmt<br/>";
  exit;
}
	
?>
<table width="100%" cellspacing="20" style="font-size:10pt;">
	<tr>
		<td align="center">A tabela <?php echo $table; ?> foi excluída com sucesso!</td>
	</tr>
</table>