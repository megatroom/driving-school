<?php
include_once("../../configuracao.php");

$form = new modulos_global_form('frmRelDeclaracao');

$form->startFieldSet('fdRelDecFiltro');
$form->inputTextStaticLookUp('alunos', 'fAluno', 'fIdAluno', 'Aluno', 'bAluno', null, false, null, null, null, $parIdAluno);
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
        var vTipoRel = "tiporel="+pTipoRel;
        var idaluno = "&idaluno=" + $("#fIdAluno").val();
        openRelatorio("relatorios/declaracao/emissao.php?"+vTipoRel+idaluno);
    }
</script>