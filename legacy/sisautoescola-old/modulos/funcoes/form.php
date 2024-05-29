<?php
include_once("../../configuracao.php");

$pId = null;
if (isset($_GET["pId"])) {
    $pId = $_GET["pId"];
}

$descricao = '';

if (isset ($pId)) {
    $mysql = new modulos_global_mysql();
    $fieldList = $mysql->select('*', 'funcoes', "id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }
}

$form = new modulos_global_form('Funcoes');

$form->buttonSave("saveFuncao");
$form->buttonCancel("closeFuncao",null,"modulos/funcoes/index.php");
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
    $("#saveFuncao").click(function(event){
        $.post(
            "modulos/funcoes/save.php",
            { id : $("#fidfuncao").val(),
                descricao : $("#fdescricao").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/funcoes/index.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
</script>