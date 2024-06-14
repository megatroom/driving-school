<?php
include_once("../../configuracao.php");

$datai = $_GET["datai"];
$dataf = $_GET["dataf"];
$tipo  = $_GET["tipo"];

$rel = new modulos_global_relatorio($tipo, "Validade do Processo");

if (!is_valid_date($datai)) {
    $rel->alertAndClose('Data inicial inválida!');
    $rel->close();
    exit;
}
if (!is_valid_date($dataf)) {
    $rel->alertAndClose('Data final inválida!');
    $rel->close();
    exit;
}

$mysql = new modulos_global_mysql();

$rows = $mysql->select(
        "a.matriculacfc, a.validadeprocesso, b.nome",
        "alunos a, pessoas b",
        "a.idpessoa = b.id and ".
            "a.validadeprocesso between date('".date_to_db($datai)."') and date('".date_to_db($dataf)."')",
        null,
        "validadeprocesso desc");

$attrTable = null;
$attrTable["border"] = "1";
$attrTable["cellpadding"] = "5px";
if (is_array($rows)) {

    $rel->titulo('Validade do Processo');
    $rel->subTitulo('Período de '.$datai.' a '.$dataf);

    $rel->openTable("tblValidProcesso", $attrTable);
    $rel->newLine();
    $rel->newCelHeader('Matrícula CFC');
    $rel->newCelHeader('Nome');
    $rel->newCelHeader('Validade do Processo');
    $rel->closeLine();
    foreach ($rows as $row) {
        $rel->newLine();
        $rel->newCel($row["matriculacfc"]);
        $rel->newCel($row["nome"]);
        $rel->newCel(db_to_date($row["validadeprocesso"]));
        $rel->closeLine();
    }
    $rel->closeTable();
} else {
    $rel->alertAndClose("Não há nenhum aluno com validade do processo para o filtro informado.");
    $rel->close();
    exit;
}

$rel->close();

?>