<?php
include_once("../../configuracao.php");

$pId = $_GET["pId"];

function getCategoriaDesc($pCodCateg) {
    $retorno = "Categoria ".$pCodCateg;
    return $retorno;
}

$mysql = new modulos_global_mysql();

$rows = $mysql->select('data, categoria, status', 'examepratico', "id = '".$pId."'");
foreach ($rows as $row) {
    $data = db_to_date($row["data"]);
    $categoria = getCategoriaDesc($row["categoria"]);
    $status = $row["status"];
}

$acesso = new modulos_usuarios_funcionalidades(4);

$rows = $mysql->select("id, carro", "vcarros", "datavenda is null", null, "carro");

$lstCarros = null;
$lstCarros[""] = "";
if (is_array($rows)) {
    foreach ($rows as $row) {
        $lstCarros[$row["id"]] = $row["carro"];
    }
}

$lstStatus = null;
$lstStatus["A"] = "Ativo";
$lstStatus["I"] = "Inativo";

$form = new modulos_global_form('frmCtrCaixa');

$form->buttonCancel("btnCancelExamePratico","Sair","modulos/examepratico/index.php");

$form->divClear(1);

$form->startFieldSet('ctrCaixaFiltro');
$form->inputTextStatic('fData', 'Data', $data, true);
$form->inputTextStatic('fCategoria', 'Categoria', $categoria, true);
$form->selectFixed('fCarro', 'Carro', true, $lstCarros);
$form->selectFixed('fStatus', 'Status', false, $lstStatus, $status);
$form->endFieldSet();

$form->divAlert();

$form->startFieldSet('fdAlunosEP');
$form->inputTextStaticLookUp('alunos', 'fAluno', 'fIdAluno', 'Aluno', 'bCnsAluno');
$form->null('<br />');
if ($acesso->getFuncionalidade(1)) {
    $form->buttonAdicionar('bAddAluno');
}
if ($acesso->getFuncionalidade(2)) {
    $form->buttonCustom('bExcAluno', 'Excluir', 'ui-icon-trash');
}
$form->null('<br /><br /><br />');
$form->null('<div id="tblExamePratico"></div>');
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#bAddAluno").click(function(event){
        divCloseAlert('<?php echo $form->getdivAlertName(); ?>');
        $.post(
            "modulos/examepratico/save.php",
            {
                idaluno : $("#fIdAluno").val(),
                idexamepratico : '<?php echo $pId; ?>',
                idcarro : $("#fCarro").val()
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
                    'modulos/examepratico/delete.php',
                    { id : $(this).children().children().val() },
                    function(data) {
                        if (data.retornoStatus != "delete") {
                            divAlertCustomBasic('<?php echo $form->getdivAlertName(); ?>', data.msg);
                        }
                        carregarTabelaAlunos();
                    }, "json");
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
    $("#fCarro").change(function(){
        carregarTabelaAlunos();
    });
    $("#fStatus").change(function(){
        $.post("modulos/examepratico/status.php", {
            id : '<?php echo $pId; ?>',
            status : $("#fStatus").val()
        }, function(){});
    });
    carregarTabelaAlunos();
});
function carregarTabelaAlunos() {
    $.post(
        "modulos/examepratico/tabela.php",
        {
            idexamepratico : '<?php echo $pId; ?>',
            idcarro : $("#fCarro").val()
        },
        function(data){
            $("#tblExamePratico").html(data);
        });
}
</script>