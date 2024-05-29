<?php
include_once("../../configuracao.php");
?>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<?php

$type_msg = NULL;
if (isset($_GET["type_msg"])) {
    $type_msg = $_GET["type_msg"];
}

$pColNames = array('Código', 'Descrição');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'descricao',index:'descricao', width:500}");
$pSortName = 'descricao';

$mainGrid = new modulos_global_grid('mainFuncoes', 'Grupos de Usuário', 'modulos/gruposusuario/index.xml.php', $pColNames, $pColModel, $pSortName, true);

$mainGrid->eventOnDblClickRowAlterRow('id', 'modulos/usuarios/acessotelas.php');

$form = new modulos_global_form('GruposUsuarios');

$form->divAlert();

$form->buttonCustom('btnSelectGrupoUser', 'Selecionar Grupo de Usuário', 'ui-icon-check');

$pCloseId = 0;
if (isset($_GET["pCloseId"])) {
    $pCloseId = $_GET["pCloseId"];
}
$form->buttonClose($pCloseId, "bCloseGruposUser");

$form->close();

?>
<br /><br /><br />
<?php

$mainGrid->drawGrid();

?>

<script type="text/javascript">

    $(document).ready(function(){
        $("#btnSelectGrupoUser").click(function(event){
            var idSelRow = jQuery("#<?php echo $mainGrid->getGridName(); ?>").jqGrid('getGridParam','selrow');
            if (idSelRow) {
                var ret = jQuery("#<?php echo $mainGrid->getGridName(); ?>").jqGrid('getRowData', idSelRow);
                openAjax('modulos/usuarios/acessotelas.php?pId='+ret.id);
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