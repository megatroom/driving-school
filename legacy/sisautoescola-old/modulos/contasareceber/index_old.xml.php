<?php
include_once("../../configuracao.php");

$page  = $_GET['page'];
$limit = $_GET['rows'];
$sidx  = $_GET['sidx'];
$sord  = $_GET['sord'];

$pStatus = $_GET['status'];

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
			case 'tiposervico':
                        case 'aluno':
                        case 'valor':
                        case 'data':
                        case 'valorapagar':
				$wh[] = "upper(".$k.") LIKE upper('".$v."%')";
				break;
		}
	}
}
if (isset ($pStatus) and $pStatus == "aberto") {
    $wh[] = "lower(v.status) = 'aberto'";
}
if (is_array($wh)) {
    $where = join(" and ", $wh);
}

$mysql = new modulos_global_mysql();

$countTable = $mysql->getValue('count(v.id) as total','total','valunoservico v',$where);

$xmlMainFun = new modulos_global_gridxml($page, $limit, $countTable);

$rows = $mysql->select(
        'v.id, v.data, v.valor, v.valorapagar, v.tiposervico, v.aluno',
        'valunoservico v',
        $where,
        "order by $sidx $sord LIMIT ".$xmlMainFun->getStart()." , ".$xmlMainFun->getLimit()." ");

$xCount = 0;

if (is_array($rows)) {
    foreach ($rows as $row) {
        $xmlMainFun->startRow($xCount);
        $xmlMainFun->addCell($row["id"]);
        $xmlMainFun->addCell(db_to_date($row["data"]));
        $xmlMainFun->addCell($row["aluno"]);
        $xmlMainFun->addCell($row["tiposervico"]);
        $xmlMainFun->addCell(db_to_float($row["valor"]));
        $xmlMainFun->addCell(db_to_float($row["valorapagar"]));
        $xmlMainFun->endRow();
        $xCount++;
    }
}

$xmlMainFun->close();

?>