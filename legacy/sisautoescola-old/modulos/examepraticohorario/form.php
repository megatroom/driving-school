<?php
include_once("../../configuracao.php");

$pId = null;
if (isset($_GET['pId'])) {
    $pId = $_GET["pId"];
}

$horario = null;
if (isset ($pId)) {
    $mysql = new modulos_global_mysql();
    $fieldList = $mysql->select('*', 'examepraticohorario', "id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }
}

$form = new modulos_global_form('Horario');

$form->buttonSave("saveHorario");
$form->buttonCancel("closeSala",null,"modulos/examepraticohorario/index.php");
$form->divClear(1);

$form->divAlert();

$form->startFieldSet('salas_basic');
$form->inputTime('fhorario', 'Horário', $horario);
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#saveHorario").click(function(event){
        $.post(
            "modulos/examepraticohorario/save.php",
            { id : '<?php echo $pId; ?>',
                horario : $("#fhorario").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/examepraticohorario/index.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
</script>