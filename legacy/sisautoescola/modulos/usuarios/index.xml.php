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
$searchOn = Strip($_GET['_search']);
if($searchOn=='true') {
	$sarr = Strip($_REQUEST);
	foreach( $sarr as $k=>$v) {
		switch ($k) {
			case 'login':
                        case 'nome':
				$wh[] = "upper(".$k.") LIKE upper('%".$v."%')";
				break;
		}
	}
}
if (is_array($wh)) {
    $where = join(" and ", $wh);
}

$mysql = new modulos_global_mysql();

$countTable = $mysql->getValue('count(a.id) as total','total','usuarios a',$where);

$xmlMainFun = new modulos_global_gridxml($page, $limit, $countTable);

$rows = $mysql->select('u.id, u.login, coalesce(pf.nome, u.nome) as nome',
            'usuarios u '.
                'left join funcionarios f on u.idfuncionario = f.id '.
                'left join pessoas pf on f.idpessoa = pf.id ',
            $where,
            "order by $sidx $sord LIMIT ".$xmlMainFun->getStart()." , ".$xmlMainFun->getLimit()." ");

$xCount = 0;

foreach ($rows as $row) {
    $xmlMainFun->startRow($xCount);
    $xmlMainFun->addCell($row["id"]);
    $xmlMainFun->addCell($row["login"]);
    $xmlMainFun->addCell($row["nome"]);
    $xmlMainFun->endRow();
    $xCount++;
}

$xmlMainFun->close();

?>