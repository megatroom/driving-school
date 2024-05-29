<?php
ob_start(); session_start(); ob_end_clean();

include_once("../../configuracao.php");

$mysql = new modulos_global_mysql();

$idusuario = $_SESSION["IDUSUARIO"];

$dataCorrente = $mysql->getCurrentDate();

$dataInicioCaixa = $mysql->getValue(
        "valor",
        null,
        "sistema",
        "campo = 'datainiciocaixa'");

if ($dataInicioCaixa == false or !isset ($dataInicioCaixa) or $dataInicioCaixa == "") {
    echo "<h3>A data de início do caixa não foi definida nas configurações do sistema.</h3>";
    exit;
}

$dataCorrenteDT = new DateTime($dataCorrente);
$dataInicioCaixaDT = new DateTime($dataInicioCaixa);

if ($dataCorrenteDT < $dataInicioCaixaDT) {
    echo "<h3>A data de início do caixa é maior que a data atual (".$dataCorrenteDT->format('d/m/Y').").</h3>";
    exit;
}

$countexistente = $mysql->getValue(
                        'count(id) as total',
                        'total',
                        'caixa',
                        "idusuario = '".$idusuario."' and data = CURDATE()");
if ($countexistente > 0) {
    echo "<h2>Caixa já fechado para hoje (".db_to_date($dataCorrente).").</h2>";
    exit;
}

$totRecebHj = $mysql->getValue(
                        'sum(valor) as total',
                        'total',
                        'contasareceber',
                        "idusuario = '".$idusuario."' and data = CURDATE()");
//echo $mysql->getMsgErro();
if (!isset ($totRecebHj) or $totRecebHj == "") {
    $totRecebHj = 0;
} 

$frmCaixa = new modulos_global_form();

$frmCaixa->divAlert();

$excDialogId = $frmCaixa->divDialogOpen();
$pDialogNoFunction = null;
$pDialogNoFunction[] = '$(this).dialog("close");';
$pDialogYesFunction = null;
$pDialogYesFunction[] = '$(this).dialog("close");';
$pDialogYesFunction[] = 'fecharCaixa();';
$frmCaixa->divDialogAddButton('Não', $pDialogNoFunction);
$frmCaixa->divDialogAddButton('Sim', $pDialogYesFunction);
$frmCaixa->divDialogClose();

$frmCaixa->null('<div style="margin-top: 10px;font-size: 14pt;">');
$frmCaixa->null('Data Atual: '.db_to_date($dataCorrente));
$frmCaixa->null("<br /><br />");
$frmCaixa->null('Total Já Recebido Hoje: R$ '.db_to_float($totRecebHj));
$frmCaixa->null("<br /><br />");
$frmCaixa->inputDecimal('fTotCaixa', 'Total do Caixa');
$frmCaixa->null("<br /></div>");
$frmCaixa->buttonCustom('btnFecharCaixa', 'Fechar o Caixa', 'ui-icon-locked');

$frmCaixa->close();
?>
<br /><br /><br />
Ao fechar o caixa não é possível abri-lo novamente.
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnFecharCaixa").click(function(event){
            dialogModalMsg("<?php echo $frmCaixa->getdivDialogNameTitle($excDialogId); ?>", "<?php echo $frmCaixa->getdivDialogNameMsg($excDialogId) ?>", "Aviso", "Esta ação não poderá ser desfeita. \n Deseja realmente fechar o caixa?");
            event.preventDefault();
        });
    });
    function fecharCaixa() {
        $.post('modulos/caixa/save.php', { valor : $("#fTotCaixa").val() }, function(data){
            if (data.retornoStatus == "save") {
                novaAbaMenuPrincipal('0', 'modulos/caixa/index.php', "Caixa");
            } else {
                divAlertCustomBasic('<?php echo $frmCaixa->getdivAlertName(); ?>', data.msg);
            }
        }, "json");
    }
</script>