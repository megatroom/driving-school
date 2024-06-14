<?php
ob_start(); session_start(); ob_end_clean();
include_once("../../configuracao.php");

$idaluno = $_GET["idaluno"];
$tipoRel = $_GET["tiporel"];
$opcoes = explode(",", $_GET["opcoes"]);
$exibir = $_GET["exibir"];

$args = "";
foreach ($_GET as $key => $value) {
    $val = $value;
    if ($key == "tiporel") {
        $val = "2";
    }
    if ($args == "") {
        $args = "?".$key."=".$val;
    } else {
        $args .= "&".$key."=".$val;
    }
}

$rel = new modulos_global_relatorio($tipoRel, "Relatório de Aulas do Aluno", $_SERVER["PHP_SELF"] . $args);

$mysql = new modulos_global_mysql();

/*
 * Cabeçalho - Início
 */
$cbcRel = $mysql->select(
        "a.campo, a.valor",
        "sistema a",
        "a.campo = 'reltitulo' or a.campo = 'reldesc'");

$titulo = "";
$cabecalho = "";
if (is_array($cbcRel)) {
    foreach ($cbcRel as $cabecalhoRel) {
        if ($cabecalhoRel["campo"] == "reltitulo") {
            $titulo = $cabecalhoRel["valor"];
        }
        if ($cabecalhoRel["campo"] == "reldesc") {
            $cabecalho = $cabecalhoRel["valor"];
            $cabecalho = str_replace("\n",'<br />', $cabecalho);
        }
    }
}

$rel->openTable('tblCabecalho', $rel->attrCabTable());
$rel->newLine();
$rel->newCel($titulo, $rel->attrCabTitulo());
$rel->closeLine();
$rel->newLine();
$rel->newCel($cabecalho, $rel->attrCabDesc());
$rel->closeLine();
$rel->closeTable();

$rel->hr();
/*
 * Cabeçalho - Fim
 */

$attrTable = null;
$attrTable["border"] = "1";
$attrTable["cellpadding"] = "0px";
$attrTable["style"] = "font-size:8pt;";

$nome = $mysql->getValue('nome', NULL, 'valunos', "id = '".$idaluno."'");

$rel->h2('<strong>Aluno:</strong> '.$nome);

if (in_array("1", $opcoes)) {
    
    $rel->null('<p><strong>Atendente:</strong> '. $_SESSION["USUARIO_NOME"] .'</p>');

    $rel->openTable('tblAlunoAulasPraticas', $attrTable);
    $rel->newLine();
    $rel->newCelHeader('Data');
    $rel->newCelHeader('Semana');
    $rel->newCelHeader('Hora');
    $rel->newCelHeader('Carro');
    $rel->newCelHeader('Instrutor');
    $rel->newCelHeader('Celular');
    $rel->newCelHeader('Comentário');
    $rel->closeLine();

    $whereExibir = "";
    if ($exibir == 1) {
        $whereExibir = "and v.data >= curdate() ";
    }

    $rowAlunoAulasPraticas = $mysql->select(
            'v.data, v.hora, v.comentario, v.carro, v.aluno, v.idcarro',
            "vaulaspraticas v",
            "idaluno = '".$idaluno."' ".
            $whereExibir.
            "and (v.falta = 'N' or v.falta is null or v.abono = 'S')",
            null,
            "data, hora");
    if (is_array($rowAlunoAulasPraticas)) {
        foreach ($rowAlunoAulasPraticas as $row) {
            $instrutor = $mysql->select(
                "b.nome, b.celular",
                'carrofuncionario a, vfuncionarios b',
                "a.idfuncionario = b.id ".
                "and a.idcarro = '".$row["idcarro"]."' ".
                    "and TIMESTAMP(a.data, a.hora) = ( ".
                    "SELECT max(TIMESTAMP(x.data, x.hora)) ".
                    "FROM carrofuncionario x ".
                    "where TIMESTAMP(x.data, x.hora) <= '".$row["data"]." ".$row["hora"]."' ".
                    " and x.idcarro = '".$row["idcarro"]."')");

            $rel->newLine();
            $rel->newCel(db_to_date($row["data"]), array("style"=>"text-align:center"));
            $rel->newCel(db_to_week($row["data"]), array("style"=>"text-align:center"));
            $rel->newCel(db_to_hour($row["hora"]), array("style"=>"text-align:center"));
            $rel->newCel($row["carro"]);
            $rel->newCel($instrutor[0]["nome"]);
            $rel->newCel($instrutor[0]["celular"]);
            $rel->newCel($row["comentario"]);
            $rel->closeLine();
        }
    } else {
        $rel->newLine();
        $rel->newCel('Não há nenhuma aula lançada.', array("colspan"=>"5"));
        $rel->close();
    }

    $rel->closeTable();
    $rel->divClear("10px");

    $rowsConteudo = $mysql->select(
            'a.texto',
            'relalunos a',
            "a.tipo = 1",
            null,
            'a.id');
    if (is_array($rowsConteudo)) {
        foreach ($rowsConteudo as $row) {
            $texto = $row["texto"];
            $texto = formatarVariaveis($texto, $idaluno);
            $rel->null($texto);
        }
        $rel->divClear("10px");
    }
}

$attrTable = null;
$attrTable["cellpadding"] = "5px";
$attrTable["style"] = "font-size:8pt;";

if (in_array("2", $opcoes)) {

    $rowsAlunoAulasTeoricas = $mysql->select(
            "v.data, v.hora, v.funcionario, v.sala",
            "vaulasteoricas v",
            "idaluno = '".$idaluno."'",
            null,
            "data, hora");
    if (is_array($rowsAlunoAulasTeoricas)) {
        foreach ($rowsAlunoAulasTeoricas as $row) {
            $rel->openTable('tblAulaTeoricaAluno', $attrTable);
            $rel->newLine();
            $rel->newCel('Data:', $rel->attrDadosLabel());
            $rel->newCel(db_to_date($row["data"]));
            $rel->newCel('Hora:', $rel->attrDadosLabel());
            $rel->newCel($row["hora"]);
            $rel->newCel('Sala:', $rel->attrDadosLabel());
            $rel->newCel($row["sala"]);
            $rel->closeLine();
            $rel->newLine();
            $rel->newCel('Professor:', $rel->attrDadosLabel());
            $rel->newCel($row["funcionario"], array("colspan"=>"5"));
            $rel->closeTable();
            $rel->divClear("10px");
        }
    }

    $rowsConteudo = $mysql->select(
            'a.texto',
            'relalunos a',
            "a.tipo = 2",
            null,
            'a.id');
    if (is_array($rowsConteudo)) {
        foreach ($rowsConteudo as $row) {
            $texto = $row["texto"];
            $texto = formatarVariaveis($texto, $idaluno);
            $rel->null($texto);
        }
        $rel->divClear("10px");
    }
}

$rel->close();

function formatarVariaveis($txt, $idAluno) {
    $conteudo = $txt;
    $conteudo = str_replace("{dataAtual}", date('d/m/Y'), $conteudo);
    return $conteudo;
}

?>