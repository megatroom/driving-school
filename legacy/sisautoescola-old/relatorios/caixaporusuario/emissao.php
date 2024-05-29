<?php
include_once("../../configuracao.php");

$datai = $_GET["datai"];
$dataf = $_GET["dataf"];
$idusuario = $_GET["idusuario"];
$tipoRel = $_GET["tipoRel"];

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

$rel = new modulos_global_relatorio($tipoRel, "Exame Prático - Resultados", $_SERVER["PHP_SELF"] . $args);

$mysql = new modulos_global_mysql();

if (!is_valid_date($datai)) {
    $rel->alertAndClose('Data Inicial informada inválida!');
    $rel->close();
    exit;
}

if (!is_valid_date($dataf)) {
    $rel->alertAndClose('Data Final informada inválida!');
    $rel->close();
    exit;
}

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

$rel->titulo('Relatório de Caixa');
$rel->subTitulo('Período de '.$datai.' a '.$dataf);

if ($idusuario > 0) {
    $nomeUsuario = $mysql->getValue('nome', null, 'vusuarios', "id = '".$idusuario."'");
    $loginUsuario = $mysql->getValue('login', null, 'vusuarios', "id = '".$idusuario."'");
    $rel->openTable("tblUsuario", array("cellpadding"=>"3"));
    $rel->newLine();
    $rel->newCel("Caixa: ", array("style" => "font-weight: bold"));
    $rel->newCel($nomeUsuario . " (" . $loginUsuario . ")");
    $rel->closeLine();
    $rel->closeTable();
    $rel->divClear(2);
}

$whereUsuario = "c.data between '".date_to_db($datai)."' and '".date_to_db($dataf)."'";
if ($idusuario > 0) {
    $whereUsuario .= " and u.id = '".$idusuario."'";
}
$rows = $mysql->select(
        'v.nome as aluno, t.descricao as tiposervico, c.data, c.valor, u.nome, u.login',
        'alunoservico a '.
            'inner join tiposervicos t on a.idtiposervico = t.id '.
            'inner join valunos v on a.idaluno = v.id '.
            'inner join contasareceber c on c.idalunoservico = a.id '.
            'inner join vusuarios u on c.idusuario = u.id ',
        $whereUsuario,
        null,
        "u.nome, c.data");

if (!is_array($rows)) {
    $rel->alertAndClose("Não há lançamentos para o filtro informado!");
    $rel->close();
}

$attrTable = null;
$attrTable["border"] = "1";
$attrTable["cellpadding"] = "2px";

$rel->openTable("tblRelCaixaPorUsuario", $attrTable);
$rel->newLine();
if ($idusuario == 0) {
    $rel->newCelHeader('Nome');
    $rel->newCelHeader('Login');
}
$rel->newCelHeader('Data');
$rel->newCelHeader('Valor Recebido');
$rel->newCelHeader('Aluno');
$rel->newCelHeader('Tipo do Serviço');
$rel->closeLine();

$valorTotal = 0;

foreach ($rows as $row) {
   $rel->newLine();
   if ($idusuario == 0) {
        $rel->newCel($row["nome"]);
        $rel->newCel($row["login"]);
   }
   $rel->newCel(db_to_date($row["data"]), array("style" => "text-align:center;"));
   $rel->newCel(db_to_float($row["valor"]), array("style" => "text-align:right;"));
   $rel->newCel($row["aluno"]);
   $rel->newCel($row["tiposervico"]);
   $rel->closeLine();
   $valorTotal += $row["valor"];
}

$rel->newFoot(array("style" => "font-weight: bold"));
$rel->newCel("Total:");
if ($idusuario == 0) {
    $rel->newCel(db_to_float($valorTotal), array("colspan"=>"5","style" => "text-align:right;"));
} else {
    $rel->newCel(db_to_float($valorTotal), array("colspan"=>"6","style" => "text-align:right;"));
}
$rel->closeFoot();

$rel->closeTable();

$rel->close();

?>