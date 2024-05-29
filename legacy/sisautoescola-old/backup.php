<?php
include_once("configuracao.php");

exit;
// ==================================================================
// ======= BLOQUIEI O BACKUP - Para usar o Mysql Amdinistrator ======
// ==================================================================

if (!extension_loaded('zip')) {
    echo "Erro: Nao esta habilitado php_zip.dll, edite seu php.ini";
    exit;
}

$mysql = new modulos_global_mysql();

$validacao = false;
$arquivoSQL = "";
$arquivoZIP = "";
$nomeArquivoSQL = 'sisautoescola' . date('Ymd') .'.sql';
$nomeArquivoZIP = 'sisautoescola' . date('Ymd') .'.zip';

$backupDir = $mysql->getValue("valor", null, "sistema", "campo = 'backupdir'");

if (strlen($backupDir) > 1) {
    if (is_dir($backupDir)) {
        $validacao = true;
    }
}

if ($validacao) {
    $arquivoSQL = $backupDir . '/' . $nomeArquivoSQL;
    $arquivoZIP = $backupDir . '/' . $nomeArquivoZIP;
    if (file_exists($arquivoZIP)) {
        $validacao = false;
    }
}

if ($validacao) {

    $dbconfig = new dbconfig();

    $db_database = $dbconfig->getDatabase();

    $openFile = fopen($arquivoSQL,"w") or die("O arquivo não pode ser criado!");

    $rows = $mysql->showTables();

    if ($rows) {
            while ($row = mysql_fetch_row($rows)) {
                if ($row[1] != 'VIEW') {
                    $validacao = true;

                    fwrite($openFile,"/* \n");
                    fwrite($openFile,"#tabela $row[0] \n");
                    fwrite($openFile,"*/ \n");

                    $sqlSelect = $mysql->showCreateTable($row[0]);
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
                            $sqlSelect = $mysql->describe($row[0]);
                            while ($field = mysql_fetch_row($sqlSelect)){
                                    $fields[] = $field[0];
                            }

                            $sqlSelect = $mysql->getQuery("select * from ".$row[0]);
                            while ($tabelas = mysql_fetch_row($sqlSelect)){
                                    $sql  = "insert into ".$row[0]." (";
                                    $sql .= implode(",",$fields);
                                    $sql .= ") values ('";
                                    $sql .= implode("','",$tabelas);
                                    $sql = str_replace(';', ',', $sql); /* Remove os ; */
                                    $sql .= "');\n";
                                    $sql = str_replace("''", "null", $sql);
                                    fwrite($openFile,$sql);
                            }
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
}

?>