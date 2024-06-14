<?php
include_once("../../configuracao.php");

$pId = null;
if (isset($_GET["pId"])) {
    $pId = $_GET["pId"];
}

$descricao   = null;
$valor       = null;
$diasavencer = null;

if (isset ($pId)) {
    $mysql = new modulos_global_mysql();
    $fieldList = $mysql->select('*', 'tiposervicos', "id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }
    $disable = 'readonly';
} else {
    $qtaulaspraticas = 0;
    $qtaulasteoricas = 0;
    $status = 'A';
    $disable = '';
}

$lstStatus = null;
$lstStatus["A"] = 'Ativo';
$lstStatus["I"] = 'Inativo';

$form = new modulos_global_form('TipoServicos');

$form->buttonSave("saveTipoServicos");
$form->buttonCancel("closeTipoServicos",null,"modulos/tiposervicos/index.php");
$form->divClear(1);

$form->divAlert();

$form->startFieldSet('funcao_basic');
$form->inputText('fdescricao', 'Descrição', $descricao, "100", false, "300px");
$form->inputInteiro('fQtAulasPraticas', 'Qtd. Aulas Práticas', $qtaulaspraticas, true, null, null, $disable);
$form->inputInteiro('fQtAulasTeoricas', 'Qtd. Aulas Teóricas', $qtaulasteoricas, false, null, null, $disable);
$form->inputDecimal('fvalor', 'Valor (R$)', $valor, true);
$form->selectFixed('fstatus', 'Status', false, $lstStatus, $status, "150px");
$form->divClear();
$form->inputInteiro('fDiasAVencer', 'Dias a Vencer', $diasavencer);
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#saveTipoServicos").click(function(event){
        $.post(
            "modulos/tiposervicos/save.php",
            { id : '<?php echo $pId; ?>',
                descricao : $("#fdescricao").val(),
                qtaulaspraticas : $("#fQtAulasPraticas").val(),
                qtaulasteoricas : $("#fQtAulasTeoricas").val(),
                valor : $("#fvalor").val(),
                status : $("#fstatus").val(),
                diasavencer : $("#fDiasAVencer").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/tiposervicos/index.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
</script>