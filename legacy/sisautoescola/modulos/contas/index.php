<?php
include_once("../../configuracao.php");

$type_msg = null;
if (isset($_GET["type_msg"])) {
    $type_msg = $_GET["type_msg"];
}

$pColNames = array('Código', 'Descrição');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'descricao',index:'descricao', width:500}");
$pSortName = 'descricao';

$mainGridFun = new modulos_global_grid('mainFuncoes', 'Funções', 'modulos/contas/index.xml.php', $pColNames, $pColModel, $pSortName, true);

$mainGridFun->eventOnDblClickRowAlterRow('id', 'modulos/contas/form.php');

$form = new modulos_global_form('Funcoes');

$form->divAlert();

$form->buttonNew('bNewFuncao','Nova Conta','modulos/contas/form.php');
$form->buttonAlt('bAltFuncao',null,$mainGridFun->getGridName(), 'id', 'modulos/contas/form.php');
$form->buttonExc('bExcFuncao',null,$mainGridFun->getGridName(), 'id', 'modulos/contas/index.php', 'modulos/contas/delete.php');
$form->buttonClose($_GET["pCloseId"], "bCloseFuncao");

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