<?php
include_once('connection.php');

if (!extension_loaded('zip')) {
    echo "Erro: Nao esta habilitado php_zip.dll, edite seu php.ini";
    exit;    
}

$data = date("YmdHi");

$nomeArquivoSQL = $db_database."_".$data.".sql";
$nomeArquivoZIP = $db_database."_".$data.".zip";
$arquivoSQL = "backup/".$nomeArquivoSQL;
$arquivoZIP = "backup/".$nomeArquivoZIP;

$openFile = fopen($arquivoSQL,"w") or die("O arquivo não pode ser criado!");

$sql = "SHOW TABLES FROM $db_database";

$rows = mysql_query($sql) or die ("ERRO: ".mysql_error());

if ($rows) {
	while ($row = mysql_fetch_row($rows)) {
	
		$validacao = true;
	
		fwrite($openFile,"/* \n");
		fwrite($openFile,"#tabela $row[0] \n");
		fwrite($openFile,"*/ \n");
	
		$sqlSelect = mysql_query("show create table ".$row[0]) or die ("ERRO: ".mysql_error());
		while ($tabela = mysql_fetch_row($sqlSelect)){  
			if (strtoupper(substr($tabela[1],0,12)) == "CREATE TABLE") {
				$sql =  substr($tabela[1],0,12) ." IF NOT EXISTS". substr($tabela[1],12) .";\n"; 				
			} else {
				$validacao = false;
			}
			fwrite($openFile,$sql);
		}
	
		if ($validacao) {
			$fields = null;
			$sqlSelect = mysql_query("describe ".$row[0]) or die ("ERRO: ".mysql_error());
			while ($field = mysql_fetch_row($sqlSelect)){  
				$fields[] = $field[0]; 
			} 
		
			$sqlSelect = mysql_query("select * from ".$row[0]) or die ("ERRO: ".mysql_error());
			while ($tabelas = mysql_fetch_row($sqlSelect)){  
				$sql  = "insert into ".$row[0]." (";
				$sql .= implode(",",$fields);
				$sql .= ") values ('"; 
				$sql .= implode("','",$tabelas); 
				$sql .= "');\n"; 
				$sql = str_replace("''", "null", $sql);
				fwrite($openFile,$sql);
			}
		} 		
	}
} else {
	echo "Erro: Não foi encontrada nenhuma tabela no banco de dados.";
	exit;
}

fclose($openFile);

$zip = new ZipArchive;
if ($zip->open($arquivoZIP, ZIPARCHIVE::CREATE) === TRUE) {
    $zip->addFile($arquivoSQL, $nomeArquivoSQL);
    $zip->close();
} else {
    echo 'Erro ao abrir arquivo zip.';
	exit;
}

	
?>
<table width="100%" cellspacing="20" style="font-size:10pt;">
	<tr>
		<td align="center">Backup efetuado com sucesso!</td>
	</tr>
	<tr>
		<td align="center"><b><a href="<?php echo $arquivoZIP; ?>">Clique aqui para baixar o arquivo.</a></b></td>
	</tr>
</table>