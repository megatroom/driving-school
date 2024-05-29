<?php
include_once("../../configuracao.php");

$mysql = new modulos_global_mysql();

$relatorio = $mysql->select(
        'a.data, a.hora, count(a.id) as total, b.carro ', 
        'aulaspraticas a, vcarros b', 
        "a.idcarro = b.id and data != '0000-00-00' and hora != '00:00:00'", 
        'GROUP BY a.idcarro, a.data, a.hora HAVING count(a.id) > 1', 
        'b.carro');

if (!is_array($relatorio)) {
    echo "<h1>Nenhum registro duplicado.</h1>";
    exit;
}

?>
<table id="users" class="ui-widget ui-widget-content" cellpadding="5">
    <thead>
        <tr class="ui-widget-header ">
            <td colspan="4" align="center">Exame Prático - Registros Duplicados</td>
        </tr>
        <tr class="ui-widget-header ">
            <td align="center">Carro</td>
            <td align="center">Data</td>
            <td align="center">Hora</td>
            <td align="center">Registros</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($relatorio as $row) { ?>
        <tr>
            <td><?php echo $row["carro"]; ?></td>
            <td align="center"><?php echo db_to_date($row["data"]); ?></td>
            <td align="center"><?php echo $row["hora"]; ?></td>
            <td align="center"><?php echo $row["total"]; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
        