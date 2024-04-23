<?php
include_once("../../configuracao.php");

$type_msg = null;
if (isset($_GET["type_msg"])) {
    $type_msg = $_GET["type_msg"];
}

$pColNames = array('Código', 'Sala', 'Data', 'Hora');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'sala',index:'sala', width:300}",
                    "{name:'data',index:'data', width:100}",
                    "{name:'hora',index:'hora', width:100}");
$pSortName = 'descricao';

$mainGridFun = new modulos_global_grid('mainTurmas', 'Turmas', 'modulos/aulasteoricas/index.xml.php', $pColNames, $pColModel, $pSortName, true);

$mainGridFun->eventOnDblClickRowAlterRow('id', 'modulos/aulasteoricas/form.php');

$form = new modulos_global_form('Turmas');

$form->divAlert();

$form->buttonVisualizar('btnVisualizar');
$form->buttonImprimir('btnImprimir');
$form->buttonExcel('btnGerarExcel');
$form->buttonWord('btnGerarWord');

$form->close();

?>
<br /><br /><br />
<?php

$mainGridFun->drawGrid();

?>
<script type="text/javascript">
    $(document).ready(function(){
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
        var vTipoRel = "tiporel="+pTipoRel;

        var idSelRow = jQuery("#<?php echo $mainGridFun->getGridName(); ?>").jqGrid('getGridParam','selrow');
        if (idSelRow) {
            var ret = jQuery("#<?php echo $mainGridFun->getGridName(); ?>").jqGrid('getRowData', idSelRow);
        } else {
            mensagemAlert("Selecione uma turma!");
        }

        var idturma = "&idturma=" + ret.id;

        openRelatorio("relatorios/aulasteoricas/emissao.php?"+vTipoRel+idturma);
    }
</script>