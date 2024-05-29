<?php
include_once("../../configuracao.php");

$form = new modulos_global_form('frmCtrCaixa');

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
        'relatorios/aulasalunos/index.xml.php',
        $pColNames,
        $pColModel,
        $pSortName,
        false);

$lstExibir = null;
$lstExibir["1"] = "Somente aulas futuras";
$lstExibir["2"] = "Todas as aulas";

$form->divAlert();

$form->startFieldSet('fdAulasAlunosAluno');
$form->nullArray($mainGridAluno->resultGrid());
$form->divClear(1);
$form->endFieldSet();

$form->startFieldSet('fdAulasAlunosAlunoPratica');
$form->checkbox('chckOpcoes', 'Aulas Práticas', '1', false, false);
$form->divClear(1);
$form->selectFixed('fExibir', 'Exibir', false, $lstExibir, "1", "300px");
$form->endFieldSet();

$form->startFieldSet('fdAulasAlunosTeorica');
$form->checkbox('chckOpcoes', 'Aulas Teóricas', '2', false, false);
$form->endFieldSet();

$form->buttonVisualizar('btnVisualizar');
$form->buttonImprimir('btnImprimir');
$form->buttonExcel('btnGerarExcel');
$form->buttonWord('btnGerarWord');
$form->buttonCustom('btnEditar', 'Editar Conteúdo.', 'ui-icon-note');

$form->divClear(3);

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
        $("#btnEditar").click(function(event){
            openAjax('relatorios/aulasalunos/opcao.php');
            event.preventDefault();
        });
    });
    function emitirRelatorio(pTipoRel) {

        var idSelRow = jQuery("#<?php echo $mainGridAluno->getGridName(); ?>").jqGrid('getGridParam','selrow');
        if (idSelRow) {
            var ret = jQuery("#<?php echo $mainGridAluno->getGridName(); ?>").jqGrid('getRowData', idSelRow);
        } else {
            mensagemAlert("Selecione um aluno!");
        }
        var exibir = "&exibir=" + $("#fExibir").val();
        var idaluno = "&idaluno=" + ret.id;
        var vTipoRel = "tiporel="+pTipoRel;
        var vOpcoes = "";
        $(":checkbox").each(function(){
            if ($(this).is(':checked')) {
                if (vOpcoes == "") {
                    vOpcoes = "&opcoes=" + $(this).val();
                } else {
                    vOpcoes = vOpcoes + "," + $(this).val();
                }
            }
        });
        if (vOpcoes == "") {
            mensagemAlert("Selecione um tipo de relatório!");
        } else {
            openRelatorio("relatorios/aulasalunos/emissao.php?"+vTipoRel+idaluno+vOpcoes+exibir);
        }
    }
    function mensagemAlert(pTexto) {
        divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>", pTexto);
    }
</script>