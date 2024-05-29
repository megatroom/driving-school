<?php
include_once("../../configuracao.php");

$pId = null;
if (isset($_GET["pId"])) {
    $pId = $_GET["pId"];
}

$idsala        = null;
$data          = null;
$hora          = null;
$qtdalunos     = null;
$fechada       = null;
$idfuncionario = null;

$mysql = new modulos_global_mysql();

$lstSalas = null;
$rows = $mysql->select('id, descricao', 'salas', null, null, 'descricao');
if (is_array($rows)) {
    foreach ($rows as $row) {
        $lstSalas[$row["id"]] = $row["descricao"];
    }
}

if (isset ($pId)) {
    $fieldList = $mysql->select('*', 'turmas', "id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }

    if (isset ($data)) {
        $data = db_to_date($data);
    }
    if (isset ($fechada)) {
        if ($fechada == '1') {
            $fechada = true;
        } else {
            $fechada = false;
        }
    } else {
        $fechada = false;
    }
} else {
    $fechada = false;
}

$form = new modulos_global_form('Turmas');

$form->buttonSave("saveTurmas");
$form->buttonCancel("closeTurma",null,"modulos/turmas/index.php");
$form->divClear(1);

$form->divAlert();

$form->inputHidden('fidcarro',$pId);

$form->startFieldSet('turma_basic');
$form->selectFixed('fsala', 'Sala', FALSE, $lstSalas, $idsala, "460px");
$form->inputDate('fdata', 'Data Início', $data, true);
$form->inputTime('fhora', 'Hora', $hora, true);
$form->inputInteiro('fqtdalunos', 'Qtd. Alunos', $qtdalunos, false);
$form->inputTextStaticLookUp('funcionarios', 'ffuncionario', 'fidfuncionario', 'Funcionário', 'fbFun', null, false, null, null, null, $idfuncionario);
$form->checkbox('ffechada', 'Turma Fechada', NULL, $fechada);
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#saveTurmas").click(function(event){
        var vFechada = "0";
        if ($("#ffechada").attr("checked")) {
            vFechada = "1";
        }
        $.post(
            "modulos/turmas/save.php",
            { id : $("#fidcarro").val(),
                idsala : $("#fsala").val(),
                idfuncionario: $("#fidfuncionario").val(),
                data : $("#fdata").val(),
                hora : $("#fhora").val(),
                qtdalunos : $("#fqtdalunos").val(),
                fechada : vFechada
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/turmas/index.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
</script>