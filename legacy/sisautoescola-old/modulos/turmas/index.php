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

$acesso = new modulos_usuarios_funcionalidades(5);

$pColNames = array('Código', 'Sala', 'Data', 'Hora');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'sala',index:'sala', width:300}",
                    "{name:'data',index:'data', width:100}",
                    "{name:'hora',index:'hora', width:100}");
$pSortName = 'descricao';

$mainGridFun = new modulos_global_grid('mainTurmas', 'Turmas', 'modulos/turmas/index.xml.php?pFechada=0', $pColNames, $pColModel, $pSortName, true);

$mainGridFun->eventOnDblClickRowAlterRow('id', 'modulos/turmas/form.php');

$form = new modulos_global_form('Turmas');

$form->divAlert();

if ($acesso->getFuncionalidade(1)) {
    $form->buttonNew('bNewCarro','Nova Turma','modulos/turmas/form.php');
}
if ($acesso->getFuncionalidade(2)) {
    $form->buttonAlt('bAltCarro',null,$mainGridFun->getGridName(), 'id', 'modulos/turmas/form.php');
}
if ($acesso->getFuncionalidade(3)) {
    $form->buttonExc('bExcCarro',null,$mainGridFun->getGridName(), 'id', 'modulos/turmas/index.php', 'modulos/turmas/delete.php');
}
$form->buttonClose($pIdClose, "bCloseCarro");

$form->divClear(1);

$form->checkbox('fFechada', 'Exibir somente turmas abertas', null, true);

$form->close();

?>
<br />
<?php

$mainGridFun->drawGrid();

?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#fFechada").click(function(){
            carregarGrid();
        });
    });
    function carregarGrid() {
        var vUrl = 'modulos/turmas/index.xml.php';
        if ($("#fFechada").attr('checked')) {
            vUrl = vUrl + '?pFechada=0';
        }
        jQuery("#<?php echo $mainGridFun->getGridName(); ?>").jqGrid('setGridParam',{url:vUrl}).trigger("reloadGrid");
    }
<?php

if (isset ($type_msg) and strlen($type_msg) > 0) {
    if ($type_msg == "save" or $type_msg == "delete") {
        echo "postSucess('".$form->getdivAlertName()."','".$type_msg."');";
    }
}

?>
</script>