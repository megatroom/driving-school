<?php
include_once("../../configuracao.php");

$pIdAluno = $_POST["idaluno"];

$mysql = new modulos_global_mysql();

$rows = $mysql->select('id, tiposervico, data', 'valunoservico', "idaluno = '".$pIdAluno."'", null, 'data');

?>
<table id="users" class="ui-widget ui-widget-content" style="margin: 10px 0 10px 0;" cellpadding="5">
    <thead>
        <tr class="ui-widget-header ">
            <td colspan="7" align="center">Serviços</td>
        </tr>
        <tr class="ui-widget-header ">
            <td>Data</td>
            <td>Tipo de Serviço</td>
        </tr>
    </thead>
    <tbody>
        <?php
        if (is_array($rows)) {
            foreach ($rows as $row) {
                echo "<tr>";
                echo "<td>".db_to_date($row["data"])."</td>";
                echo "<td>".$row["tiposervico"]."</td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>