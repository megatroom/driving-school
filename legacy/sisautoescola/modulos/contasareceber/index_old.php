<?php
include_once("../../configuracao.php");

$type_msg = $_GET["type_msg"];

$acesso = new modulos_usuarios_funcionalidades(19);

$pColNames = array('Código', 'Data', 'Aluno', 'Serviço', 'Valor', 'Valor a Pagar');
$pColModel = array("{name:'id',index:'id', hidden:false, width:80}",
                   "{name:'data',index:'data', width:60}",
                    "{name:'aluno',index:'aluno', width:100}",
                    "{name:'tiposervico',index:'tiposervico', width:100}",
                    "{name:'valor',index:'valor', width:60, align:'right'}",
                    "{name:'valorapagar',index:'valorapagar', width:60, align:'right'}",);
$pSortName = 'data';

$mainGridFun = new modulos_global_grid('mainFuncoes', 'Contas a Receber', 'modulos/contasareceber/index.xml.php?status=aberto', $pColNames, $pColModel, $pSortName, true);

if ($acesso->getFuncionalidade(3) or $acesso->getFuncionalidade(4)) {
    $mainGridFun->eventOnDblClickRowAlterRow('id', 'modulos/contasareceber/form.php');
}

$form = new modulos_global_form('ContasAPagar');

$form->divAlert();

if ($acesso->getFuncionalidade(1)) {
    $form->buttonNew('bNewContasAPagar','Nova Conta','modulos/contasareceber/form.php');
}
if ($acesso->getFuncionalidade(3) or $acesso->getFuncionalidade(4)) {
    $form->buttonAlt('bAltContasAPagar','Receber Conta',$mainGridFun->getGridName(), 'id', 'modulos/contasareceber/form.php');
}
if ($acesso->getFuncionalidade(2)) {
    $form->buttonExc('bExcContasAPagar',"Excluir Conta",$mainGridFun->getGridName(), 'id', 'modulos/contasareceber/index.php', 'modulos/contasareceber/delete.php');
}
$form->buttonClose($_GET["pCloseId"], "bCloseContasAPagar");

$form->null('<br /><br /><br />');
$form->checkbox('fPago', 'Exibir contas não pagas', null, true);

$form->close();

?>
<br />
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
    $(document).ready(function(){
        $("#fPago").click(function(event){
            carregarGrid();
        });
    });
    function carregarGrid() {
        var vUrl = 'modulos/contasareceber/index.xml.php';
        if ($("#fPago").attr('checked')) {
            vUrl = vUrl + '?status=aberto';
        }
        jQuery("#<?php echo $mainGridFun->getGridName(); ?>").jqGrid('setGridParam',{url:vUrl}).trigger("reloadGrid");
    }
</script>