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
			case 'data':
				$wh[] = "upper(".$k.") LIKE upper('%".$v."%')";
				break;
		}
	}
}
if (isset ($_GET["status"])) {
    $wh[] = "status = '".$_GET["status"]."'";
}
if (is_array($wh)) {
    $where = join(" and ", $wh);
}

$mysql = new modulos_global_mysql();

$countTable = $mysql->getValue('count(id) as total','total','examepratico',$where);

//echo $mysql->getMsgErro()."<br>";
$xmlMainFun = new modulos_global_gridxml($page, $limit, $countTable);

$rows = $mysql->select('id, data, categoria', 'examepratico', $where, "order by $sidx $sord LIMIT ".$xmlMainFun->getStart()." , ".$xmlMainFun->getLimit()." ");
//echo $mysql->getMsgErro()."<br>";

$xCount = 0;

if (is_array($rows)) {
    foreach ($rows as $row) {
        $xmlMainFun->startRow($xCount);
        $xmlMainFun->addCell($row["id"]);
        $xmlMainFun->addCell(db_to_date($row["data"]));
        $xmlMainFun->addCell(getCategoriaDesc($row["categoria"]));
        $xmlMainFun->endRow();
        $xCount++;
    }
}

$xmlMainFun->close();

function getCategoriaDesc($pCodCateg) {
    $retorno = "Categoria ".$pCodCateg;
    return $retorno;
}

?>