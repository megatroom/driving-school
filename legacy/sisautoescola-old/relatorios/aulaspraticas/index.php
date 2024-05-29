<?php
include_once("../../configuracao.php");

$mysql = new modulos_global_mysql();

$lstInstrutores = null;
$lstInstrutores[""] = "TODOS";
$rows = $mysql->select(
        'a.id, a.carro',
        'vcarros a ',
        "a.datavenda is null",
        null,
        'carro');
if (is_array($rows)) {
    foreach ($rows as $row) {
        $lstInstrutores[$row["id"]] = $row["carro"];
    }
}

$lstTurnos = null;
$turnos = $mysql->select('t.id, t.descricao', 'turnos t', null, null, 't.descricao');
if (is_array($turnos)) {
    foreach ($turnos as $turno) {
        $lstTurnos[$turno["id"]] = $turno["descricao"];
    }
}

$form = new modulos_global_form('EmisAulasPraticas');

$form->divAlert();

$form->startFieldSet('fdEmisAulasPraticas');
$form->inputDate('fData', 'Data', date('d/m/Y'), true);
$form->selectFixed('fTurno', 'Turno', false, $lstTurnos);
$form->divClear();
$form->selectFixed('fCarros', 'Carro', false, $lstInstrutores);
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
        var vTipoRel = "tipoRel="+pTipoRel;
        var idfuncionario = "&idcarro=" + $("#fCarros").val();
        var data = "&data=" + $("#fData").val();
        var turno = "&turno=" + $("#fTurno").val();
        openRelatorio("relatorios/aulaspraticas/emissao.php?"+vTipoRel+idfuncionario+data+turno);
    }
</script>