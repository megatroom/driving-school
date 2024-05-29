<?php
include_once("../../configuracao.php");

$pId = null;
if (isset($_GET["pId"])) {
    $pId = $_GET["pId"];
}

$descricao = null;

if (isset ($pId)) {
    $mysql = new modulos_global_mysql();
    $fieldList = $mysql->select('*', 'tiposagendamentos', "id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }
}

$form = new modulos_global_form('Funcoes');

$form->buttonSave("saveTipoAgend");
$form->buttonCancel("closeTipoAgend",null,"modulos/tipoagendamento/index.php");
$form->divClear(1);

$form->divAlert();

$form->inputHidden('fidfuncao',$pId);

$form->startFieldSet('funcao_basic');
$form->inputText('fdescricao', 'Descrição', $descricao, "30", true, "300px");
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#saveTipoAgend").click(function(event){
        $.post(
            "modulos/tipoagendamento/save.php",
            { id : $("#fidfuncao").val(),
                descricao : $("#fdescricao").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/tipoagendamento/index.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
</script>