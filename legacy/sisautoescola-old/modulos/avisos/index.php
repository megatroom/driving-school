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

$acesso = new modulos_usuarios_funcionalidades(6);

$pColNames = array('Código', 'Remetente', 'Destinatário', 'Data', 'Status', 'Prioridade');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'remetente',index:'remetente', width:100}",
                    "{name:'destinatario',index:'destinatario', width:100}",
                    "{name:'data',index:'data', width:50}",
                    "{name:'status',index:'status', width:50}",
                    "{name:'prioridade',index:'prioridade', width:50}");
$pSortName = 'data';

$mainGridFun = new modulos_global_grid('mainAvisos', 'Avisos', 'modulos/avisos/index.xml.php', $pColNames, $pColModel, $pSortName, true);

$mainGridFun->eventOnDblClickRowAlterRow('id', 'modulos/avisos/form.php');

$form = new modulos_global_form('Carros');

$form->divAlert();
if ($acesso->getFuncionalidade(1)) {
    $form->buttonNew('bNewAvisos','Novo Aviso','modulos/avisos/form.php');
}
if ($acesso->getFuncionalidade(2)) {
    $form->buttonAlt('bAltAvisos',null,$mainGridFun->getGridName(), 'id', 'modulos/avisos/form.php');
}
if ($acesso->getFuncionalidade(3)) {
    $form->buttonExc('bExcAvisos',null,$mainGridFun->getGridName(), 'id', 'modulos/avisos/index.php', 'modulos/avisos/delete.php');
}
$form->buttonClose($pIdClose, "bCloseCarro");

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