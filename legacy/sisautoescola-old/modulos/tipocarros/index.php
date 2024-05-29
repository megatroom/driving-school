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

$acesso = new modulos_usuarios_funcionalidades(18);

$pColNames = array('Código', 'Descrição', 'Comissão (R$)');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'descricao',index:'descricao', width:400}",
                   "{name:'comissao',index:'comissao', width:100, align:'right'}");
$pSortName = 'descricao';

$mainGridFun = new modulos_global_grid('mainFuncoes', 'Tipos de Carros', 'modulos/tipocarros/index.xml.php', $pColNames, $pColModel, $pSortName, true);

if ($acesso->getFuncionalidade(2)) {
    $mainGridFun->eventOnDblClickRowAlterRow('id', 'modulos/tipocarros/form.php');
}

$form = new modulos_global_form('TipoCarros');

$form->divAlert();

if ($acesso->getFuncionalidade(1)) {
    $form->buttonNew('bNewTipoCarros','Novo Tipo de Carros','modulos/tipocarros/form.php');
}
if ($acesso->getFuncionalidade(2)) {
    $form->buttonAlt('bAltTipoCarros',null,$mainGridFun->getGridName(), 'id', 'modulos/tipocarros/form.php');
}
if ($acesso->getFuncionalidade(3)) {
    $form->buttonExc('bExcTipoCarros',null,$mainGridFun->getGridName(), 'id', 'modulos/tipocarros/index.php', 'modulos/tipocarros/delete.php');
}
$form->buttonClose($pIdClose, "bCloseTipoCarros");

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