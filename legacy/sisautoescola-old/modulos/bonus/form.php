<?php
include_once("../../configuracao.php");

$pId = $_GET["pId"];

$mysql = new modulos_global_mysql();

if (isset ($pId)) {
    $fieldList = $mysql->select('*', 'bonus', "id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }
    $data = db_to_date($data);
}

$rows = $mysql->select('a.id, a.nome', 'vfuncionarios a', null, null, 'a.nome');
$lstFun = null;
foreach ($rows as $row) {
    $lstFun[$row["id"]] = $row["nome"];
}

$form = new modulos_global_form('Vales');

$form->buttonSave("saveBonus");
$form->buttonCancel("closeBonus",null,"modulos/bonus/index.php");
$form->divClear(1);

$form->divAlert();

$form->startFieldSet('funcao_basic');
$form->inputDate('fData', 'Data', $data, true, null, null, null, null);
$form->inputDecimal('fValor', 'Valor', $valor, false, NULL, null, null);
$form->selectFixed('fFuncionario', 'Funcionário', false, $lstFun, $idfuncionario, "400px");
$form->textArea('fMotivo', 'Motivo', $motivo, NULL, false, "400px", null, 'rows="6"');
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#saveBonus").click(function(event){
        $.post(
            "modulos/bonus/save.php",
            { id : '<?php echo $pId; ?>',
                data : $("#fData").val(),
                idfuncionario : $("#fFuncionario").val(),
                valor : $("#fValor").val(),
                motivo : $("#fMotivo").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/bonus/index.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
</script>