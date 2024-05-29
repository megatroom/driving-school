<?php
include_once("../../configuracao.php");

$page  = $_GET['page'];
$limit = $_GET['rows'];
$sidx  = $_GET['sidx'];
$sord  = $_GET['sord'];

if (!isset ($sidx) or $sidx == "") {
    $sidx = '1';
}
if (!isset ($sord) or $sord == "") {
    $sord = 'asc';
}

$wh = null;
$where = null;
$searchOn = Strip($_GET['_search']);
$wh[] = "a.id = b.idcarro";
$wh[] = "b.idfuncionario = c.id";
$wh[] = "c.idpessoa = d.id";
$wh[] = "b.data = (select max(aux.data) from carrofuncionario aux where aux.idcarro = a.id)";
if($searchOn=='true') {
	$sarr = Strip($_REQUEST);
	foreach( $sarr as $k=>$v) {
		switch ($k) {
			case 'descricao':
                                $wh[] = "upper(a.descricao) LIKE upper('%".$v."%')";
				break;
                        case 'placa':
                                $wh[] = "upper(a.placa) LIKE upper('%".$v."%')";
				break;
                        case 'funcionario':
				$wh[] = "upper(d.nome) LIKE upper('%".$v."%')";
				break;
		}
	}
}

if (isset ($_GET["idtipocarro"]) and is_numeric($_GET["idtipocarro"]) and $_GET["idtipocarro"] > 0) {
    $wh[] = "a.idtipocarro = '".$_GET["idtipocarro"]."'";
}

if (is_array($wh)) {
    $where = join(" and ", $wh);
}

$mysql = new modulos_global_mysql();

$countTable = $mysql->getValue('count(b.id) as total','total','carros a, carrofuncionario b, funcionarios c, pessoas d',$where);

$xmlMainFun = new modulos_global_gridxml($page, $limit, $countTable);

$rows = $mysql->select("b.id, a.descricao, a.placa, d.nome as funcionario, concat(a.descricao,' - ',a.placa, ' - ', d.nome) as nomecompleto", 'carros a, carrofuncionario b, funcionarios c, pessoas d', $where, "order by $sidx $sord LIMIT ".$xmlMainFun->getStart()." , ".$xmlMainFun->getLimit()." ");

$xCount = 0;

if (is_array($rows)) {
    foreach ($rows as $row) {
        $xmlMainFun->startRow($xCount);
        $xmlMainFun->addCell($row["id"]);
        $xmlMainFun->addCell($row["nomecompleto"]);
        $xmlMainFun->addCell($row["descricao"]);
        $xmlMainFun->addCell($row["placa"]);
        $xmlMainFun->addCell($row["funcionario"]);        
        $xmlMainFun->endRow();
        $xCount++;
    }
}

$xmlMainFun->close();

?>