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
$wh[] = "c.idfuncionario = a.id";
$wh[] = "a.idpessoa = b.id";
$wh[] = "c.idcarro = '". $_GET["pIdCarro"] ."'";
$searchOn = Strip($_GET['_search']);
if($searchOn=='true') {
	$sarr = Strip($_REQUEST);
	foreach( $sarr as $k=>$v) {
		switch ($k) {
			case 'matricula':
                        case 'nome':
                        case 'telefone':
                        case 'celular':
				$wh[] = $k." LIKE '%".$v."%'";
				break;
			default:
				$wh[] .= $k." = ".$v;
				break;
		}
	}
}
if (is_array($wh)) {
    $where = join(" and ", $wh);
}

$mysql = new modulos_global_mysql();

$countTable = $mysql->getValue('count(a.id) as total','total','carrofuncionario c, funcionarios a, pessoas b',$where);

$xmlMainFun = new modulos_global_gridxml($page, $limit, $countTable);

$rows = $mysql->select('c.id, a.matricula, b.nome, c.data, c.hora', 'carrofuncionario c, funcionarios a, pessoas b', $where, "order by $sidx $sord LIMIT ".$xmlMainFun->getStart()." , ".$xmlMainFun->getLimit()." ");

$xCount = 0;

foreach ($rows as $row) {
    $xmlMainFun->startRow($xCount);
    $xmlMainFun->addCell($row["id"]);
    $xmlMainFun->addCell($row["matricula"]);
    $xmlMainFun->addCell($row["nome"]);
    $xmlMainFun->addCell(db_to_date($row["data"]));
    $xmlMainFun->addCell($row["hora"]);
    $xmlMainFun->endRow();
    $xCount++;
}

$xmlMainFun->close();

?>