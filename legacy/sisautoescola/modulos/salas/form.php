<?php
include_once("../../configuracao.php");

$pId = null;
if (isset($_GET["pId"])) {
    $pId = $_GET["pId"];
}

$descricao = null;

if (isset ($pId)) {
    $mysql = new modulos_global_mysql();
    $fieldList = $mysql->select('*', 'salas', "id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }
}

$form = new modulos_global_form('Salas');

$form->buttonSave("saveSala");
$form->buttonCancel("closeSala",null,"modulos/salas/index.php");
$form->divClear(1);

$form->divAlert();

$form->startFieldSet('salas_basic');
$form->inputText('fdescricao', 'Descrição', $descricao, "30", true, "300px");
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#saveSala").click(function(event){
        $.post(
            "modulos/salas/save.php",
            { id : '<?php echo $pId; ?>',
                descricao : $("#fdescricao").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/salas/index.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
</script>