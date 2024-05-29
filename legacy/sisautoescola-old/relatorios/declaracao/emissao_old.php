<?php
include_once("../../configuracao.php");

$idaluno = $_GET["idaluno"];
$tipoRel = $_GET["tiporel"];

$rel = new modulos_global_relatorio($tipoRel, "Declaração");

$mysql = new modulos_global_mysql();

$aluno = $mysql->select(
        "a.matriculacfc, a.renach, b.nome, b.cpf",
        "alunos a, pessoas b",
        "a.idpessoa = b.id and a.id = '".$idaluno."'");
if (!is_array($aluno)) {
    $rel->alertAndClose("Aluno não definido!");
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

$rel->divClear("10px");

/*
$attr = null;
$attr["style"] = "width:100%;text-align:center;font-weight: bold;";
$rel->openTable('tblCabecalho', $attr);
$rel->newLine();
$attr = null;
$attr["style"] = "font-family:'Times New Roman',Georgia,Serif;font-size:28pt;letter-spacing:5px;";
$rel->newCel("Declaração", $attr);
$rel->closeLine();
$rel->closeTable();
*/
$rel->titulo("Declaração");

$rel->divClear("10px");

$txtBody = "Declaramos junto ao DETRAN/RJ, que ";
if ($aluno[0]["sexo"] == "F") {
    $txtBody .= "a candidata ";
} else {
    $txtBody .= "o candidato ";
}
$txtBody .= $aluno[0]["nome"];
$txtBody .= ", inscrito com o RENACH ";
$txtBody .= $aluno[0]["renach"];
if ($aluno[0]["sexo"] == "F") {
    $txtBody .= ", portadora ";
} else {
    $txtBody .= ", portador ";
}
$txtBody .= "do CPF nº ";
$txtBody .= $aluno[0]["cpf"];
$txtBody .= ", cumpriu a carga horária de 15 horas/aula de prática de direção veicular, conforme exigência da Resolução nº 168, de 14 de dezembro de 2004, em seu anexo II. ";
$txtBody .= "Obs.: As informações contidas nesta declaração são de inteira responsabilidade do CFC.";

$attr = null;
$attr["style"] = "width:100%;text-align:justify;text-indent:50px;font-size:14pt;";
$rel->openTable('tblCorpo',$attr);
$rel->newLine();
$rel->newCel($txtBody);
$rel->closeLine();
$rel->closeTable();

$rel->divClear("10px");

$rel->openTable('tblAssinatura', array("style" => "min-width:400px;"));
$rel->newLine();
$rel->newCel('&nbsp;', array("style" => "border-bottom: 1px #000000 solid;min-width:200px;"));
$rel->closeLine();
$rel->newLine();
$rel->newCel($aluno[0]["nome"]);
$rel->closeLine();
$rel->closeTable();

$rel->divClear("10px");

$rel->openTable('tblAssinatura', array("style" => "min-width:400px;"));
$rel->newLine();
$rel->newCel('&nbsp;', array("style" => "border-bottom: 1px #000000 solid;min-width:200px;"));
$rel->closeLine();
$rel->newLine();
$rel->newCel(
        'Edmilson de Souza Lima. <br />'.
        'CIC.: 755.657.737-68 <br />'.
        'Diretor de Ensino');
$rel->closeLine();
$rel->closeTable();

$rel->openTable('tblAssinatura', array("style" => "min-width:400px;"));
$rel->newLine();
$rel->newCel('&nbsp;', array("style" => "border-bottom: 1px #000000 solid;min-width:200px;"));
$rel->closeLine();
$rel->newLine();
$rel->newCel("Instrutor");
$rel->closeLine();
$rel->closeTable();

$rel->divClear("10px");


$rel->close();

?>