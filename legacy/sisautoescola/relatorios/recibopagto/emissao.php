<?php
include_once ("../../configuracao.php");

$tipoRel = $_GET["tiporel"];
$idalunoservico = $_GET["idalunoservico"];

$rel = new modulos_global_relatorio($tipoRel, "Declaração");

$mysql = new modulos_global_mysql();

/*
 * Cabeçalho - Início
 */
$cbcRel = $mysql->select(
  "a.campo, a.valor",
  "sistema a",
  "a.campo = 'reltitulo' or a.campo = 'reldesc'"
);

$titulo = "";
$cabecalho = "";
if (is_array($cbcRel)) {
  foreach ($cbcRel as $cabecalhoRel) {
    if ($cabecalhoRel["campo"] == "reltitulo") {
      $titulo = $cabecalhoRel["valor"];
    }
    if ($cabecalhoRel["campo"] == "reldesc") {
      $cabecalho = $cabecalhoRel["valor"];
      $cabecalho = str_replace("\n", '<br />', $cabecalho);
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

$rel->titulo('Recibo de Pagamento');

$rows = $mysql->select(
  'a.texto',
  'relatorios a',
  "a.codigo = '1'",
  null,
  'a.id'
);

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

function formatarVariaveis($txt, $idalunoservico)
{
  $conteudo = $txt;
  $sql = new modulos_global_mysql();
  $nomeAluno = $sql->getValue('a.nome', 'nome', 'valunos a, alunoservico b', "b.id = '" . $idalunoservico . "' and b.idaluno = a.id");
  $rows = $sql->select(
    'sum(b.valor) as valor, b.data as datapagto',
    "alunoservico a, contasareceber b",
    "a.id = b.idalunoservico " .
    "and b.data = (select max(data) from contasareceber where idalunoservico = a.id) " .
    "and a.id = '" . $idalunoservico . "' ",
    "group by b.data"
  );
  if (is_array($rows)) {
    foreach ($rows as $row) {
      $valorRecebido = "R$ " . db_to_float($row["valor"]);
      $dataRecebimento = db_to_date($row["datapagto"]);
    }
  }
  $conteudo = str_replace("{nomeAluno}", $nomeAluno, $conteudo);
  $conteudo = str_replace("{valorRecebido}", $valorRecebido, $conteudo);
  $conteudo = str_replace("{dataRecebimento}", $dataRecebimento, $conteudo);
  return $conteudo;
}

?>
