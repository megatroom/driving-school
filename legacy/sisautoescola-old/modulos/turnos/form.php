<?php
include_once("../../configuracao.php");

$pId = null;
if (isset($_GET["pId"])) {
    $pId = $_GET["pId"];
}

$idtipocarro = null;
$descricao   = null;
$duracaoaula = null;

$mysql = new modulos_global_mysql();

$lstTipos = null;
$tiposcarros = $fieldList = $mysql->select('id, descricao', 'tipocarros', null, null, "descricao");
if (is_array($tiposcarros)) {
    foreach ($tiposcarros as $tipo) {
        $lstTipos[$tipo["id"]] = $tipo["descricao"];
    }
} else {
    echo '<h2>Não há tipos de carros cadastrados</h2>';
}

if (isset ($pId)) {
    $fieldList = $mysql->select('*', 'turnos', "id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }
}

$form = new modulos_global_form('Carros');

$form->buttonSave("saveTurno");
$form->buttonCancel("closeTurno",null,"modulos/turnos/index.php");
$form->divClear(1);

$form->divAlert();

$form->startFieldSet('turno_basic');
$form->selectFixed('ftipo', 'Tipo', false, $lstTipos, $idtipocarro, "450px");
$form->inputText('fdescricao', 'Descrição', $descricao, "30", false, "450px");
$form->inputInteiro('fduracaoaula', 'Duração da Aula (Minutos)', $duracaoaula);
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#saveTurno").click(function(event){
        $.post(
            "modulos/turnos/save.php",
            { id : '<?php echo $pId; ?>',
                idtipocarro : $("#ftipo").val(),
                descricao : $("#fdescricao").val(),
                duracaoaula : $("#fduracaoaula").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/turnos/index.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
</script>