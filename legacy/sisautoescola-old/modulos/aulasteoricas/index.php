<?php
include_once("../../configuracao.php");

$type_msg = null;
if (isset($_GET["type_msg"])) {
    $type_msg = $_GET["type_msg"];
}
$pCloseId = null;
if (isset($_GET["pCloseId"])) {
    $pCloseId = $_GET["pCloseId"];
}

$pColNames = array('Código', 'Sala', 'Data', 'Hora');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'sala',index:'sala', width:300}",
                    "{name:'data',index:'data', width:100}",
                    "{name:'hora',index:'hora', width:100}");
$pSortName = 'data, hora';

$mainGridFun = new modulos_global_grid('mainTurmas', 'Turmas', 'modulos/aulasteoricas/index.xml.php', $pColNames, $pColModel, $pSortName, true);

$mainGridFun->eventOnDblClickRowAlterRow('id', 'modulos/aulasteoricas/form.php');

$form = new modulos_global_form('Turmas');

$form->divAlert();

$form->buttonAlt('bAltCarro','Selecionar Turma',$mainGridFun->getGridName(), 'id', 'modulos/aulasteoricas/form.php');
$form->buttonClose($pCloseId, "bCloseCarro");

$form->close();

?>
<br /><br /><br />
<?php

$mainGridFun->drawGrid();

?>