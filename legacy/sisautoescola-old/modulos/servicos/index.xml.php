<?php
include_once("../../configuracao.php");

$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];

if (!isset ($sidx) or $sidx == "") {
    $sidx = '1';
}
if (!isset ($sord) or $sord == "") {
    $sidx = 'asc';
}

$wh = null;
$where = null;
$searchOn = Strip($_GET['_search']);
if($searchOn=='true') {
	$sarr = Strip($_REQUEST);
	foreach( $sarr as $k=>$v) {
		switch ($k) {
                        case 'matricula':
                        case 'matriculacfc':
                        case 'nome':
                        case 'cpf':
                        case 'renach':
                        case 'telefone':
				$wh[] = "upper(".$k.") LIKE upper('".$v."%')";
				break;
		}
	}
}
if (isset($_GET["ftnome"])) {
    $wh[] = "upper(nome) like upper('%".$_GET["ftnome"]."%')";
}
if (is_array($wh)) {
    $where = join(" and ", $wh);
}

$mysql = new modulos_global_mysql();

$countTable = $mysql->getValue(
        'count(a.id) as total',
        'total',
        'alunos a '.
        'left join pessoas b on a.idpessoa = b.id ',
        $where);

$xmlMainFun = new modulos_global_gridxml($page, $limit, $countTable);

$rows = $mysql->select(
        'a.id, a.matricula, a.matriculacfc, b.nome, b.cpf, a.renach, b.telefone ',
        'alunos a '.
        'left join pessoas b on a.idpessoa = b.id ',
        $where,
        "order by $sidx $sord LIMIT ".$xmlMainFun->getStart()." , ".$xmlMainFun->getLimit()." ");

$xCount = 0;

foreach ($rows as $row) {
    $xmlMainFun->startRow($xCount);
    $xmlMainFun->addCell($row["id"]);
    $xmlMainFun->addCell($row["matricula"]);
    $xmlMainFun->addCell($row["matriculacfc"]);
    $xmlMainFun->addCell($row["nome"]);
    $xmlMainFun->addCell($row["cpf"]);
    $xmlMainFun->addCell($row["renach"]);
    $xmlMainFun->addCell($row["telefone"]);
    $xmlMainFun->endRow();
    $xCount++;
}

$xmlMainFun->close();

?>