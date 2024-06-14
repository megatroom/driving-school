<?php
include_once("../../configuracao.php");

$arquivo = $_GET["arquivo"];
$errPrimaryKey = $_GET["key"];
$errValoresNull = $_GET["vnull"];

$mysql = new modulos_global_mysql();

$diretorio = $mysql->getValue("valor", null, "sistema", "campo = 'backupdir'") . "/";

$arquivo = str_replace(".zip",".sql",$arquivo);

$f = fopen($diretorio.$arquivo,"r+");
$sqlFile = fread($f, filesize($diretorio.$arquivo));

$mysql->_CONNECT();

$sqlArray = explode(';',$sqlFile);
foreach ($sqlArray as $stmt) {
	if (strlen($stmt) > 3 && substr(ltrim($stmt),0,2) != '/*' && substr(ltrim($stmt),0,1) != '#') {
		$result = $mysql->getQuery($stmt, FALSE);
		if (!$result) {
			if (!(mysql_errno() == '1062' and $errPrimaryKey == "S") &&
                            !(mysql_errno() == '1048' and $errValoresNull == "S")) {
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

$mysql->_DESCONNECT();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <body>
        <table width="100%" cellspacing="20" style="font-size:10pt;">
                <tr>
                        <td align="center">Restauração do Banco de Dados efetuada com sucesso!</td>
                </tr>
        </table>
    </body>
</html>