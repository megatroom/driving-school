<?php
session_start();
include_once("../configuracao.php");

$wh = null;
$wh[] = "a.data >= date(now())";

$where = join(" and ", $wh);

$mysql = new modulos_global_mysql();
$rows = $mysql->select(
        "a.id, a.tipoagendamento, a.data, a.hora, a.aluno ",
        "vagendamentos a",
        $where,
        null,
        "a.data, a.hora, a.aluno");

if (is_array($rows)) {
    echo '<table width="100%" id="users" class="ui-widget ui-widget-content" cellpadding="5">';
    echo '<thead><tr class="ui-widget-header ">';
    echo '<th>Aluno</th>';
    echo '<th>Tipo de Agendamento</th>';
    echo '<th width="80px">Data</th>';
    echo '<th width="80px">Hora</th>';
    echo '</tr></thead><tbody>';
    foreach ($rows as $row) {
        echo '<tr>';
        echo '<td>'.$row["aluno"].'</td>';
        echo '<td>'.str_replace("\n", "<br />", $row["tipoagendamento"]).'</td>';
        echo '<td align="center">'.$row["data"].'</td>';
        echo '<td align="center">'.$row["hora"].'</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
    echo '<br /><br />';
}
?>