<?php
include_once("../../configuracao.php");

$pIdAluno = $_POST["idaluno"];

$mysql = new modulos_global_mysql();

$totais = $mysql->select(
        "count(a.id) total, b.carro",
        "aulaspraticas a, vcarros b",
        "a.idaluno = '".$pIdAluno."' and a.idcarro = b.id",
        "group by b.carro");

$rows = $mysql->select(
        'a.id, a.data, a.hora, b.carro, a.falta, a.abono, a.abonomotivo, a.validado, '.
        "case when (curdate() > a.data) or (curdate() = a.data and curtime() > a.hora) then 'S' else 'N' end as dataatual ",
        'aulaspraticas a, vcarros b',
        "a.idaluno = '".$pIdAluno."' and a.idcarro = b.id",
        null,
        'a.data, a.hora');

$acesso = new modulos_usuarios_funcionalidades(1);

$habilitarTodasAulas = $acesso->getFuncionalidade(2);

$habilitarExclusao = false;


?>
<table id="users" class="ui-widget ui-widget-content" cellpadding="5">
    <thead>
        <tr class="ui-widget-header ">
            <td colspan="2" align="center">Aulas Práticas - Consolidado</td>
        </tr>
        <tr class="ui-widget-header ">
            <td>Carro</td>
            <td>Aulas</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $countTotal = 0;
        if (is_array($rows)) {            
            foreach ($totais as $total) {
                ?>
                <tr>
                    <td><?php echo $total["carro"]; ?></td>
                    <td align="right"><?php echo $total["total"]; ?></td>
                </tr>
                <?php
                $countTotal += $total["total"];
            }
        }
        ?>
        <tr class="ui-widget-header">
            <td>Total Geral</td>
            <td align="right"><?php echo $countTotal; ?></td>
        </tr>
    </tbody>
</table>
<br />
<table id="users" class="ui-widget ui-widget-content" cellpadding="5">
    <thead>
        <tr class="ui-widget-header ">
            <td colspan="8" align="center">Aulas Práticas - Analítico</td>
        </tr>
        <tr class="ui-widget-header ">
            <td>&nbsp;</td>
            <td>Data</td>
            <td>Hora</td>
            <td>Carro</td>
            <td>Presença</td>
            <td>Abono</td>
            <td>Motivo do abono</td>
            <td>Validado</td>
        </tr>
    </thead>
    <tbody>
        <?php
        if (is_array($rows)) {
            foreach ($rows as $row) {
                echo '<tr>';
                if ($row['validado'] == 'S') {
                    echo '<td>&nbsp;</td>';
                } else if ($habilitarTodasAulas) {
                    echo '<td><input type="checkbox" id="chckExcAP" value="'.$row["id"].'" /></td>';
                    $habilitarExclusao = true;
                } else {
                    if ($row["dataatual"] == "S") {
                       echo '<td>&nbsp;</td>';
                    } else {
                       echo '<td><input type="checkbox" id="chckExcAP" value="'.$row["id"].'" /></td>';
                       $habilitarExclusao = true;
                    }
                }
                echo '<td>'.db_to_date($row["data"]).'</td>';
                echo '<td>'.$row["hora"].'</td>';
                echo '<td>'.$row["carro"].'</td>';
                if ($row["abono"] == "S") {
                    echo '<td>Abonado</td>';
                    echo '<td><a href="#" id="btnRemoverAbono">Remover</a><input type="hidden" value="'.$row["id"].'" /></td>';
                    echo '<td>'.str_replace("\n", "<br />", $row["abonomotivo"]).'</td>';
                } else if ($row["falta"] == "N") {
                    echo '<td>Presente</td>';
                    echo '<td>&nbsp;</td>';
                    echo '<td>&nbsp;</td>';
                } else {
                    echo '<td><span style="color:red">Falta</span></td>';
                    echo '<td><a href="#" id="btnAbonarAP">Abonar</a><input type="hidden" value="'.$row["id"].'" /></td>';
                    echo '<td>&nbsp;</td>';
                }
                if ($row['validado'] == 'S') {
                    echo '<td>Sim</td>';
                } else {
                    echo '<td>Não</td>';
                }
                echo '</tr>';
            }
        } 
        ?>
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnAbonarAP").live('click', function(event) {
            $.post('modulos/servicos/abonaraulapratica.php',
            {
                idaulapratica : $(this).next().val()
            }, function(data){
                $("#dTblServAulasPraticas").html(data);
            });
            event.preventDefault();
        });
        $("#btnRemoverAbono").live('click', function(event) {
            $.post('modulos/servicos/removerabono.php',
            {
                idaulapratica : $(this).next().val()
            }, function(data){
                carregarAulasPraticas();
            });
            event.preventDefault();
        });
        <?php if ($habilitarExclusao) { ?>
            $("#fExcAulaPratica").removeAttr("disabled");
        <?php } else { ?>
            $("#fExcAulaPratica").attr("disabled", "disabled");
        <?php } ?>
    });
</script>