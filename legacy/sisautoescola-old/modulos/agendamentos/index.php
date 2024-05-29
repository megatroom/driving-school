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

$acesso = new modulos_usuarios_funcionalidades(7);

$pColNames = array('Código', 'Aluno', 'Descrição', 'Data', 'Hora');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'aluno',index:'aluno', width:170}",
                   "{name:'descricao',index:'descricao', width:150}",
                    "{name:'data',index:'data', width:100,align:'center'}",
                    "{name:'hora',index:'hora', width:80,align:'center'}");
$pSortName = 'aluno';

$mainGridFun = new modulos_global_grid('mainCarros', 'Agendamentos', 'modulos/agendamentos/index.xml.php', $pColNames, $pColModel, $pSortName, true);

$mainGridFun->eventOnDblClickRowAlterRow('id', 'modulos/agendamentos/form.php');

$form = new modulos_global_form('Carros');

$form->divAlert();

if ($acesso->getFuncionalidade(1)) {
    $form->buttonNew('bNewCarro','Novo Agendamento','modulos/agendamentos/form.php');
}
if ($acesso->getFuncionalidade(2)) {
    $form->buttonAlt('bAltCarro',null,$mainGridFun->getGridName(), 'id', 'modulos/agendamentos/form.php');
}
if ($acesso->getFuncionalidade(3)) {
    $form->buttonExc('bExcCarro',null,$mainGridFun->getGridName(), 'id', 'modulos/agendamentos/index.php', 'modulos/agendamentos/delete.php');
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