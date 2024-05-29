<?php
include_once("../../configuracao.php");

$idaluno = $_POST["idaluno"];

$mysql = new modulos_global_mysql();

if (!isset ($idaluno) or $idaluno == 0) {
    exit;
}

$horanoturna = $mysql->getValue("valor", null, "sistema", "campo = 'horanoturna'");

$total = $mysql->getValue(
        'count(*) as total', 
        'total', 
        'aulaspraticas', 
        "idaluno = '".$idaluno."' and data != '0000-00-00' and hora != '00:00:00'");

$totalnoite = $mysql->getValue('count(*) as total', 'total', 'aulaspraticas', "idaluno = '".$idaluno."' and hora >= '".$horanoturna."'");

$totServ = $mysql->getValue('coalesce(sum(qtaulaspraticas),0) as total', 'total', 'alunoservico', "idaluno = '".$idaluno."'");

if (!isset ($totServ) or $totServ == '') {
    $totServ = 0;
}

?>
<div class="dContadorAulaPratica ui-widget ui-widget-content ui-corner-all">
    <div class="dContadorATCabecalho ui-widget-header">Total Aulas</div>
    <div class="dContadorATValor"><?php echo $total . " / " . $totServ; ?></div>
</div>
<div class="dContadorAulaPratica2 ui-widget ui-widget-content ui-corner-all">
    <div class="dContadorATCabecalho ui-widget-header">Aulas Noturnas</div>
    <div class="dContadorATValor"><?php echo $totalnoite; ?></div>
</div>