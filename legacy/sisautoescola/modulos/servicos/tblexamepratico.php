<?php
include_once("../../configuracao.php");

$pIdAluno = $_POST["idaluno"];

$mysql = new modulos_global_mysql();

$rows = $mysql->select("data, carro, resultado", 'vexamepraticoaluno', "idaluno = '".$pIdAluno."'", null, "data");

?>
<table id="users" class="ui-widget ui-widget-content" cellpadding="5">
    <thead>
        <tr class="ui-widget-header ">
            <td colspan="4" align="center">Exame Prático</td>
        </tr>
    </thead>
<?php
if (is_array($rows)) {
    foreach ($rows as $row) {
?>
    <tbody>
        <tr >
            <td class="ui-widget-header ">Data</td>
            <td><?php echo db_to_date($row["data"]); ?></td>
        </tr>
        <tr >
            <td class="ui-widget-header ">Carro</td>
            <td><?php echo $row["carro"]; ?></td>
        </tr>
        <tr>
            <td class="ui-widget-header ">Resultado</td>
            <td><?php echo getResultado($row["resultado"]); ?></td>
        </tr>
    </tbody>
<?php
    }
}

function getResultado($pResultado) {
    if ($pResultado == "A")
    {
        return "Aprovado";
    }
    elseif ($pResultado == "R")
    {
        return "Reprovado";
    }
    elseif ($pResultado == "M")
    {
        return "Não Marcado";
    }
    elseif ($pResultado == "F")
    {
        return "Falta";
    }
    elseif ($pResultado == "T")
    {
        return "Retirado";
    }
    elseif ($pResultado == "C")
    {
        return "Cancelado Aluno";
    }
    else
    {
        return "";
    }
}

?>
</table>