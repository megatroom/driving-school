<?php
include_once("../../configuracao.php");

$form = new modulos_global_form('frmRelDeclaracao');

$pColNames = array('Código', 'Descrição', 'Status');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'descricao',index:'descricao', width:400}",
                   "{name:'status',index:'status',align:'center', width:100}");
$pSortName = 'descricao';

$mainGridFun = new modulos_global_grid('mainDeclara', 'Declarações', 'relatorios/declaracao/edicao.xml.php', $pColNames, $pColModel, $pSortName, false);

$form->divAlert();

$form->buttonNew('btnNovo', null, 'relatorios/declaracao/form.php');
$form->buttonAlt('btnAlterar', null, $mainGridFun->getGridName(), 'id', 'relatorios/declaracao/form.php');
$form->buttonCustom('btnEditor', 'Editar Conteúdo', 'ui-icon-note');
$form->buttonCancel('btnCancelar', 'Voltar', 'relatorios/declaracao/index.php');

$form->close();

?>
<br /><br /><br />
<?php

$mainGridFun->drawGrid();

?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnEditor").click(function(event){
            var idSelRow = jQuery("#<?php echo $mainGridFun->getGridName(); ?>").jqGrid('getGridParam','selrow');
            if (idSelRow) {
                var ret = jQuery("#<?php echo $mainGridFun->getGridName(); ?>").jqGrid('getRowData', idSelRow);
                window.open("relatorios/declaracao/editor.php?iddeclaracao="+ret.id);
            } else {
                divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>","Selecione uma linha.");
            }
            event.preventDefault();
        });
    });
    
<?php
if (isset ($type_msg) and strlen($type_msg) > 0) {
    if ($type_msg == "save" or $type_msg == "delete") {
        echo "postSucess('".$form->getdivAlertName()."','".$type_msg."');";
    }
}

?>
</script>