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

$pColNames = array('Código', 'Descrição', 'Placa');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'descricao',index:'descricao', width:400}",
                    "{name:'placa',index:'placa', width:100}");
$pSortName = 'descricao';

$mainGridFun = new modulos_global_grid('mainCarros', 'Carros', 'modulos/carrosfun/index.xml.php', $pColNames, $pColModel, $pSortName, true);

$mainGridFun->eventOnDblClickRowAlterRow('id', 'modulos/carrosfun/form.php');

$form = new modulos_global_form('Carros');

$form->divAlert();

$form->buttonAlt('bAltCarro','Selecionar Carro',$mainGridFun->getGridName(), 'id', 'modulos/carrosfun/form.php');
$form->buttonClose($pIdClose, "bCloseCarroFun");

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