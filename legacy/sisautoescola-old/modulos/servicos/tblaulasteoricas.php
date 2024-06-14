<?php
include_once("../../configuracao.php");

$pIdAluno = $_POST["idaluno"];

$mysql = new modulos_global_mysql();

$rows = $mysql->select("*", 'vaulasteoricas', "idaluno = '".$pIdAluno."'", null, "data");

if (is_array($rows)) {
    foreach ($rows as $row) {
?>
<table id="users" class="ui-widget ui-widget-content" cellpadding="5">
    <thead>
        <tr class="ui-widget-header ">
            <td colspan="4" align="center">Aulas Teóricas</td>
        </tr>
    </thead>
    <tbody>
        <tr >
            <td class="ui-widget-header ">Professor</td>
            <td><?php echo $row["funcionario"]; ?></td>
        </tr>
        <tr >
            <td class="ui-widget-header ">Sala</td>
            <td><?php echo $row["sala"]; ?></td>
        </tr>
        <tr >
            <td class="ui-widget-header ">Data</td>
            <td><?php echo db_to_date($row["data"]); ?></td>
        </tr>
        <tr >
            <td class="ui-widget-header ">Hora</td>
            <td><?php echo $row["hora"]; ?></td>
        </tr>
    </tbody>
</table>
<?php
    }
}
?>