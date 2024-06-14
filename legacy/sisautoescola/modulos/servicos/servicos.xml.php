<?php
include_once("../../configuracao.php");

$page  = $_GET['page'];
$limit = $_GET['rows'];
$sidx  = $_GET['sidx'];
$sord  = $_GET['sord'];

$pIdAluno = $_GET["pIdAluno"];

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
				$wh[] = "upper(".$k.") LIKE upper('%".$v."%')";
				break;
		}
	}
}
$wh[] = "a.idaluno = '".$pIdAluno."'";
$wh[] = "a.idtiposervico = b.id";
if (is_array($wh)) {
    $where = join(" and ", $wh);
}

$mysql = new modulos_global_mysql();

$countTable = $mysql->getValue('count(a.id) as total','total','alunoservico a, tiposervicos b',$where);

//echo $mysql->getMsgErro()."<br>";
//echo "$page : $limit : $countTable";
$xmlMainFun = new modulos_global_gridxml($page, $limit, $countTable);

$rows = $mysql->select(
        'a.id, a.data, b.descricao, a.qtaulaspraticas, a.qtaulasteoricas, a.valor, a.desconto',
        'alunoservico a, tiposervicos b',
        $where,
        "order by $sidx $sord LIMIT ".$xmlMainFun->getStart()." , ".$xmlMainFun->getLimit()." ");
//echo $mysql->getMsgErro()."<br>";

$xCount = 0;

if (is_array($rows)) {
    foreach ($rows as $row) {
        $xmlMainFun->startRow($xCount);
        $xmlMainFun->addCell($row["id"]);
        $xmlMainFun->addCell(db_to_date($row["data"]));
        $xmlMainFun->addCell($row["descricao"]);
        $xmlMainFun->addCell($row["qtaulaspraticas"]);
        $xmlMainFun->addCell($row["qtaulasteoricas"]);
        $xmlMainFun->addCell(db_to_float($row["valor"]));
        $xmlMainFun->addCell(db_to_float($row["desconto"]));
        $xmlMainFun->endRow();
        $xCount++;
    }
}

//echo $mysql->getMsgErro();

$xmlMainFun->close();

?>