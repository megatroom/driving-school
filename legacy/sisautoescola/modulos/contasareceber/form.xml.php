<?php
include_once("../../configuracao.php");

$page  = $_GET['page'];
$limit = $_GET['rows'];
$sidx  = $_GET['sidx'];
$sord  = $_GET['sord'];

$idalunoservico = $_GET['idalunoservico'];

if (!isset ($sidx) or $sidx == "") {
    $sidx = '1';
}
if (!isset ($sord) or $sord == "") {
    $sord = 'asc';
}

$wh = null;
$where = null;
$searchOn = Strip($_GET['_search']);
if($searchOn=='true') {
	$sarr = Strip($_REQUEST);
	foreach( $sarr as $k=>$v) {
		switch ($k) {
                        case 'valor':
                        case 'data':
				$wh[] = "upper(".$k.") LIKE upper('%".$v."%')";
				break;
		}
	}
}
$wh[] = "idalunoservico = '".$idalunoservico."'";
if (is_array($wh)) {
    $where = join(" and ", $wh);
}

$mysql = new modulos_global_mysql();

$countTable = $mysql->getValue('count(id) as total','total','contasareceber',$where);

$xmlMainFun = new modulos_global_gridxml($page, $limit, $countTable);

$rows = $mysql->select(
        'id, valor, data',
        'contasareceber',
        $where,
        "order by $sidx $sord LIMIT ".$xmlMainFun->getStart()." , ".$xmlMainFun->getLimit()." ");

$xCount = 0;

if (is_array($rows)) {
    foreach ($rows as $row) {
        $xmlMainFun->startRow($xCount);
        $xmlMainFun->addCell($row["id"]);
        $xmlMainFun->addCell(db_to_date($row["data"]));
        $xmlMainFun->addCell(db_to_float($row["valor"]));
        $xmlMainFun->endRow();
        $xCount++;
    }
}

$xmlMainFun->close();

?>