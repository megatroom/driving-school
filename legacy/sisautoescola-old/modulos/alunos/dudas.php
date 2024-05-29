<?php
include_once("../../configuracao.php");

$pIdAluno = $_POST["idaluno"];

$mysql = new modulos_global_mysql();

$rows = null;
if ($pIdAluno > 0) {
    $rows = $mysql->select('duda, data', 'alunosdudas', "idaluno = '".$pIdAluno."'", null, 'data desc');
}

?>
<table id="users" class="ui-widget ui-widget-content" style="margin: 10px 0 10px 0;" cellpadding="5">
    <thead>
        <tr class="ui-widget-header ">
            <td colspan="7" align="center">Dudas</td>
        </tr>
        <tr class="ui-widget-header ">
            <td align="center">Data</td>
            <td align="center">Duda</td>
        </tr>
    </thead>
    <tbody>
        <?php
        if (is_array($rows)) {
            foreach ($rows as $row) {
                echo "<tr>";
                echo "<td>".db_to_date($row["data"])."</td>";
                echo "<td>".$row["duda"]."</td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>