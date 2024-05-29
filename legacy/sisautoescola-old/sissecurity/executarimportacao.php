<?php
ini_set('max_execution_time','99999');
include_once('config.php');

$origemDB = $_GET["origemDB"];
$destinoDB = $_GET["destinoDB"];
?>
<div style="font-family: Courier New;font-size: 14px;">
<?php
$db_connection = mysql_connect($db_host,$db_user,$db_password,$db_database) or die ("Erro de Conexão:".mysql_error());

$lstAlunosCodigo1 = null;
$lstAlunosCodigo2 = null;

$sql = "select a.Cod_Clientes, a.Nome, b.Nome as Filial, a.Pai, a.Mae, a.Identidade, a.Identidade_Orgao, ";
$sql .= "a.Endereco, a.Numero, a.Complemento, a.Cidade, a.UF, a.Telefone, a.CPF, a.Sexo_Tipo, ";
$sql .= "a.Bairro, a.Celular, a.CEP, DATE_FORMAT(a.Nascimento_Data,'%Y-%m-%d') as Nascimento_Data,  ";
$sql .= "a.Duda, a.RENACH, a.Observacoes ";
$sql .= "from `".$origemDB."`.`clientes - pessoal` a ";
$sql .= "left join `".$origemDB."`.`filial` b on a.Filial = b.Codigo ";
$sql .= "where a.Nome is not null ";
$sql .= "and a.Nome <> '' ";

$rows = mysql_query($sql) or die ("ERRO: ".mysql_error()." SQL: ".$sql);

$matriculaAluno = 1;

while ($row = mysql_fetch_array($rows)) {

    $filial = 'null';
    if (isset($row["Filial"]) and trim($row["Filial"]) != '') {
        $sqlFilial = "select id from `".$destinoDB."`.origens ";
        $sqlFilial .= "where descricao = ".tratarString($row["Filial"])." ";
        $filiais = mysql_query($sqlFilial) or die ("ERRO: ".mysql_error()." SQL: ".$sqlFilial);
        while ($rowFilial = mysql_fetch_array($filiais)) {
            $filial = $rowFilial["id"];
        }
        mysql_free_result($filiais);
        if ($filial == 'null') {
            $sqlInsertOrigem = "insert into `".$destinoDB."`.origens ";
            $sqlInsertOrigem .= "(descricao) values (".tratarString($row["Filial"]).") ";
            mysql_query($sqlInsertOrigem) or die ("ERRO: ".mysql_error()." SQL: ".$sqlInsertOrigem);
        }
        $filiais = mysql_query($sqlFilial) or die ("ERRO: ".mysql_error()." SQL: ".$sqlFilial);
        if ($filiais) {
            while ($rowFilial2 = mysql_fetch_array($filiais)) {
                $filial = $rowFilial2["id"];
            }
        }
        mysql_free_result($filiais);
    }

    $idpessoa = 0;
    $txt = '';
    $txt .= "insert into `".$destinoDB."`.pessoas ";
    $txt .= '(nome, pai, mae, rg, orgaoemissor, endereco, bairro, cidade, ';
    $txt .= 'estado, telefone, celular, cep, sexo, dtnascimento, cpf)';
    $txt .= ' values ';
    $txt .= "(".tratarString($row["Nome"]).", ";    
    $txt .= tratarString($row["Pai"]).", ";
    $txt .= tratarString($row["Mae"]).", ";
    $txt .= tratarString($row["Identidade"]).", ";
    $txt .= tratarString($row["Identidade_Orgao"]).", ";
    $txt .= tratarString($row["Endereco"]." Número ".$row["Numero"]." - ".$row["Complemento"]).", ";
    $txt .= tratarString($row["Bairro"]).", ";
    $txt .= tratarString($row["Cidade"]).", ";
    $txt .= tratarString($row["UF"]).", ";
    $txt .= tratarString($row["Telefone"]).", ";
    $txt .= tratarString($row["Celular"]).", ";
    $txt .= tratarString($row["CEP"]).", ";
    $txt .= strtoupper(tratarString($row["Sexo_Tipo"])).", ";
    $txt .= tratarData($row["Nascimento_Data"]).", ";
    $txt .= tratarString($row["CPF"]).")";
    $result = incluirTupla($txt);
    if ($result) {        
        $sqlPessoa = "select max(id) as total from `".$destinoDB."`.pessoas ";
        $pessoas = mysql_query($sqlPessoa) or die ("ERRO: ".mysql_error()." SQL: ".$sqlPessoa);
        while ($rowIdPessoa = mysql_fetch_array($pessoas)) {
            $idpessoa = $rowIdPessoa["total"];
        }
    }

    $idaluno = 0;
    if ($idpessoa > 0) {
        $txt = '';
        $txt .= "insert into `".$destinoDB."`.alunos ";
        $txt .= '(idpessoa, codacess, idorigem, matricula, renach, observacoes)';
        $txt .= ' values ';
        $txt .= "(".$idpessoa.", ".$row["Cod_Clientes"].", ".$filial.", ".$matriculaAluno.", ";
        $txt .= tratarString($row["RENACH"]).", ";
        $txt .= tratarString($row["Observacoes"]).")";
        $result = incluirTupla($txt);
        $idaluno = mysql_insert_id();
        $matriculaAluno++;
        if ($result and tratarString($row["Duda"]) != "null") {
            $txt = '';
            $txt .= "insert into `".$destinoDB."`.alunosdudas ";
            $txt .= "(idaluno, duda, data) values ";
            $txt .= "(".$idaluno.", ".tratarString($row["Duda"]).", CURDATE())";
            $result = incluirTupla($txt);            
        }
    }

    if ($idaluno > 0) {
        $lstAlunosCodigo1[] = $row["Cod_Clientes"];
        $lstAlunosCodigo2[] = $idaluno;
    }
}

