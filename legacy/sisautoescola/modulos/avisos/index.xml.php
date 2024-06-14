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
			case 'nome':
				$wh[] = "upper(coalesce(b.nome, d.nome, 'TODOS')) LIKE upper('%".$v."%')";
				break;
		}
	}
}
if (is_array($wh)) {
    $where = join(" and ", $wh);
}

$mysql = new modulos_global_mysql();

$countTable = $mysql->getValue('count(a.id) as total','total','vavisos a',$where);

$xmlMainFun = new modulos_global_gridxml($page, $limit, $countTable);

$rows = $mysql->select("a.*", 'vavisos a', $where, "order by $sidx $sord LIMIT ".$xmlMainFun->getStart()." , ".$xmlMainFun->getLimit()." ");

//echo $mysql->getMsgErro();

$xCount = 0;

if (is_array($rows)) {
    foreach ($rows as $row) {
        $xmlMainFun->startRow($xCount);
        $xmlMainFun->addCell($row["id"]);
        $xmlMainFun->addCell($row["remetente"]);
        $xmlMainFun->addCell($row["destinatario"]);
        $xmlMainFun->addCell(db_to_date($row["data"]));
        $xmlMainFun->addCell(avisos_status_to_str($row["status"]));
        $xmlMainFun->addCell(avisos_prioridade_to_str($row["prioridade"]));
        $xmlMainFun->endRow();
        $xCount++;
    }
}

$xmlMainFun->close();

?>