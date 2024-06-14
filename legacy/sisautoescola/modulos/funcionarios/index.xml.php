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
    $sord = 'asc';
}

$wh = null;
$where = null;
$wh[] = "a.idpessoa = b.id";
$searchOn = Strip($_GET['_search']);
if($searchOn=='true') {
	$sarr = Strip($_REQUEST);
	foreach( $sarr as $k=>$v) {
		switch ($k) {
			case 'matricula':
                                $wh[] = "upper(a.matricula) LIKE upper('%".$v."%')";
				break;
                        case 'nome':
				$wh[] = "upper(b.nome) LIKE upper('%".$v."%')";
				break;
                        case 'nome':
				$wh[] = "upper(b.telefone) LIKE upper('%".$v."%')";
				break;
                        case 'nome':
				$wh[] = "upper(b.celular) LIKE upper('%".$v."%')";
				break;
		}
	}
}
if (is_array($wh)) {
    $where = join(" and ", $wh);
}

$mysql = new modulos_global_mysql();

$countTable = $mysql->getValue('count(a.id) as total','total','funcionarios a, pessoas b',$where);

$xmlMainFun = new modulos_global_gridxml($page, $limit, $countTable);

$rows = $mysql->select('a.id, a.matricula, b.nome, b.telefone, b.celular', 'funcionarios a, pessoas b', $where, "order by $sidx $sord LIMIT ".$xmlMainFun->getStart()." , ".$xmlMainFun->getLimit()." ");

$xCount = 0;

foreach ($rows as $row) {
    $xmlMainFun->startRow($xCount);
    $xmlMainFun->addCell($row["id"]);
    $xmlMainFun->addCell($row["matricula"]);
    $xmlMainFun->addCell($row["nome"]);
    $xmlMainFun->addCell($row["telefone"]);
    $xmlMainFun->addCell($row["celular"]);
    $xmlMainFun->endRow();
    $xCount++;
}

$xmlMainFun->close();

?>