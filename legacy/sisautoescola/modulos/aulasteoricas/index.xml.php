<?php
include_once("../../configuracao.php");

$page  = $_GET['page'];
$limit = $_GET['rows'];
$sidx  = $_GET['sidx'];
$sord  = $_GET['sord'];

//echo $_GET['_search'];

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
			case 'sala':
                                $wh[] = "upper(b.descricao) LIKE upper('%".$v."%')";
				break;
                        case 'data':
                                $wh[] = date_to_where("a.data", $v);
				break;
                        case 'hora':
				$wh[] = "a.hora LIKE '%".$v."%'";
				break;
		}
	}
}
$wh[] = "a.idsala = b.id";
$wh[] = "a.fechada <> 1";
if (is_array($wh)) {
    $where = join(" and ", $wh);
}

$mysql = new modulos_global_mysql();

$countTable = $mysql->getValue('count(a.id) as total','total','turmas a, salas b',$where);

$xmlMainFun = new modulos_global_gridxml($page, $limit, $countTable);

$rows = $mysql->select('a.id, b.descricao as sala, a.data, a.hora', 'turmas a, salas b', $where, "order by $sidx $sord LIMIT ".$xmlMainFun->getStart()." , ".$xmlMainFun->getLimit()." ");

$xCount = 0;

if (is_array($rows)) {
    foreach ($rows as $row) {
        $xmlMainFun->startRow($xCount);
        $xmlMainFun->addCell($row["id"]);
        $xmlMainFun->addCell($row["sala"]);
        $xmlMainFun->addCell(db_to_date($row["data"]));
        $xmlMainFun->addCell($row["hora"]);
        $xmlMainFun->endRow();
        $xCount++;
    }
}

$xmlMainFun->close();

?>