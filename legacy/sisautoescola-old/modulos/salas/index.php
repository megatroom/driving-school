<?php
include_once("../../configuracao.php");

$type_msg = null;
if (isset($_GET["type_msg"])) {
    $type_msg = $_GET["type_msg"];
}

$pIdClose = null;
if (isset($_GET["pCloseId"])) {
    $pIdClose = $_GET["pCloseId"];
}

$acesso = new modulos_usuarios_funcionalidades(13);

$pColNames = array('Código', 'Descrição');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'descricao',index:'descricao', width:500}");
$pSortName = 'descricao';

$mainGridFun = new modulos_global_grid('mainSalas', 'Salas', 'modulos/salas/index.xml.php', $pColNames, $pColModel, $pSortName, true);

if ($acesso->getFuncionalidade(2)) {
    $mainGridFun->eventOnDblClickRowAlterRow('id', 'modulos/salas/form.php');
}

$form = new modulos_global_form('Salas');

$form->divAlert();

if ($acesso->getFuncionalidade(1)) {
    $form->buttonNew('bNewSalas','Nova Sala','modulos/salas/form.php');
}
if ($acesso->getFuncionalidade(2)) {
    $form->buttonAlt('bAltSalas',null,$mainGridFun->getGridName(), 'id', 'modulos/salas/form.php');
}
if ($acesso->getFuncionalidade(3)) {
    $form->buttonExc('bExcSalas',null,$mainGridFun->getGridName(), 'id', 'modulos/salas/index.php', 'modulos/salas/delete.php');
}

$form->buttonClose($pIdClose, "bCloseSalas");

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