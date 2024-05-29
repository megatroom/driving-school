<?php
include_once("../../configuracao.php");

$pDataI = $_POST["datai"];
$pDataF = $_POST["dataf"];
$pUsuario = $_POST["usuario"];

$where = null;
$where[] = "c.caixa = 'S'";
$where[] = "d.data between DATE('".date_to_db($pDataI)."') and DATE('".date_to_db($pDataF)."')";
if ($pUsuario > 0) {
    $where[] = "a.id = '".$pUsuario."'";
}
$where = join(" and ", $where);

$mysql = new modulos_global_mysql();

$rows = $mysql->select(
        'b.nome, a.login, d.data, d.hora, d.valor as valorcaixa, d.ajuste, '.
        '(select sum(x.valor) from contasareceber x where x.idusuario = d.idusuario and x.data = d.data) as valorconta',
        'usuarios a '.
        'inner join vfuncionarios b on a.idfuncionario = b.id '.
        'inner join funcoes c on b.idfuncao = c.id '.
        'left join caixa d on d.idusuario = a.id ',
        $where,
        null,
        'd.data, d.hora, b.nome');
//echo $mysql->getMsgErro();
?>
<table id="users" class="ui-widget ui-widget-content" cellpadding="5">
    <thead>
        <tr class="ui-widget-header ">
            <td colspan="7" align="center">Caixas</td>
        </tr>
        <tr class="ui-widget-header " align="center">
            <td>Data</td>
            <td>Hora</td>
            <td>Nome</td>
            <td>Login</td>            
            <td>Valor Contas a Receber</td>
            <td>Valor Real do Caixa</td>
            <td>Ajuste</td>
        </tr>
    </thead>
    <tbody>
    <?php
    if (is_array($rows)) {
        foreach ($rows as $row) {
            echo '<tr>';
            echo '<td align="center">'.db_to_date($row["data"]).'</td>';
            echo '<td align="center">'.$row["hora"].'</td>';
            echo '<td>'.$row["nome"].'</td>';
            echo '<td>'.$row["login"].'</td>';
            echo '<td align="right">'.db_to_float($row["valorconta"]).'</td>';
            echo '<td align="right">'.db_to_float($row["valorcaixa"]).'</td>';
            echo '<td align="right">'.db_to_float($row["ajuste"]).'</td>';
            echo '</tr>';
        }
    }
    ?>
    </tbody>
</table>