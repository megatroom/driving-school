<?php
include_once("../../configuracao.php");

$idexamepratico = $_GET["idexamepratico"];
$tipoRel = $_GET["tiporel"];
$idcarro = $_GET["idcarro"];
$debito = $_GET["debito"];

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

$rel = new modulos_global_relatorio($tipoRel, "Exame Prático", $_SERVER["PHP_SELF"] . $args);

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
$attrTable["cellpadding"] = "5px";

$examespratico = $mysql->select('data, categoria', 'examepratico', "id = '".$idexamepratico."'");
if (is_array($examespratico)) {
    foreach ($examespratico as $value) {
        $rel->null('<table style="width:100%"><tr><td align="center">Exame Prático - Dia '.db_to_date($value["data"])." Categoria ".$value["categoria"].'</td></tr></table>');
    }
} else {
    $rel->alertAndClose('Exame não encontrado para o filtro informado.');
    $rel->close();
    exit;
}

$wCarro = "";
if ($idcarro != "" && $idcarro != "TODOS") {
    $wCarro = "and a.idcarro = '".$idcarro."' ";
}

$alunos = $mysql->select(
        "a.idcarro, b.idaluno, b.horario, c.carro, d.nome as aluno, d.matriculacfc, d.renach ",
        "examepraticocarro a, examepraticoalunos b, vcarros c, valunos d",
        "a.id = b.idexamepraticocarro ".
            "and a.idcarro = c.id ".
            "and b.idaluno = d.id ".
            "and a.idexamepratico = '".$idexamepratico."' ".
            $wCarro,
        null,
        "a.idcarro, b.horario, d.nome");

$lastCarro = 0;
foreach ($alunos as $aluno) {
    if ($lastCarro != $aluno["idcarro"]) {
        if ($lastCarro > 0) {
            $rel->closeTable();
        }
        $rel->h2($aluno["carro"]);
        $rel->openTable('tblEPAlunos', $attrTable);
        $rel->newLine();
        $rel->newCelHeader('Horário');
        $rel->newCelHeader('Matrícula CFC');
        $rel->newCelHeader('RENACH');
        $rel->newCelHeader('Nome');
        if ($debito == "S") {
            $rel->newCelHeader('Débito');
        }
        $rel->closeLine();
        $lastCarro = $aluno["idcarro"];
    }

    $rel->newLine();
    $rel->newCel(substr($aluno["horario"], 0, 5), array('style'=>'text-align:center;'));
    $rel->newCel($aluno["matriculacfc"], array('style'=>'text-align:right;'));
    $rel->newCel($aluno["renach"]);
    $rel->newCel($aluno["aluno"]);

    if ($debito == "S") {
        $valorDebito = $mysql->getValue(
                "sum(a.valor - a.desconto) as debito",
                "debito",
                "alunoservico a ",
                "a.idaluno = '". $aluno["idaluno"] ."' ");
        $valorPago = $mysql->getValue(
                "sum(c.valor) as debito",
                "debito",
                "alunoservico a ".
                    "inner join contasareceber c on a.id = c.idalunoservico",
                "a.idaluno = '". $aluno["idaluno"] ."' ");
        $valorDebito = $valorDebito - $valorPago;
        $rel->newCel(db_to_float($valorDebito), array('align'=>'right'));
    }

    $rel->closeLine();
}

$rel->close();

?>