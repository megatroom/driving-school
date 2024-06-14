<?php
include_once("../../configuracao.php");

$ordem = $_GET["ordem"];
$status = $_GET["status"];
$tipo  = $_GET["tipo"];

$mysql = new modulos_global_mysql();

$pWhere = null;
if ($status == "A") {
    $pWhere = "t.status = 'A'";
} else if ($status == "I") {
    $pWhere = "t.status = 'I'";
}
$pOrderBy = "t.id";
if ($ordem == "2") {
    $pOrderBy = "t.descricao";
}

$rows = $mysql->select(
        "t.id, t.descricao, t.qtaulaspraticas, ".
            "t.qtaulasteoricas, t.valor, t.status, t.diasavencer, ".
            "(select count(a.id) from alunoservico a where a.idtiposervico = t.id) as totalservico",
        "tiposervicos t",
        $pWhere,
        null,
        $pOrderBy);

$cbcRel = $mysql->select(
        "a.campo, a.valor",
        "sistema a",
        "a.campo = 'reltitulo' or a.campo = 'reldesc'");

$rel = new modulos_global_relatorio($tipo, "Tipo de Serviços", $_SERVER["PHP_SELF"] . $args);

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

$attr = null;
$attr["border"] = "1";
$attr["cellpadding"] = "5px";
$rel->openTable('tblTipoServicos', $attr);
$rel->newLine();
$rel->newCelHeader('Código');
$rel->newCelHeader('Descrição');
$rel->newCelHeader('Qtd Aulas Práticas');
$rel->newCelHeader('Qtd Aulas Teóricas');
$rel->newCelHeader('Valor');
$rel->newCelHeader('Status');
$rel->newCelHeader('Dias a vencer');
$rel->newCelHeader('Total de Serviços');
$rel->closeLine();

foreach ($rows as $row) {
    $rel->newLine();
    $rel->newCel($row["id"], array("align" => "center"));
    $rel->newCel($row["descricao"]);
    $rel->newCel($row["qtaulaspraticas"], array("align" => "center"));
    $rel->newCel($row["qtaulasteoricas"], array("align" => "center"));
    $rel->newCel(db_to_float($row["valor"]), array("align" => "right"));
    $rel->newCel(tipoServicoStatus($row["status"]), array("align" => "center"));
    $rel->newCel($row["diasavencer"], array("align" => "center"));
    $rel->newCel($row["totalservico"], array("align" => "center"));
    $rel->closeLine();
}

$rel->closeTable();
$rel->close();

function tipoServicoStatus($pStatus) {
    if ($pStatus == "A") {
        return "Ativo";
    } else {
        return "Inativo";
    }
}
?>
