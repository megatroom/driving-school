<?php
include_once("../../configuracao.php");

$pId = $_POST["id"];

$mysql = new modulos_global_mysql();

$valorCaixa = $mysql->getValue('valor', null, 'caixa', "id = '".$pId."'");
$valorConta = $mysql->getValue(
        'sum(a.valor) as total',
        'total',
        'contasareceber a',
        "a.data = (select x.data from caixa x where x.id = '".$pId."' and a.idusuario = x.idusuario)");
$diferenca = $valorCaixa - $valorConta;

$form = new modulos_global_form('frmAjuste');

$form->buttonSave('btnSaveAjuste');
$form->buttonCancel('btnCancelAjuste', null, 'modulos/ctrcaixa/index.php');

$form->divClear(1);

$form->divAlert();

$form->startFieldSet('fdAjuste');
$form->inputTextStatic('fValorCAR', 'Valor Conta a Receber', db_to_float($valorConta));
$form->inputTextStatic('fValorCaixa', 'ValorCaixa', db_to_float($valorCaixa));
$form->inputTextStatic('fDiferenca', 'Diferença', db_to_float($diferenca));
$form->inputDecimal('fAjuste', 'Ajuste');
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnSaveAjuste").click(function(){
            $.post(
                "modulos/ctrcaixa/saveajuste.php",
                {
                    id : '<?php echo $pId; ?>',
                    ajuste : $("#fAjuste").val()
                },
                function(data){
                    postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/ctrcaixa/index.php','<?php echo $form->getdivAlertName(); ?>');
                },
                "json");
        });
    });
</script>