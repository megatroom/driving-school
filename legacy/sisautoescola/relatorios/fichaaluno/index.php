<?php
include_once("../../configuracao.php");

$mysql = new modulos_global_mysql();

$pColNames = array('Código', 'Matrícula', 'Matrícula CFC', 'Nome', 'CPF');
$pColModel = array( "{name:'id',index:'id', hidden:true}",
                    "{name:'matricula',index:'matricula', width:100}",
                    "{name:'matriculacfc',index:'matriculacfc', width:100}",
                    "{name:'nome',index:'nome', width:300}",
                    "{name:'cpf',index:'cpf', width:100}");
$pSortName = 'nome';

$mainGridAluno = new modulos_global_grid(
        'mainGrdAluno',
        'Alunos',
        'relatorios/declaracao/index.xml.php',
        $pColNames,
        $pColModel,
        $pSortName,
        false);

$form = new modulos_global_form('frmRelDeclaracao');

$form->divAlert();

$form->startFieldSet('fdDeclara');
$form->nullArray($mainGridAluno->resultGrid());
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
        var idSelRow = jQuery("#<?php echo $mainGridAluno->getGridName(); ?>").jqGrid('getGridParam','selrow');
        if (idSelRow) {
            var ret = jQuery("#<?php echo $mainGridAluno->getGridName(); ?>").jqGrid('getRowData', idSelRow);
            var idaluno = "&idaluno=" + ret.id;
            var vTipoRel = "tiporel="+pTipoRel;
            openRelatorio("relatorios/fichaaluno/emissao.php?"+vTipoRel+idaluno);
        } else {
            mensagemAlert("Selecione um aluno!");
        }
    }
    function mensagemAlert(pTexto) {
        divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>", pTexto);
    }
</script>