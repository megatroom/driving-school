<?php
include_once("../../configuracao.php");

if (isset($_GET["type_msg"])) {
    $type_msg = $_GET["type_msg"];
}

$pCloseId = 0;
if (isset($_GET["pCloseId"])) {
    $pCloseId = $_GET["pCloseId"];
}

$pColNames = array('Código', 'Horário');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'hora',index:'hora', align:'center', width:500}");
$pSortName = 'hora';

$mainGridFun = new modulos_global_grid('mainSalas', 'Horários', 'modulos/examepraticohorario/index.xml.php', $pColNames, $pColModel, $pSortName, true);
$mainGridFun->eventOnDblClickRowAlterRow('id', 'modulos/examepraticohorario/form.php');

$form = new modulos_global_form('Salas');

$form->divAlert();

$form->buttonNew('bNewSalas','Novo Horário','modulos/examepraticohorario/form.php');
$form->buttonAlt('bAltSalas',null,$mainGridFun->getGridName(), 'id', 'modulos/examepraticohorario/form.php');
$form->buttonExc('bExcSalas',null,$mainGridFun->getGridName(), 'id', 'modulos/examepraticohorario/index.php', 'modulos/examepraticohorario/delete.php');

$form->buttonClose($pCloseId, "bCloseSalas");

$form->close();

?>
<br /><br /><br />
<?php

$mainGridFun->drawGrid();

?>

<script type="text/javascript">
<?php

if (isset ($type_msg) and strlen($type_msg) > 0) {
    if ($type_msg == "save" or $type_msg == "delete") {
        echo "postSucess('".$form->getdivAlertName()."','".$type_msg."');";
    }
}

?>
</script>