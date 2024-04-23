<?php
include_once("../../configuracao.php");

$pId = NULL;
if (isset($_GET["pId"])) {
    $pId = $_GET["pId"];
} else if (isset ($_POST["pId"])) {
    $pId = $_POST["pId"];
}

// Variáveis externas ao módulos
$pIdAluno = NULL;
if (isset($_POST["pIdAluno"])) {
    $pIdAluno = $_POST["pIdAluno"];
}

$pReturn = NULL;
if (isset($_POST["pReturn"])) {
    $pReturn = $_POST["pReturn"];
}

if (isset ($pReturn)) {
    $urlRetorno = $pReturn;
} else {
    $urlRetorno = "modulos/agendamentos/index.php";
}

$mysql = new modulos_global_mysql();

$lstTipos = null;
$lstTipos[""] = "";
$rows = $mysql->select('id, descricao', 'tiposagendamentos', null, null, 'descricao');
if (is_array($rows)) {
    foreach ($rows as $row) {
        $lstTipos[$row["id"]] = $row['descricao'];
    }
}

$idtipoagendamento = NULL;
$data = NULL;
$hora = NULL;
if (isset ($pId)) {
    $fieldList = $mysql->select('*', 'vagendamentos', "id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }

    if (isset ($data)) {
        $data = db_to_date($data);
    }
} else {
    $aprovado = 'N';
}

if (isset ($pIdAluno) and is_numeric($pIdAluno) and $pIdAluno > 0) {
    $idaluno = $pIdAluno;
} else {
    $idaluno = null;
}

$lstAprovado = null;
$lstAprovado["N"] = "Não se aplica";
$lstAprovado["A"] = "Aprovado";
$lstAprovado["C"] = "Cancelado Aluno";
$lstAprovado["F"] = "Falta";
$lstAprovado["R"] = "Reprovado";
$lstAprovado["T"] = "Retirado";

$form = new modulos_global_form('Carros');

$form->buttonSave("saveAgendamento");
$form->buttonCancel("closeAgendamento",null,$urlRetorno);
$form->divClear(1);

$form->divAlert();

$form->inputHidden('fid',$pId);
$form->startFieldSet('carro_basic');
$form->inputTextStaticLookUp('alunos', 'fAluno', 'fIdAluno', 'Aluno', 'bCnsAluno', null, false, null, null, null, $idaluno);
$form->selectFixed('fTipo', 'Tipo de Agendamento', FALSE, $lstTipos, $idtipoagendamento, "520px");
$form->inputDate('fdata', 'Data', $data, true);
$form->inputTime('fhora', 'Hora', $hora, true);
$form->selectFixed('fAprovado', 'Resultado', false, $lstAprovado, $aprovado, "210px");
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#saveAgendamento").click(function(event){
        $.post(
            "modulos/agendamentos/save.php",
            { id : $("#fid").val(),
                idtipoagendamento : $("#fTipo").val(),
                data : $("#fdata").val(),
                hora : $("#fhora").val(),
                idaluno : $("#fIdAluno").val(),
                aprovado : $("#fAprovado").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg,'<?php echo $urlRetorno; ?>','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
</script>