<?php
include_once("../../configuracao.php");

$acesso = new modulos_usuarios_funcionalidades(8);

$pColNames = array('Código', 'Data', 'Categoria');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'data',index:'data', width:200, align:'center'}",
                    "{name:'categoria',index:'categoria', width:200, align:'center'}");
$pSortName = 'data';

$mainGridFun = new modulos_global_grid('grdExamePratico', 'Datas de Exame Prático', 'modulos/examepratico/index.xml.php?status=A', $pColNames, $pColModel, $pSortName, true);

if ($acesso->getFuncionalidade(3) or $acesso->getFuncionalidade(4)) {
    $mainGridFun->eventOnDblClickRowAlterRow('id', 'modulos/examepratico/form.php');
}

$form = new modulos_global_form('frmExamePratico');

$data = date('d/m/Y');

$lstCategoria = null;
$lstCategoria["A"] = 'Categoria A';
$lstCategoria["B"] = 'Categoria B';

$form->divAlert();

$form->inputDate('fData', 'Data', $data, true);
$form->selectFixed('fCategoria', 'Categoria', true, $lstCategoria);

$form->divClear(1);

if ($acesso->getFuncionalidade(1)) {
    $form->buttonAdicionar('btnAddData', 'Nova data de Exame');
}
if ($acesso->getFuncionalidade(2)) {
    $form->buttonExc('btnExcData', 'Remover data do Exame', $mainGridFun->getGridName(), 'id', 'modulos/examepratico/index.php', 'modulos/examepratico/deletedata.php');
}
if ($acesso->getFuncionalidade(3) or $acesso->getFuncionalidade(4)) {
    $form->buttonCustom('btnAddAlunoExame', 'Adicionar alunos ao Exame', 'ui-icon-pencil', null, 'fg-button-red');
}
$form->buttonClose();

$form->divClear(1);

$form->checkbox('fStatus', 'Exibir somente exames ativos', null, true);

$form->divClear(1);

$form->close();

$mainGridFun->drawGrid();
?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#fStatus").click(function(){
            carregarGrid();
        });
        $("#btnAddData").click(function(event){
            $.post('modulos/examepratico/savedata.php',
            {
                data : $("#fData").val(),
                categoria : $("#fCategoria").val(),
                status : 'A'
            }, function(data){
                if (data.retornoStatus == "save") {
                    carregarGrid();
                } else {
                    postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/examepratico/index.php', '<?php echo $form->getdivAlertName();  ?>');
                }
            }, "json");
            event.preventDefault();
        });
        $("#btnAddAlunoExame").click(function(event){
            var idSelRow = jQuery("#<?php echo $mainGridFun->getGridName(); ?>").jqGrid('getGridParam','selrow');
            if (idSelRow) {
                var ret = jQuery("#<?php echo $mainGridFun->getGridName(); ?>").jqGrid('getRowData', idSelRow);
                openAjax("modulos/examepratico/form.php?pId="+ret.id);
            } else {
                divAlertCustomBasic("<?php echo $form->getdivAlertName();  ?>","Selecione uma linha.");
            }
            event.preventDefault();
        });
    });
    function carregarGrid() {
        var vUrl = 'modulos/examepratico/index.xml.php';
        if ($("#fStatus").attr('checked')) {
            vUrl = vUrl + '?status=A';
        }
        jQuery("#<?php echo $mainGridFun->getGridName(); ?>").jqGrid('setGridParam',{url:vUrl}).trigger("reloadGrid");
    }
</script>