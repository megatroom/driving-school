<?php
include_once("../../configuracao.php");

$pIdAluno = $_POST["idaluno"];

$mysql = new modulos_global_mysql();

$rows = $mysql->select(
        'v.id, v.tipoagendamento, v.data, v.hora, v.aprovadotxt',
        'vagendamentos v',
        "v.idaluno = '".$pIdAluno."'",
        null,
        'v.data desc, v.hora desc');

?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#btnEdtAgendamento").live('click', function(event){
            novaAbaMenuPrincipalComParametro(
                'modulos/agendamentos/form.php',
                { 
                    pId: $(this).attr('idAgend'),
                    pIdAluno: '<?php echo $pIdAluno; ?>',
                    pReturn: 'modulos/servicos/form.php?pId=<?php echo $pIdAluno; ?>' 
                },
                'Serviços');
            event.preventDefault();
        });
        $("#btnExcAgendamento").live('click', function(event){
            vId = $(this).attr('idAgend');
            $.post(
                "modulos/agendamentos/delete.php",
                { id : vId },
                function (data) {
                    if (data.retornoStatus == "delete") {
                        $("#agendLinhaId"+vId).hide();
                    }
                },
                "json");
            event.preventDefault();
        });
    });
</script>
<table id="users" class="ui-widget ui-widget-content" cellpadding="5">
    <thead>
        <tr class="ui-widget-header ">
            <td colspan="7" align="center">Agendamentos</td>
        </tr>
        <tr class="ui-widget-header ">
            <td align="center">Data</td>
            <td align="center">Hora</td>
            <td align="center">Descrição</td>
            <td align="center">Resultado</td>
            <td align="center">&nbsp;</td>
        </tr>
    </thead>
    <tbody>
        <?php
        if (is_array($rows)) {
            foreach ($rows as $row) {
        ?>
            <tr id="agendLinhaId<?php echo $row["id"]; ?>">
                <td align="center"><?php echo db_to_date($row["data"]); ?></td>
                <td align="center"><?php echo $row["hora"]; ?></td>
                <td>
                    <a id="btnEdtAgendamento" href="#" idAgend="<?php echo $row["id"]; ?>">
                        <?php echo $row["tipoagendamento"]; ?>
                    </a>
                </td>
                <td><?php echo $row["aprovadotxt"]; ?></td>
                <td>
                    <a id="btnExcAgendamento" href="#" idAgend="<?php echo $row["id"]; ?>">
                        Excluir
                    </a>
                </td>
            </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>