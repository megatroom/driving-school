<?php
include_once('connection.php');

$arquivo = $_GET["arquivo"];
$errPrimaryKey = $_GET["key"];

$diretorio = "backup/";

$arquivo = str_replace(".zip",".sql",$arquivo);

$f = fopen($diretorio.$arquivo,"r+");
$sqlFile = fread($f, filesize($diretorio.$arquivo));
$sqlArray = explode(';',$sqlFile);
foreach ($sqlArray as $stmt) {
	if (strlen($stmt) > 3 && substr(ltrim($stmt),0,2) != '/*' && substr(ltrim($stmt),0,1) != '#') {
		$result = mysql_query($stmt);
		if (!$result) {
			if (!(mysql_errno() == '1062' and $errPrimaryKey == "S")) {
				$sqlErrorCode = mysql_errno();
				$sqlErrorText = mysql_error();
				$sqlStmt = $stmt;
				break;
			}
		}
	}
}
if (!($sqlErrorCode == 0)) {
  echo "<h2>Ocorreu um erro ao tentar recuperar o banco de dados!</h2><br/>";
  echo "Error code: $sqlErrorCode<br/>";
  echo "Error text: $sqlErrorText<br/>";
  echo "Statement: $sqlStmt<br/>";
  exit;
}
	
?>
<table width="100%" cellspacing="20" style="font-size:10pt;">
	<tr>
		<td align="center">Restauração do Banco de Dados efetuada com sucesso!</td>
	</tr>
</table>