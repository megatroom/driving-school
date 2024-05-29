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

$acesso = new modulos_usuarios_funcionalidades(14);

$pColNames = array('Código', 'Tipo', 'Descrição', 'Duração');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                    "{name:'tipo',index:'tipo', width:100}",
                    "{name:'descricao',index:'descricao', width:300}",
                    "{name:'duracaoaula',index:'duracaoaula', width:60}");
$pSortName = 'descricao';

$mainGridFun = new modulos_global_grid('mainTurnos', 'Turnos', 'modulos/turnos/index.xml.php', $pColNames, $pColModel, $pSortName, true);

$mainGridFun->eventOnDblClickRowAlterRow('id', 'modulos/turnos/form.php');

$form = new modulos_global_form('Turnos');

$form->divAlert();

if ($acesso->getFuncionalidade(1)) {
    $form->buttonNew('bNewTurno','Novo Turno','modulos/turnos/form.php');
}
if ($acesso->getFuncionalidade(2)) {
    $form->buttonAlt('bAltTurno',null,$mainGridFun->getGridName(), 'id', 'modulos/turnos/form.php');
}
if ($acesso->getFuncionalidade(3)) {
    $form->buttonExc('bExcTurno',null,$mainGridFun->getGridName(), 'id', 'modulos/turnos/index.php', 'modulos/turnos/delete.php');
}
$form->buttonClose($pIdClose, "bCloseTurno");

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