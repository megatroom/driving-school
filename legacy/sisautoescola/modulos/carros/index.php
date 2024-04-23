<?php
include_once("../../configuracao.php");

$type_msg = NULL;
if (isset($_GET["type_msg"])) {
    $type_msg = $_GET["type_msg"];
}

$acesso = new modulos_usuarios_funcionalidades(11);

$pColNames = array('Código', 'Tipo', 'Descrição', 'Placa');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'tipo',index:'tipo', width:150}",
                   "{name:'descricao',index:'descricao', width:250}",
                    "{name:'placa',index:'placa', width:100}");
$pSortName = 'tipo,descricao';

$mainGridFun = new modulos_global_grid('mainCarros', 'Carros', 'modulos/carros/index.xml.php', $pColNames, $pColModel, $pSortName, true);

if ($acesso->getFuncionalidade(2)) {
    $mainGridFun->eventOnDblClickRowAlterRow('id', 'modulos/carros/form.php');
}

$form = new modulos_global_form('Carros');

$form->divAlert();

if ($acesso->getFuncionalidade(1)) {
    $form->buttonNew('bNewCarro','Novo Carro','modulos/carros/form.php');
}
if ($acesso->getFuncionalidade(2)) {
    $form->buttonAlt('bAltCarro',null,$mainGridFun->getGridName(), 'id', 'modulos/carros/form.php');
}
if ($acesso->getFuncionalidade(3)) {
    $form->buttonExc('bExcCarro',null,$mainGridFun->getGridName(), 'id', 'modulos/carros/index.php', 'modulos/carros/delete.php');
}

$pCloseId = 0;
if (isset($_GET["pCloseId"])) {
    $pCloseId = $_GET["pCloseId"];
}

$form->buttonClose($pCloseId, "bCloseCarro");

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