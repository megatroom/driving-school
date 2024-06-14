<?php
include_once("../../configuracao.php");

$page  = $_GET['page'];
$limit = $_GET['rows'];
$sidx  = $_GET['sidx'];
$sord  = $_GET['sord'];

$pIdTurno = $_GET['pIdTurno'];

if (!isset ($sidx) or $sidx == "") {
    $sidx = '1';
}
if (!isset ($sord) or $sord == "") {
    $sord = 'asc';
}

$wh = null;
$where = null;
$searchOn = Strip($_GET['_search']);
$wh[] = "idturno = '".$pIdTurno."'";
if($searchOn=='true') {
	$sarr = Strip($_REQUEST);
	foreach( $sarr as $k=>$v) {
		switch ($k) {
			case 'horai':
                        case 'horaf':
				$wh[] = $k." LIKE '%".$v."%'";
				break;
			default:
				$wh[] = $k." = ".$v;
				break;
		}
	}
}
if (is_array($wh)) {
    $where = join(" and ", $wh);
}

$mysql = new modulos_global_mysql();

$countTable = $mysql->getValue('count(id) as total','total','expediente',$where);

$xmlMainFun = new modulos_global_gridxml($page, $limit, $countTable);

$rows = $mysql->select('id, diasemana, horai, horaf', 'expediente', $where, "order by $sidx $sord LIMIT ".$xmlMainFun->getStart()." , ".$xmlMainFun->getLimit()." ");

$xCount = 0;

if (is_array($rows)) {
    foreach ($rows as $row) {
        $xmlMainFun->startRow($xCount);
        $xmlMainFun->addCell($row["id"]);
        switch ($row["diasemana"]) {
            case 1:
                $xmlMainFun->addCell('Domingo');
                break;
            case 2:
                $xmlMainFun->addCell('Segunda');
                break;
            case 3:
                $xmlMainFun->addCell('Terça');
                break;
            case 4:
                $xmlMainFun->addCell('Quarta');
                break;
            case 5:
                $xmlMainFun->addCell('Quinta');
                break;
            case 6:
                $xmlMainFun->addCell('Sexta');
                break;
            case 7:
                $xmlMainFun->addCell('Sábado');
                break;
            default:
                $xmlMainFun->addCell('');
        }
        $xmlMainFun->addCell($row["horai"]);
        $xmlMainFun->addCell($row["horaf"]);
        $xmlMainFun->endRow();
        $xCount++;
    }
}

$xmlMainFun->close();

?>