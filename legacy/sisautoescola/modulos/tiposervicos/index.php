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

$acesso = new modulos_usuarios_funcionalidades(17);

$lstExibir = null;
$lstExibir["0"] = "Somente os ativos";
$lstExibir["1"] = "Todos";

$pColNames = array('Código', 'Descrição', 'Valor');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'descricao',index:'descricao', width:400}",
                    "{name:'valor',index:'valor', width:100, align:'right'}");
$pSortName = 'descricao';

$mainGridFun = new modulos_global_grid('mainTipoServicos', 'Tipos de Serviços', 'modulos/tiposervicos/index.xml.php?exibir=0', $pColNames, $pColModel, $pSortName, true);

if ($acesso->getFuncionalidade(2)) {
    $mainGridFun->eventOnDblClickRowAlterRow('id', 'modulos/tiposervicos/form.php');
}

$form = new modulos_global_form('Funcoes');

$form->divAlert();

if ($acesso->getFuncionalidade(1)) {
    $form->buttonNew('bNewTipoServicos','Novo Tipo de Serviços','modulos/tiposervicos/form.php');
}
if ($acesso->getFuncionalidade(2)) {
    $form->buttonAlt('bAltTipoServicos',null,$mainGridFun->getGridName(), 'id', 'modulos/tiposervicos/form.php');
}
if ($acesso->getFuncionalidade(3)) {
    $form->buttonExc('bExcTipoServicos',null,$mainGridFun->getGridName(), 'id', 'modulos/tiposervicos/index.php', 'modulos/tiposervicos/delete.php');
}
$form->buttonClose($pIdClose, "bCloseFuncao");

$form->divClear(1);

$form->selectFixed('fExibir', 'Exibir', false, $lstExibir, 0, "150px");

$form->divClear(1);

$form->close();

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
        $("#fExibir").change(function(){
            carregarGrid();
        });
    });
    function carregarGrid() {
        var vUrl = 'modulos/tiposervicos/index.xml.php';
        vUrl = vUrl + '?exibir=' + $("#fExibir").val();
        jQuery("#<?php echo $mainGridFun->getGridName(); ?>").jqGrid('setGridParam',{url:vUrl}).trigger("reloadGrid");
    }
</script>