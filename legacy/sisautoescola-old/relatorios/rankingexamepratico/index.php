<?php
include_once("../../configuracao.php");

$form = new modulos_global_form('RelRankingExamePratico');

$form->divAlert();

$form->startFieldSet('fdRelRankingFiltro', 'Filtro');
$form->inputDate('fDataI', 'Data Inicial', '01'.date('/m/Y'), true);
$form->inputDate('fDataF', 'Data Final', date('t/m/Y'), true);
$form->endFieldSet();

$form->startFieldSet('fdRelRankingTipo', 'Tipo de Relatório');
$form->checkbox('chckRanking', 'Ranking de Aprovados', "1", true);
$form->checkbox('chckPorcentagem', 'Ranking de Aproveitamento', "2", true);
$form->checkbox('chckTotal', 'Total de Cada Resultado', "3", true);

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
        var datai = "&datai=" + $("#fDataI").val();
        var dataf = "&dataf=" + $("#fDataF").val();
        var vTipo1 = "&tipo1=N";
        if ($("#chckRanking").attr('checked')) {
            vTipo1 = "&tipo1=S";
        }
        var vTipo2 = "&tipo2=N";
        if ($("#chckPorcentagem").attr('checked')) {
            vTipo2 = "&tipo2=S";
        }
        var vTipo3 = "&tipo3=N";
        if ($("#chckTotal").attr('checked')) {
            vTipo3 = "&tipo3=S";
        }
        openRelatorio("relatorios/rankingexamepratico/emissao.php?"+
            vTipo+datai+dataf+vTipo1+vTipo2+vTipo3);
    }
    function mensagemAlert(pTexto) {
        divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>", pTexto);
    }
</script>