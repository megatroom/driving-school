<?php
include_once("../../configuracao.php");

$pId = 0;
$descricao = null;
$status = null;
if (isset ($_GET["pId"])) {
    $pId = $_GET["pId"];
    $mysql = new modulos_global_mysql();
    $fieldList = $mysql->select('*', 'declaracoes', "id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }
}

$form = new modulos_global_form('frmRelDeclaracao');

$lstStatus = null;
$lstStatus["A"] = 'Ativo';
$lstStatus["I"] = 'Inativo';

$form->buttonSave('btnSalvar');
$form->buttonCancel('btnCancelar', null, 'relatorios/declaracao/edicao.php');

$form->divClear(1);

$form->startFieldSet('fdBasicDecla');
$form->inputText('fDescricao', 'Descrição', $descricao, '100', true, '400px');
$form->selectFixed('fStatus', 'Status', false, $lstStatus, $status);
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#btnSalvar").click(function(event){
        $.post(
            "relatorios/declaracao/savedeclara.php",
            { id : '<?php echo $pId; ?>',
                descricao : $("#fDescricao").val(),
                status : $("#fStatus").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'relatorios/declaracao/edicao.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
</script>