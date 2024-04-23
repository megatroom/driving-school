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
			case 'descricao':
                        case 'placa':
				$wh[] = "upper(".$k.") LIKE upper('%".$v."%')";
				break;
		}
	}
}
$wh[] = "a.idtipocarro = b.id";
$wh[] = "a.datavenda is null";
if (isset ($_GET["idtipocarro"]) and is_numeric($_GET["idtipocarro"]) and $_GET["idtipocarro"] > 0) {
    $wh[] = "a.idtipocarro = '".$_GET["idtipocarro"]."'";
}
if (is_array($wh)) {
    $where = join(" and ", $wh);
}

$mysql = new modulos_global_mysql();

$countTable = $mysql->getValue('count(a.id) as total','total','carros a, tipocarros b',$where);

//echo $mysql->getMsgErro()."<br>";
$xmlMainFun = new modulos_global_gridxml($page, $limit, $countTable);

$rows = $mysql->select('a.id, a.descricao, a.placa, b.descricao as tipo', 'carros a, tipocarros b', $where, "order by $sidx $sord LIMIT ".$xmlMainFun->getStart()." , ".$xmlMainFun->getLimit()." ");
//echo $mysql->getMsgErro()."<br>";

$xCount = 0;

if (is_array($rows)) {
    foreach ($rows as $row) {
        $xmlMainFun->startRow($xCount);
        $xmlMainFun->addCell($row["id"]);
        $xmlMainFun->addCell($row["tipo"]);
        $xmlMainFun->addCell($row["descricao"]);
        $xmlMainFun->addCell($row["placa"]);
        $xmlMainFun->endRow();
        $xCount++;
    }
}

$xmlMainFun->close();

?>