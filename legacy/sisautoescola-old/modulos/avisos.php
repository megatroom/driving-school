<?php
session_start();
include_once("../configuracao.php");

$wh = null;
$wh[] = "a.status = 'A'";
$wh[] = "a.iddestinatario = '".$_SESSION["IDUSUARIO"]."'";

$where = join(" and ", $wh);

$mysql = new modulos_global_mysql();
$rows = $mysql->select(
        "a.id, a.mensagem, a.data, a.prioridade, a.remetente",
        "vavisos a",
        $where,
        null,
        "a.data, a.prioridade");

if (is_array($rows)) {
    echo '<table width="100%" id="users" class="ui-widget ui-widget-content" cellpadding="5">';
    echo '<thead><tr class="ui-widget-header ">';
    echo '<th width="80px">Data</th>';
    echo '<th width="200px">Remetente</th>';
    echo '<th>Mensagem</th>';
    echo '<th width="80px">Prioridade</th>';
    echo '<th width="80px">Concluído</th>';
    echo '</tr></thead><tbody>';
    foreach ($rows as $row) {
        echo '<tr>';
        echo '<td align="center">'.db_to_date($row["data"]).'</td>';
        echo '<td>'.$row["remetente"].'</td>';
        echo '<td>'.str_replace("\n", "<br />", $row["mensagem"]).'</td>';
        echo '<td align="center">'.avisos_prioridade_to_str($row["prioridade"]).'</td>';
        echo '<td align="center"><input type="checkbox" id="chckConcluirAviso" value="'.$row["id"].'" /></td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
    echo '<br /><br />';
}
?>