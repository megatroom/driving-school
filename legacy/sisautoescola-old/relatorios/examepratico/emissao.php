<?php
include_once("../../configuracao.php");

$datai = $_GET["datai"];
$dataf = $_GET["dataf"];
$tipo  = $_GET["tipo"];
$exibeColunaEmail = $_GET["exibeColunaEmail"];
$naoExibirAlunoSemEmail = $_GET["naoExibirAlunoSemEmail"];

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

$countExistente = $mysql->getValue(
        "count(a.id) as total",
        "total",
        "examepraticoalunos a, examepraticocarro b, examepratico c",
        "a.idexamepraticocarro = b.id ".
            "and b.idexamepratico = c.id ".
            "and c.data between '".date_to_db($datai)."' and '".date_to_db($dataf)."' ",
        "group by a.resultado");
if ($countExistente == 0) {
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

$lstResumo = $mysql->select(
        "count(a.id) as total, a.resultado",
        "examepraticoalunos a, examepraticocarro b, examepratico c",
        "a.idexamepraticocarro = b.id ".
            "and b.idexamepratico = c.id ".
            "and c.data between '".date_to_db($datai)."' and '".date_to_db($dataf)."' ",
        "group by a.resultado");
//echo $mysql->getMsgErro();
$totAprovado = 0;
$totReprovado = 0;
$totNaoSeAplica = 0;
if (is_array($lstResumo)) {
    foreach ($lstResumo as $resumo) {
        if ($resumo["resultado"] == "A") {
            $totAprovado = $resumo["total"];
        } else if ($resumo["resultado"] == "R") {
            $totReprovado = $resumo["total"];
        } else {
            $totNaoSeAplica = $resumo["total"];
        }
    }
}

$totTotal = $totAprovado + $totReprovado + $totNaoSeAplica;
$percAprovado = "0";
$percReprovado = "0";
$percNaoSeAplica = "0";
if ($totTotal > 0) {
    $percAprovado = ($totAprovado * 100) / $totTotal;
    $percReprovado = ($totReprovado * 100) / $totTotal;
    $percNaoSeAplica = ($totNaoSeAplica * 100) / $totTotal;
}

$rel->titulo("Exame Prático");
$rel->subTitulo("Período de ".$datai." a ".$dataf);

$attr = null;
$attr["border"] = "1";
$attr["cellpadding"] = "5px";
$rel->openTable("tblRelEPTotal", $attr);
$rel->newLine();
$rel->newCelHeader("Resultado");
$rel->newCelHeader("Total");
$rel->newCelHeader("Porcentagem");
$rel->closeLine();
$attrNumber = null;
$attrNumber["align"] = "right";
$rel->newLine();
$rel->newCel("Aprovados");
$rel->newCel($totAprovado, $attrNumber);
$rel->newCel(formatar_numero($percAprovado)."%", $attrNumber);
$rel->closeLine();
$rel->newLine();
$rel->newCel("Reprovados");
$rel->newCel($totReprovado, $attrNumber);
$rel->newCel(formatar_numero($percReprovado)."%", $attrNumber);
$rel->closeLine();
$rel->newLine();
$rel->newCel("Não se aplica");
$rel->newCel($totNaoSeAplica, $attrNumber);
$rel->newCel(formatar_numero($percNaoSeAplica)."%", $attrNumber);
$rel->closeLine();
$rel->newFoot();
$rel->newLine();
$rel->newCel("Total");
$rel->newCel($totTotal, $attrNumber);
$rel->newCel("100%", $attrNumber);
$rel->closeLine();
$rel->closeFoot();
$rel->closeTable();

$whereNoEmail = '';
if ($naoExibirAlunoSemEmail == 'S') {
    $whereNoEmail = "and coalesce(d.noemail, 'N') = 'N'";
}

$lstAlunos = $mysql->select(
        "d.matriculacfc, e.nome, e.email, a.resultado",
        "examepraticoalunos a, examepraticocarro b, examepratico c, alunos d, pessoas e",
        "a.idexamepraticocarro = b.id ".
            "and b.idexamepratico = c.id ".
            "and a.idaluno = d.id ".
            "and d.idpessoa = e.id ".
            "and c.data between '".date_to_db($datai)."' and '".date_to_db($dataf)."' ".
            $whereNoEmail,
        null,
        "resultado, nome");
//echo $mysql->getMsgErro();

$lastResultado = "";
$attrTable = null;
$attrTable["border"] = "1";
$attrTable["cellpadding"] = "5px";
if (is_array($lstAlunos)) {
    foreach ($lstAlunos as $aluno) {
        if ($lastResultado != $aluno["resultado"]) {
            if ($lastResultado != "") {
                $rel->closeTable();
            }
            $rel->h2(examepratico_resultado_to_str($aluno["resultado"]));
            $rel->openTable("tblLstEXAlunos", $attrTable);
            $rel->newLine();
            $rel->newCelHeader("Matrícula CFC");
            $rel->newCelHeader("Alunos");
            if ($exibeColunaEmail == "S") {
                $rel->newCelHeader('@');
            }
            $rel->closeLine();
            $lastResultado = $aluno["resultado"];
        }
        $rel->newLine();
        $rel->newCel($aluno["matriculacfc"]);
        $rel->newCel($aluno["nome"]);
        if ($exibeColunaEmail == "S") {
            if (isset($aluno['email']) && $aluno['email'] !== '') {
                $rel->newCel('X');
            } else {
                $rel->newCel('&nbsp;');
            }
        }
        $rel->closeLine();
    }
    $rel->closeTable();
}

$rel->close();

?>