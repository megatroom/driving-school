<?php
include_once("../../configuracao.php");

$pId = $_GET["pId"];
?>
<style type="text/css">
.cssTotalAlunoAulaTeorica {
    font-weight: bold;
}
</style>
<?php

$mysql = new modulos_global_mysql();

$sala = '';

if (isset ($pId)) {
    $fieldList = $mysql->select('*', 'vturmas', "id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }

    if (isset ($data)) {
        $data = db_to_date($data);
    }
    
    $rows = $mysql->select('descricao', 'salas', "id = '".$idsala."'", null, 'descricao');
    if (is_array($rows)) {
        foreach ($rows as $row) {
            $sala = $row["descricao"];
        }
    }

} else {
    echo '<h2>Escolha a turma!</h2>';
    exit;
}

$form = new modulos_global_form('Turmas');

$form->inputHidden('fidcarro',$pId);

$form->startFieldSet('aultateorica_basic');
$form->inputTextStatic('fSala', 'Sala', $sala, false, "450px");
$form->inputTextStatic('fdata', 'Data Início', $data, true);
$form->inputTextStatic('fhora', 'Hora', $hora, true);
$form->inputTextStatic('fqtdalunos', 'Qtd. Alunos', $qtdalunos, false);
$form->inputTextStatic('fFuncionario', 'Funcionário', $funcionario, false, "450px");
$form->endFieldSet();

$form->divAlert();

$form->startFieldSet('aultateorica_alunos', 'Alunos');
$form->inputTextStaticLookUp('alunos', 'fAluno', 'fIdAluno', 'Aluno', 'bCnsAluno');
$form->null('<br />');
$form->buttonAdicionar('bAddAluno');
$form->buttonCustom('bExcAluno', 'Excluir', 'ui-icon-trash');
$form->buttonImprimir('btnImprimir');
$form->null('<br /><br /><br /><div id="tblAlunosAultaTeorica"></div><br /><br />');
$form->endFieldSet();

$form->buttonCancel("closeTurma","Voltar","modulos/aulasteoricas/index.php");

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#btnImprimir").click(function(event){
        emitirRelatorio(2);
        event.preventDefault();
    });
    $("#bAddAluno").click(function(event){
        divCloseAlert('<?php echo $form->getdivAlertName(); ?>');
        $.post(
            "modulos/aulasteoricas/save.php",
            {
                idaluno : $("#fIdAluno").val(),
                idturma : '<?php echo $pId; ?>'
            },
            function(data){
                if (data.retornoStatus != "save") {
                    divAlertCustomBasic('<?php echo $form->getdivAlertName(); ?>', data.msg);
                } else {
                    if (data.msg != null && data.msg != "") {
                        divAlertCustomBasic('<?php echo $form->getdivAlertName(); ?>', data.msg);
                    }
                }
                carregarTabelaAlunos();
            }, "json");
        event.preventDefault();
    });
    $("#bExcAluno").click(function(){
        divCloseAlert('<?php echo $form->getdivAlertName(); ?>');
        $('tr').each(function(){
            if ($(this).hasClass('ui-state-highlight')) {
                $.post(
                    'modulos/aulasteoricas/delete.php',
                    { id : $(this).children().children().val() },
                    function(data) {
                        carregarTabelaAlunos();
                    });
            }
        });
        event.preventDefault();
    });
    $("#selRowAulaTeorica").live('click', function(event){
        $('tr').each(function(){
            $(this).removeClass('ui-state-highlight');
        });
        objetoLinkSelRow = $(this);
        objetoLinkSelRow.parent().parent().addClass('ui-state-highlight');
        event.preventDefault();
    });
    carregarTabelaAlunos();
});
function carregarTabelaAlunos() {
    $.post(
        "modulos/aulasteoricas/tabela.php",
        {
            idturma: '<?php echo $pId; ?>',
            qtdaluno: '<?php echo $qtdalunos; ?>'
        },
        function(data){
            $("#tblAlunosAultaTeorica").html(data);
        });
}
function emitirRelatorio(pTipoRel) {
    var vTipoRel = "tiporel="+pTipoRel;
    var idturma = "&idturma=<?php echo $pId; ?>";

    openRelatorio("relatorios/aulasteoricas/emissao.php?"+vTipoRel+idturma);
}
</script>