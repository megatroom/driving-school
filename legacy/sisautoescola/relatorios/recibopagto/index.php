<?php
include_once("../../configuracao.php");

$acesso = new modulos_usuarios_funcionalidades(41);

$pColNames = array('Código', 'Data', 'Aluno', 'Serviço', 'Valor', 'Valor a Pagar');
$pColModel = array("{name:'id',index:'id', hidden:false, width:80}",
                   "{name:'data',index:'data', width:60}",
                    "{name:'aluno',index:'aluno', width:100}",
                    "{name:'tiposervico',index:'tiposervico', width:100}",
                    "{name:'valor',index:'valor', width:60, align:'right'}",
                    "{name:'valorapagar',index:'valorapagar', width:60, align:'right'}",);
$pSortName = 'data';

$mainGridConta = new modulos_global_grid(
        'mainContasAReceber',
        'Contas a Receber',
        'relatorios/recibopagto/index.conta.xml.php',
        $pColNames,
        $pColModel,
        $pSortName,
        false);


$form = new modulos_global_form('frmRelDeclaracao');

$form->divAlert();

$form->startFieldSet('fdDeclara');
$form->nullArray($mainGridConta->resultGrid());
$form->endFieldSet();

if ($acesso->getFuncionalidade(1)) {
    $form->buttonCustom('btnEditor', 'Editar Recibo', 'ui-icon-note');
}
$form->buttonVisualizar('btnVisualizar');
$form->buttonImprimir('btnImprimir');
$form->buttonExcel('btnGerarExcel');
$form->buttonWord('btnGerarWord');

$form->close();

?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnEditor").click(function(event){
            window.open("relatorios/recibopagto/editor.php");
            event.preventDefault();
        });
        $("#btnVisualizar").click(function(event){
            emitirRelatorio(1);
            event.preventDefault();
        });
        $("#btnImprimir").click(function(event){
            emitirRelatorio(2);
            event.preventDefault();
        });
        $("#btnGerarExcel").click(function(event){
            emitirRelatorio(3);
            event.preventDefault();
        });
        $("#btnGerarWord").click(function(event){
            emitirRelatorio(4);
            event.preventDefault();
        });
    });
    function emitirRelatorio(pTipoRel) {
        var idSelConta = jQuery("#<?php echo $mainGridConta->getGridName(); ?>").jqGrid('getGridParam','selrow');
        if (idSelConta) {
            var ret = jQuery("#<?php echo $mainGridConta->getGridName(); ?>").jqGrid('getRowData', idSelConta);
            var idalunoservico = "&idalunoservico=" + ret.id;
            var vTipoRel = "tiporel="+pTipoRel;
            if ($("#fDeclaracao").val() == "") {
                mensagemAlert("Selecione um tipo de declaração!");
            } else {
                openRelatorio("relatorios/recibopagto/emissao.php?"+vTipoRel+idalunoservico);
            }
        } else {
            mensagemAlert("Selecione uma conta!");
        }
    }
    function mensagemAlert(pTexto) {
        divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>", pTexto);
    }
</script>