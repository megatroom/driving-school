<?php
include_once("../../configuracao.php");

$lstStatus = null;
$lstStatus[""] = "TODOS";
$lstStatus["A"] = "Somente os Ativo";
$lstStatus["I"] = "Somente os Inativos";

$ordem = null;
$ordem["1"] = "Código";
$ordem["2"] = "Descrição";

$form = new modulos_global_form('RelTipoServicos');

$form->divAlert();

$form->startFieldSet('fdOrdem', 'Ordenar por ');
$form->radiobutton('fOrdem', $ordem, "1");
$form->endFieldSet();

$form->startFieldSet('fdFiltro', 'Filtro');
$form->selectFixed('fStatus', 'Status', false, $lstStatus);
$form->endFieldSet();

$form->buttonVisualizar('btnVisualizarRelVales');
$form->buttonImprimir('btnImprimirRelVales');
$form->buttonExcel('btnGerarExcelRelVales');
$form->buttonWord('btnGerarWordRelVales');

$form->close();

?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnVisualizarRelVales").click(function(event){
            emitirRelatorio(1);
            event.preventDefault();
        });
        $("#btnImprimirRelVales").click(function(event){
            emitirRelatorio(2);
            event.preventDefault();
        });
        $("#btnGerarExcelRelVales").click(function(event){
            emitirRelatorio(3);
            event.preventDefault();
        });
        $("#btnGerarWordRelVales").click(function(event){
            emitirRelatorio(4);
            event.preventDefault();
        });
    });
    function emitirRelatorio(pTipo) {
        var vTipo = "tipo="+pTipo;
        var ordem = "&ordem=" + $("#fOrdem:checked").val();;
        var status = "&status=" + $("#fStatus").val();
        openRelatorio("relatorios/tiposervicos/emissao.php?"+vTipo+ordem+status);
    }
    function mensagemAlert(pTexto) {
        divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>", pTexto);
    }
</script>