echo "===============================================<br/>";
echo "==== Tabela ALUNOS importada com sucesso!!! ===<br/>";
echo "===============================================<br/>";

$sql = "select a.Cod_Clientes, a.DataVencimento, a.Historico, ";
$sql .= "coalesce(a.Debito, 0) as Debito, a.Credito, a.DataPagamento, ";
$sql .= "case when coalesce(data_mov_usuario, now()) < coalesce(datapagamento, now()) then data_mov_usuario else datapagamento end as datacad ";
$sql .= "from `".$origemDB."`.`clientes - financeiro` a ";

$rows = mysql_query($sql) or die ("ERRO: ".mysql_error()." SQL: ".$sql);

while ($row = mysql_fetch_array($rows)) {
    if (strlen(validarString($row["Historico"])) > 1) {
        $idtiposervico = validarHistoricoFin($destinoDB, $row["Historico"]);
        if ($idtiposervico == 0) {
            $sqlInsert = "insert into `".$destinoDB."`.tiposervicos ";
            $sqlInsert .= "(descricao, qtaulaspraticas, qtaulasteoricas, valor, status, diasavencer) values ";
            $sqlInsert .= "(".tratarString($row["Historico"]).", ";
            $sqlInsert .= "30, 1, ";
            $sqlInsert .= "'".$row["Credito"]."', ";
            $sqlInsert .= "'I', 30) ";
            incluirTupla($sqlInsert);
            $idtiposervico = mysql_insert_id();
        }
        $idaluno = localizarCliente($lstAlunosCodigo1, $lstAlunosCodigo2, $row["Cod_Clientes"]);
        if ($idtiposervico > 0 && $idaluno > 0) {
            $sqlTipoServ  = "insert into `".$destinoDB."`.alunoservico ";
            $sqlTipoServ .= "(idtiposervico, idaluno, data, qtaulaspraticas, qtaulasteoricas, valor, desconto, vencimento) values ";
            $sqlTipoServ .= "(".$idtiposervico.", ";
            $sqlTipoServ .= "'".$idaluno."', ";
            $sqlTipoServ .= "'".$row["DataPagamento"]."', ";
            $sqlTipoServ .= "30, 1, ";
            $sqlTipoServ .= "'".$row["Credito"]."', ";
            $sqlTipoServ .= "0, ";
            $sqlTipoServ .= "'".$row["datacad"]."') ";
            incluirTupla($sqlTipoServ);

            if ($row["Debito"] > 0) {
                $idalunoservico = mysql_insert_id();
                $sqlContaReceber  = "insert into `".$destinoDB."`.contasareceber ";
                $sqlContaReceber .= "(idalunoservico, idusuario, valor, data) values ";
                $sqlContaReceber .= "(".$idalunoservico.", ";
                $sqlContaReceber .= "null, ";
                $sqlContaReceber .= "'".$row["Debito"]."', ";
                $sqlContaReceber .= "'".$row["DataPagamento"]."') ";
                incluirTupla($sqlContaReceber);
            }
        }
    }
}

echo "===================================================<br/>";
echo "==== Tabela FINANCEIRO importada com sucesso!!! ===<br/>";
echo "===================================================<br/>";

function localizarCliente($lista1, $lista2, $codigo) {
    $total = array_search($codigo, $lista1);
    $valor = $lista2[$total];
    if (is_numeric($valor))
    {
        return $valor;
    }
    else
    {
        return 0;
    }
}
function validarHistoricoFin($db, $historico){
    $total = 0;
    $resultado = mysql_query("select id from `".$db."`.tiposervicos where descricao = '".validarString($historico)."' ");
    if ($resultado) {
        while ($row = mysql_fetch_array($resultado)) {
            $total = $row["id"];
        }
    }
    return $total;
}
function validarString($txt) {
    $resultado = $txt;
    $resultado = str_replace("'", "", $txt);
    $resultado = str_replace("\\", "", $resultado);
    $resultado = str_replace(";", ",", $resultado);
    $resultado = str_replace("&", "e", $resultado);
    $resultado = trim($resultado);
    return $resultado;
}
function tratarString($txt) {
    if ($txt == "") {
        $resultado = "null";
    } else {
        $resultado = "'".validarString($txt)."'";
    }
    return $resultado;
}
function tratarData($txt) {
    if ($txt == "0000-00-00" or $txt == "") {
        $resultado = "null";
    } else {
        $resultado = "'".$txt."'";
    }
    return $resultado;
}
function incluirTupla($sql) {
    $resultado = mysql_query($sql);
    if (!$resultado) {
        $sqlErrorCode = mysql_errno();
	$sqlErrorText = mysql_error();
        echo "<h2>Ocorreu um erro ao incluir um registro!</h2><br/>";
        echo "Error code: $sqlErrorCode<br/>";
        echo "Error text: $sqlErrorText<br/>";
        echo "Statement: $sql<br/>";
        echo "===============================================<br/>";
        exit;
    }
    return $resultado;
}
?>
</div>