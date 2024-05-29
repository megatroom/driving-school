<?php
include_once("../../configuracao.php");

$pId = null;
if (isset($_GET["pId"])) {
    $pId = $_GET["pId"];
}

$descricao = null;

$vCaixa = false;
$vInstrutor = false;
if (isset ($pId)) {
    $mysql = new modulos_global_mysql();
    $fieldList = $mysql->select('*', 'origens', "id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }
    if ($caixa == 'S') {
        $vCaixa = true;
    }
    if ($instrutor == 'S') {
        $vInstrutor = true;
    }
}

$form = new modulos_global_form('Origens');

$form->buttonSave("saveOrigens");
$form->buttonCancel("closeOrigens",null,"modulos/origens/index.php");
$form->divClear(1);

$form->divAlert();

$form->inputHidden('fidfuncao',$pId);

$form->startFieldSet('funcao_basic');
$form->inputText('fdescricao', 'Descrição', $descricao, "30", false, "300px");
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#saveOrigens").click(function(event){
        $.post(
            "modulos/origens/save.php",
            { id : $("#fidfuncao").val(),
                descricao : $("#fdescricao").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/origens/index.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
</script>