<?php
include_once("../../configuracao.php");

$idturma = $_GET["idturma"];
$tipoRel = $_GET["tiporel"];

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

$lstAulasTeoricas = $mysql->select('*', 'vaulasteoricas', " idturma = '".$idturma."'", null, 'id');

if (is_array($lstAulasTeoricas)) {

    $rel->openTable('tblAulasPraticas', $attrTable);

    $rel->newLine();
    $rel->newCelHeader("Matrícula CFC");
    $rel->newCelHeader("Aluno");
    $rel->newCelHeader("RENACH");
    $rel->closeLine();

    foreach ($lstAulasTeoricas as $row) {
        $rel->newLine();
        $rel->newCel($row["matriculacfc"]);
        $rel->newCel($row["aluno"]);
        $rel->newCel($row["renach"]);
        $rel->closeLine();
    }

    $rel->closeTable();

} else {
    $rel->h2("Nenhum aluno encontrado para esta turma.");
}

$rel->close();

?>