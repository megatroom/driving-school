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

$acesso = new modulos_usuarios_funcionalidades(8);

$pColNames = array('Código', 'Data', 'Valor', 'Funcionário');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'data',index:'data', width:150, align:'center'}",
                   "{name:'valor',index:'valor', width:150, align:'right'}",
                   "{name:'funcionario',index:'funcionario', width:500}");
$pSortName = 'data';

$mainGridFun = new modulos_global_grid('mainFuncoes', 'Vales', 'modulos/vales/index.xml.php', $pColNames, $pColModel, $pSortName, true);

if ($acesso->getFuncionalidade(2)) {
    $mainGridFun->eventOnDblClickRowAlterRow('id', 'modulos/vales/form.php');
}

$form = new modulos_global_form('Vales');

$form->divAlert();

if ($acesso->getFuncionalidade(1)) {
    $form->buttonNew('bNewFuncao','Novo Vale','modulos/vales/form.php');
}
if ($acesso->getFuncionalidade(2)) {
    $form->buttonAlt('bAltFuncao',null,$mainGridFun->getGridName(), 'id', 'modulos/vales/form.php');
}
if ($acesso->getFuncionalidade(3)) {
    $form->buttonExc('bExcFuncao',null,$mainGridFun->getGridName(), 'id', 'modulos/vales/index.php', 'modulos/vales/delete.php');
}
$form->buttonClose($pIdClose, "bCloseFuncao");

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