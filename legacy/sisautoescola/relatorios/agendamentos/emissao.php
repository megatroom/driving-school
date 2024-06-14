<?php
include_once("../../configuracao.php");

$datai = $_GET["datai"];
$dataf = $_GET["dataf"];
$resultado = $_GET["resultado"];
$tipo = $_GET["tipo"];
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

if (!isset($tipo) or $tipo == "") {
    $rel->titulo('Relatório de Agendamentos');
} else {
    $descTipo = $mysql->getValue('descricao', null, 'tiposagendamentos', "id = '".$tipo."'");
    $rel->titulo('Relatório de '.$descTipo);
}
$rel->subTitulo('Período de '.$datai.' a '.$dataf);

$showResultado = true;
$where = "";
if (strlen($resultado) > 0) {
    if ($resultado == "AR") {
        $where .= "and a.aprovado in ('A', 'R') ";
    } else {
        $where .= "and a.aprovado = '".$resultado."' ";
        $showResultado = false;
    }
}
if (strlen($tipo) > 0) {
    $where .= "and a.idtipoagendamento = '".$tipo."' ";
}
$lstRelatorio = $mysql->select(
        'a.data, a.hora, b.descricao, a.aprovado, c.nome',
        'agendamentos a, tiposagendamentos b, valunos c',
        "a.idtipoagendamento = b.id ".
            "and a.idaluno = c.id ".
            "and a.data between '".date_to_db($datai)."' and '".date_to_db($dataf)."' ".
            $where,
        null,
        "data, hora, nome");
//echo $mysql->getMsgErro() . "<br><br>";

if (is_array($lstRelatorio)) {
    $attr = null;
    $attr["border"] = "1";
    $attr["cellpadding"] = "5px";
    $attr["style"] = "width:100%;";
    $rel->openTable('tblRelAgendResult', $attr);
    $rel->newLine();
    $rel->newCelHeader("Data");
    $rel->newCelHeader("Hora");
    $rel->newCelHeader("Aluno");
    if ($showResultado) {
        $rel->newCelHeader("Resultado");
    }
    $rel->closeLine();
    foreach ($lstRelatorio as $linha) {
        $rel->newLine();
        $rel->newCel(db_to_date($linha["data"]), array("align"=>"center"));
        $rel->newCel($linha["hora"], array("align"=>"center"));
        $rel->newCel($linha["nome"]);
        if ($showResultado) {
            $rel->newCel(agendamento_tipo_to_str($linha["aprovado"]), array("align"=>"center"));
        }
        $rel->closeLine();
    }
    $rel->closeTable();
} else {
    $rel->alertAndClose('Não há nenhum agendamento para o filtro informado!');
    $rel->close();
    exit;
}

$rel->divClear("20px");

$lstTotal = $mysql->select(
        'a.aprovado, count(*) as total',
        'agendamentos a, tiposagendamentos b, valunos c',
        "a.idtipoagendamento = b.id ".
            "and a.idaluno = c.id ".
            "and a.data between '".date_to_db($datai)."' and '".date_to_db($dataf)."' ".
            $where,
        "group by a.aprovado",
        "aprovado");
//echo $mysql->getMsgErro() . "<br><br>";

$totAprovado = 0;
$totNaoSeAplica = 0;
$totReprovado = 0;
if (is_array($lstTotal)) {
    foreach ($lstTotal as $total) {
        if ($total["aprovado"] == "A") {
            $totAprovado = $total["total"];
        } else if ($total["aprovado"] == "R") {
            $totReprovado = $total["total"];
        } else {
            $totNaoSeAplica = $total["total"];
        }
    }
}

$totTotal = $totAprovado + $totNaoSeAplica + $totReprovado;
$percAprovado = "0";
$percReprovado = "0";
$percNaoSeAplica = "0";
if ($totTotal > 0) {
    $percAprovado = ($totAprovado * 100) / $totTotal;
    $percReprovado = ($totReprovado * 100) / $totTotal;
    $percNaoSeAplica = ($totNaoSeAplica * 100) / $totTotal;
}

$attr = null;
$attr["border"] = "1";
$attr["cellpadding"] = "5px";
$rel->openTable("tblRelAgendTotal", $attr);
$rel->newLine();
$rel->newCelHeader("Resultado");
$rel->newCelHeader("Total");
$rel->newCelHeader("Porcentagem");
$rel->closeLine();
$attrNumber = null;
$attrNumber["align"] = "right";
if ($resultado == "" or $resultado == "AR" or $resultado == "A") {
    $rel->newLine();
    $rel->newCel("Aprovados");
    $rel->newCel($totAprovado, $attrNumber);
    $rel->newCel(formatar_numero($percAprovado)."%", $attrNumber);
    $rel->closeLine();
}
if ($resultado == "" or $resultado == "AR" or $resultado == "R") {
    $rel->newLine();
    $rel->newCel("Reprovados");
    $rel->newCel($totReprovado, $attrNumber);
    $rel->newCel(formatar_numero($percReprovado)."%", $attrNumber);
    $rel->closeLine();
}
if ($resultado == "" or $resultado == "N") {
    $rel->newLine();
    $rel->newCel("Não se aplica");
    $rel->newCel($totNaoSeAplica, $attrNumber);
    $rel->newCel(formatar_numero($percNaoSeAplica)."%", $attrNumber);
    $rel->closeLine();
}
$rel->newFoot();
$rel->newLine();
$rel->newCel("Total");
$rel->newCel($totTotal, $attrNumber);
$rel->newCel("100%", $attrNumber);
$rel->closeLine();
$rel->closeFoot();
$rel->closeTable();

$rel->close();
?>