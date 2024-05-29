<?php
include_once("../../configuracao.php");

$datai = $_GET["datai"];
$dataf = $_GET["dataf"];
$tipo  = $_GET["tipo"];
$idfuncionario = $_GET["idfuncionario"];

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

$rel = new modulos_global_relatorio($tipo, "Exame Prático - Resultados", $_SERVER["PHP_SELF"] . $args);

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

$where = "v.idfuncionario = f.id ";
$where .= "and v.data between '".date_to_db($datai)."' and '".date_to_db($dataf)."' ";
$where2 = "b.idfuncionario = c.id ";
$where2 .= "and b.data between '".date_to_db($datai)."' and '".date_to_db($dataf)."' ";
if (isset ($idfuncionario) && $idfuncionario != "") {
    if ($idfuncionario > 0) {
        $where  .= "and v.idfuncionario = '".$idfuncionario."'";
        $where2 .= "and b.idfuncionario = '".$idfuncionario."'";
    }
} else {
    $idfuncionario = 0;
}

$countExistente1 = $mysql->getValue(
        "count(v.id) as total",
        "total",
        "vales v, vfuncionarios f",
        $where);
$countExistente2 = $mysql->getValue(
        "count(b.id) as total",
        "total",
        "bonus b, vfuncionarios f",
        $where2);
if ($countExistente1 == 0 && $countExistente2 == 0) {
    $rel->alertAndClose('Não há registros para o filtro informado!');
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

$rows = $mysql->select(
        '1 as tipo, v.data, v.valor, v.motivo, f.nome, f.id as idfun',
        'vales v, vfuncionarios f',
        $where,
        'union '.
            "select 2 as tipo, b.data, b.valor, b.motivo, c.nome, c.id as idfun ".
            "from bonus b, vfuncionarios c ".
            "where ".$where2,
        'nome, data');
//echo $mysql->getMsgErro();

$lastFun = 0;
$total = 0;

if ($idfuncionario > 0) {
    $nomeFun = $mysql->getValue(
            "nome",
            null,
            "vfuncionarios",
            "id = '".$idfuncionario."'",
            null,
            null,
            null);
    $rel->null('<b>Funcionário:</b> '.$nomeFun.'<br>');
    $rel->null('<b>Período:</b> '.$datai.' a '.$dataf.'<br>');
}

$attr = null;
$attr["border"] = "1";
$attr["cellpadding"] = "5px";
$rel->openTable('tblRelVales', $attr);
$rel->newLine();
if ($idfuncionario == 0) {
    $rel->newCelHeader('Funcionário');
    $numColspanTotal = "5";
} else {
    $numColspanTotal = "4";
}
$rel->newCelHeader('Data');
$rel->newCelHeader('Tipo');
$rel->newCelHeader('valor');
$rel->newCelHeader('motivo');
$rel->closeLine();
foreach ($rows as $row) {

    if (($lastFun != $row["idfun"]) && ($lastFun > 0)) {
        $rel->newLine();
        $rel->newCel("Total: ".db_to_float($total), array("colspan"=>$numColspanTotal));
        $rel->closeLine();
        $total = 0;
    }
 
    $lastFun = $row["idfun"];
    if ($row["tipo"] == "1") {
        $total -= $row["valor"];
        $valorAtual = db_to_float($row["valor"] * -1);
    } else {
        $total += $row["valor"];
        $valorAtual = db_to_float($row["valor"]);
    }

    $rel->newLine();
    if ($idfuncionario == 0) {
        $rel->newCel($row["nome"]);
    }
    $rel->newCel(db_to_date($row["data"]), array("align"=>"center"));
    $rel->newCel(getTipo($row["tipo"]), array("align"=>"center"));
    $rel->newCel($valorAtual, array("align"=>"right"));
    $rel->newCel($row["motivo"]);
    $rel->closeLine();
}
$rel->newLine();
$rel->newCel("Total: ".db_to_float($total), array("colspan"=>$numColspanTotal));
$rel->closeLine();
$rel->closeTable();

$rel->close();

function getTipo($pTipo) {
    if ($pTipo == 1) {
        return 'VALE';
    } else if ($pTipo == 2) {
        return 'BÔNUS';
    } else {
        return '';
    }
}
?>