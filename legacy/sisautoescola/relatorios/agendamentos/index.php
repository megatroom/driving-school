<?php
include_once("../../configuracao.php");

$mysql = new modulos_global_mysql();

$lstTipoAgendamentos = null;
$lstTipoAgendamentos[""] = "TODOS";
$agendamentos = $mysql->select(
        "t.id, t.descricao",
        "tiposagendamentos t",
        null,
        null,
        "descricao");
if (is_array($agendamentos)) {
    foreach ($agendamentos as $agendamento) {
        $lstTipoAgendamentos[$agendamento["id"]] = $agendamento["descricao"];
    }
}

$lstResultados = null;
$lstResultados[""] = "TODOS";
$lstResultados["N"] = "Não se aplica";
$lstResultados["AR"] = "Aprovados/Reprovados";
$lstResultados["A"] = "Aprovados";
$lstResultados["R"] = "Reprovados";

$form = new modulos_global_form('RelResultadosET');

$form->divAlert();

$form->startFieldSet('fdRelResultadosET');
$form->inputDate('fDataI', 'Data Inicial', '01'.date('/m/Y'), true);
$form->inputDate('fDataF', 'Data Final', date('t/m/Y'), true);
$form->selectFixed('fResultados', "Resultados", false, $lstResultados, "");
$form->divClear();
$form->selectFixed('fTipo', "Tipo de Agendamento", false, $lstTipoAgendamentos, "");
$form->endFieldSet();

$form->buttonVisualizar('btnVisualizar');
$form->buttonImprimir('btnImprimir');
$form->buttonExcel('btnGerarExcel');
$form->buttonWord('btnGerarWord');

$form->close();

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
        var datai = "&datai=" + $("#fDataI").val();
        var dataf = "&dataf=" + $("#fDataF").val();
        var tipo = "&tipo=" + $("#fTipo").val();
        var resultado = "&resultado=" + $("#fResultados").val();
        var vTipoRel = "tipoRel="+pTipoRel;
        openRelatorio("relatorios/agendamentos/emissao.php?"+vTipoRel+datai+dataf+tipo+resultado);
    }
    function mensagemAlert(pTexto) {
        divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>", pTexto);
    }
</script>