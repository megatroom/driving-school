<?php
include_once("../../configuracao.php");

$pIdAluno = $_POST["idaluno"];

$mysql = new modulos_global_mysql();

$rows = $mysql->select(
        'v.id, v.data, v.valor, v.desconto, v.valorpago, v.dtultimopagto, '.
        'v.tiposervico, v.valorapagar, v.status',
        'valunoservico v',
        "v.idaluno = '".$pIdAluno."'",
        null,
        'v.data desc, v.tiposervico');

$totalvalor = 0;
$totaldesconto = 0;
$totalpago = 0;
$totalapagar = 0;

?>
<table id="users" class="ui-widget ui-widget-content" cellpadding="5">
    <thead>
        <tr class="ui-widget-header ">
            <td colspan="10" align="center">Contas a Receber</td>
        </tr>
        <tr class="ui-widget-header ">
            <td align="center">Recibo</td>
            <td>Serviço</td>
            <td align="center">Valor (R$)</td>
            <td align="center">Desconto (R$)</td>
            <td align="center">Valor Pago (R$)</td>
            <td align="center">Data do Cadastrado</td>
            <td align="center">Último Pagamento</td>
            <td align="center">Valor a Pagar (R$)</td>
            <td align="center">Status</td>
            <td align="center">Recebimento</td>
        </tr>
    </thead>
    <tbody>
        <?php
        if (is_array($rows)) {
            foreach ($rows as $row) {
                if (strtoupper($row["status"]) == 'ABERTO') {
                    $corstatus = 'color:red;';
                    $receberTxt = 'Receber Conta';
                } else {
                    $corstatus = '';
                    $receberTxt = 'Visualizar Conta';
                }
                $totalapagar = $totalapagar + $row["valorapagar"];
                $totalvalor = $totalvalor + $row["valor"];
                $totaldesconto = $totaldesconto + $row["desconto"];
                $totalpago = $totalpago + $row["valorpago"];
                $receber = '<a href="#" onclick="javascript:openAjax(\'modulos/contasareceber/form.php?pId='.$row["id"].'&pReturn=Servicos\');">'.$receberTxt.'</a>';
        ?>
            <tr>
                <!--<td align="center"><?php echo db_to_date($row["data"]); ?></td>-->
                <td align="center"><a href="#" id="btnRecibo">Recibo</a><input type="hidden" value="<?php echo $row["id"]; ?>" /></td>
                <td><?php echo $row["tiposervico"]; ?></td>
                <td align="right"><?php echo db_to_float($row["valor"]); ?></td>
                <td align="right"><?php echo db_to_float($row["desconto"]); ?></td>
                <td align="right"><?php echo db_to_float($row["valorpago"]); ?></td>
                <td align="center"><?php echo db_to_date($row["data"]); ?></td>
                <td align="center"><?php echo db_to_date($row["dtultimopagto"]); ?></td>
                <td align="right" style="<?php echo $corstatus; ?>"><?php echo db_to_float($row["valorapagar"]); ?></td>
                <td align="center" style="<?php echo $corstatus; ?>"><?php echo $row["status"]; ?></td>
                <td align="center"><?php echo $receber; ?></td>
            </tr>
        <?php
            }
        }
        if ($totalapagar > 0) {
            $corstatus = 'color:red;';
        } else {
            $corstatus = '';
        }
        ?>
    </tbody>
    <tfoot>
        <tr class="ui-widget-header ">
            <td colspan="2">Total</td>
            <td align="right"><?php echo db_to_float($totalvalor); ?></td>
            <td align="right"><?php echo db_to_float($totaldesconto); ?></td>
            <td align="right"><?php echo db_to_float($totalpago); ?></td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="right" style="<?php echo $corstatus; ?>"><?php echo db_to_float($totalapagar); ?></td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
        </tr>
    </tfoot>
</table>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnRecibo").live('click', function(){
            var idalunoservico = "idalunoservico=" + $(this).next().val();
            var tiporel = "&tiporel=1";
            openRelatorio('relatorios/recibopagto/emissao.php?'+idalunoservico+tiporel);
            event.preventDefault();
        });
    });
</script>