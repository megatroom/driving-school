<?php
ob_start(); session_start(); ob_end_clean();
include_once("../../configuracao.php");

$tipoRel = $_GET["tiporel"];
$idaluno = $_GET["idaluno"];
$iddeclaracao = $_GET["iddeclaracao"];

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

$rows = $mysql->select(
        'a.texto',
        'declaracoesitens a',
        "a.iddeclaracao = '".$iddeclaracao."'",
        null,
        'a.id');

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
        $texto = formatarVariaveis($texto, $idaluno);
        $rel->null($texto);
    }
} else {
    $rel->null('Nenhum texto encontrado para a declaração escolhida.');
}
$rel->null('</td>');
$rel->closeLine();
$rel->closeTable();

$rel->close();

function formatarVariaveis($txt, $idAluno) {
    $conteudo = $txt;
    $sql = new modulos_global_mysql();
    $nomeAluno = $sql->getValue('nome', null, 'valunos', "id = '".$idAluno."'");
    $cpfAluno = $sql->getValue('cpf', null, 'valunos', "id = '".$idAluno."'");
    $cfcAluno = $sql->getValue('matriculacfc', null, 'valunos', "id = '".$idAluno."'");

    $examePratico = $sql->select(
            "a.idexamepraticocarro, a.data as dtexam, a.horario",
            "vexamepraticoaluno a",
            "a.data = (select max(x.data) from vexamepraticoaluno x where a.idaluno = x.idaluno) ".
            "and a.idaluno = '".$idAluno."' ");

    $examePraticoCarroId = 0;
    $examePraticoData = "__/__/____";
    $examePraticoHora = "__:__";
    if (is_array($examePratico)) {
        foreach ($examePratico as $value) {
            $examePraticoData = $value["dtexam"];
            $examePraticoCarroId = $value["idexamepraticocarro"];
            $examePraticoHora = substr($value["horario"], 0, 5);
        }
    }

    $examePraticoInstrutores = $sql->select(
            "c.nome",
            "examepraticocarro a, carros b, vfuncionarios c",
            "a.id = '".$examePraticoCarroId."' and ".
                "a.idcarro = b.id and b.idfunfixo = c.id ");

    $examePraticoInstrutorNome = "";
    if (is_array($examePraticoInstrutores)) {
        foreach ($examePraticoInstrutores as $value) {
            $examePraticoInstrutorNome = $value["nome"];
        }
    }

    $conteudo = str_replace("{nomeAluno}", $nomeAluno, $conteudo);
    $conteudo = str_replace("{cpfAluno}", $cpfAluno, $conteudo);
    $conteudo = str_replace("{cfcAluno}", $cfcAluno, $conteudo);
    $conteudo = str_replace("{dataAtual}", date('d/m/Y'), $conteudo);
    $conteudo = str_replace("{dataExamePratico}", db_to_date($examePraticoData), $conteudo);
    $conteudo = str_replace("{instrutorExamePratico}", $examePraticoInstrutorNome, $conteudo);
    $conteudo = str_replace("{usuarioLogado}", $_SESSION["USUARIO_NOME"], $conteudo);
    $conteudo = str_replace("{horaExamePratico}", $examePraticoHora, $conteudo);
    return $conteudo;
}

?>