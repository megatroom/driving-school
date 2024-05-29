<?php
include_once("../../configuracao.php");

$tipoRel = $_GET["tiporel"];
$idalunoservico = $_GET["idalunoservico"];

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

$rel = new modulos_global_relatorio($tipoRel, "Declaração", $_SERVER["PHP_SELF"] . $args);

$mysql = new modulos_global_mysql();

$totalPagamentosDeHoje = $mysql->getValue(
        'count(a.id) as total', 
        'total', 
        'contasareceber a, alunoservico b', 
        "a.idalunoservico = b.id and a.data = CURDATE() and b.id = '".$idalunoservico."'");

if ($totalPagamentosDeHoje == 0) {
    $rel->alertAndClose('Não há pagamento para este aluno na data de hoje!');
}

/**
 * Valida se existe lançamento - se não houver, fechar relatório
 */
$totalRegistros = $mysql->getValue(
            "count(c.id) as total",
            "total",
            "contasareceber c, alunoservico b",
            "c.idalunoservico = b.id and b.id = '".$idalunoservico."'");
if ($totalRegistros == 0) {
    $rel->alertAndClose("Não há pagamento lançado para esta conta!");
    $rel->close();
    exit;
}

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

$rows = $mysql->select('a.texto', 'declaracaopagto a', 'a.id = (select max(id) from declaracaopagto)');

$attrTable = null;
$attrTable["border"] = "0";
$attrTable["cellpadding"] = "5px";
$attrTable["width"] = "100%";
$rel->openTable('tblAlunoAulasPraticas', $attrTable);
$rel->newLine();
$rel->null('<td>');
if (is_array($rows)) {
    foreach ($rows as $row) {
        $texto = $row["texto"];
        $texto = formatarVariaveis($texto, $idalunoservico);
        $rel->null($texto);
    }
} else {
    $rel->null('Nenhum texto encontrado para a declaração escolhida.');
}
$rel->null('</td>');
$rel->closeLine();
$rel->closeTable();

$rel->close();

function formatarVariaveis($txt, $idalunoservico) {
    $conteudo = $txt;
    $sql = new modulos_global_mysql();
    $idAluno = $sql->getValue('idaluno', null, 'alunoservico', "id = '".$idalunoservico."'");
    $nomeAluno = $sql->getValue('nome', null, 'valunos', "id = '".$idAluno."'");
    $cpfAluno = $sql->getValue('cpf', null, 'valunos', "id = '".$idAluno."'");
    $cfcAluno = $sql->getValue('matriculacfc', null, 'valunos', "id = '".$idAluno."'");
    $servico = $sql->getValue('b.descricao', 'descricao', 'alunoservico a, tiposervicos b', "a.idtiposervico = b.id and a.id = '".$idalunoservico."'");
    $dtpagto = $sql->getValue(
            "max(c.data) as dtpagto",
            "dtpagto",
            "contasareceber c, alunoservico b",
            "c.idalunoservico = b.id and b.id = '".$idalunoservico."'");
    $vlpagto = $sql->getValue(
            "max(c.data) as dtpagto, c.valor",
            "valor",
            "contasareceber c, alunoservico b",
            "c.idalunoservico = b.id and b.id = '".$idalunoservico."' ",
            "group by c.valor",
            "dtpagto desc");
    $conteudo = str_replace("{nomeAluno}", $nomeAluno, $conteudo);
    $conteudo = str_replace("{cpfAluno}", $cpfAluno, $conteudo);
    $conteudo = str_replace("{cfcAluno}", $cfcAluno, $conteudo);
    $conteudo = str_replace("{descricaoServico}", $servico, $conteudo);
    $conteudo = str_replace("{dataAtual}", date('d/m/Y'), $conteudo);
    $conteudo = str_replace("{dataPagamento}", db_to_date($dtpagto), $conteudo);
    $conteudo = str_replace("{valorPagamento}", "R$ ".db_to_float($vlpagto), $conteudo);
    return $conteudo;
}

?>