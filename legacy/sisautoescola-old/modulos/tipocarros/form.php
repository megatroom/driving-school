<?php
include_once("../../configuracao.php");

$pId = null;
if (isset($_GET["pId"])) {
    $pId = $_GET["pId"];
}

$descricao = null;

if (isset ($pId)) {
    $mysql = new modulos_global_mysql();
    $fieldList = $mysql->select('*', 'tipocarros', "id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }
} else {
    $comissao = 0;
}

$form = new modulos_global_form('TipoCarros');

$form->buttonSave("saveTipoCarros");
$form->buttonCancel("closeTipoCarros",null,"modulos/tipocarros/index.php");
$form->divClear(1);

$form->divAlert();

$form->inputHidden('fidfuncao',$pId);

$form->startFieldSet('funcao_basic');
$form->inputText('fdescricao', 'Descrição', $descricao, "30", false, "300px");
$form->inputDecimal('fcomissao', 'Comissão (R$)', db_to_float($comissao));
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#saveTipoCarros").click(function(event){
        $.post(
            "modulos/tipocarros/save.php",
            { id : $("#fidfuncao").val(),
                descricao : $("#fdescricao").val(),
                comissao : $("#fcomissao").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/tipocarros/index.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
</script>