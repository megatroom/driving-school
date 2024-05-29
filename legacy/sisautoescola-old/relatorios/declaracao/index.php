<?php
include_once("../../configuracao.php");

$acesso = new modulos_usuarios_funcionalidades(32);

$mysql = new modulos_global_mysql();

$rows = $mysql->select('id, descricao', 'declaracoes', "status = 'A'", null, 'descricao');

$lstDeclaracoes = null;
$lstDeclaracoes[""] = "";
if (is_array($rows)) {
    foreach ($rows as $row) {
        $lstDeclaracoes[$row["id"]] = $row["descricao"];
    }
}

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
$form->selectFixed('fDeclaracao', 'Declaração', false, $lstDeclaracoes);
$form->divClear(1);
$form->nullArray($mainGridAluno->resultGrid());
$form->endFieldSet();

if ($acesso->getFuncionalidade(1)) {
    $form->buttonCustom('btnEditor', 'Editar declarações', 'ui-icon-note');
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
            openAjax('relatorios/declaracao/edicao.php');
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
        var idSelRow = jQuery("#<?php echo $mainGridAluno->getGridName(); ?>").jqGrid('getGridParam','selrow');
        if (idSelRow) {
            var ret = jQuery("#<?php echo $mainGridAluno->getGridName(); ?>").jqGrid('getRowData', idSelRow);
            var idaluno = "&idaluno=" + ret.id;
            var iddeclaracao = "&iddeclaracao=" + $("#fDeclaracao").val();
            var vTipoRel = "tiporel="+pTipoRel;
            if ($("#fDeclaracao").val() == "") {
                mensagemAlert("Selecione um tipo de declaração!");
            } else {
                openRelatorio("relatorios/declaracao/emissao.php?"+vTipoRel+idaluno+iddeclaracao);
            }
        } else {
            mensagemAlert("Selecione um aluno!");            
        }        
    }
    function mensagemAlert(pTexto) {
        divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>", pTexto);
    }
</script>