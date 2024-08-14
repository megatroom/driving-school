<?php
include_once ("../../configuracao.php");

$pDataI = $_POST["datai"];
$pDataF = $_POST["dataf"];
$pUsuario = $_POST["usuario"];

$pDataValidI = new DateTime(date_to_db($pDataI));
$pDataValidF = new DateTime(date_to_db($pDataF));

$acesso = new modulos_usuarios_funcionalidades(22);

$mysql = new modulos_global_mysql();

$dataInicioCaixa = $mysql->getValue("valor", null, 'sistema', "campo = 'datainiciocaixa'");
$dataInicioCaixaValid = new DateTime($dataInicioCaixa);
if (!isset($dataInicioCaixa) or $dataInicioCaixa == "" or !is_valid_date(db_to_date($dataInicioCaixa))) {
  echo '<h2>Defina a data início do caixa na configurações do sistema.</h2>';
  exit;
}
if ($dataInicioCaixaValid > $pDataValidI) {
  echo '<h2>A data inicial é inferior a data de configuração do caixa (' . db_to_date($dataInicioCaixa) . ').<br />' .
    'Escolha uma data inicial igual ou superior.</h2>';
  exit;
}

?>
<table id="users" class="ui-widget ui-widget-content" cellpadding="5">
  <thead>
    <tr class="ui-widget-header ">
      <td colspan="9" align="center">Caixas</td>
    </tr>
    <tr class="ui-widget-header " align="center">
      <td>Data</td>
      <td>Nome</td>
      <td>Login</td>
      <td>Valor Contas a Receber</td>
      <td>Valor do Caixa</td>
      <td>Diferença</td>
      <td>Ajuste</td>
      <td>Valor do Caixa Corrigido</td>
      <?php
      if ($acesso->getFuncionalidade(1)) {
        echo "<td>&nbsp;</td>";
      }
      ?>
    </tr>
  </thead>
  <tbody>
    <?php
    $cor = '';
    $datacount = $pDataValidI;
    while ($datacount <= $pDataValidF) {

      $where = null;
      $where[] = "(a.id in " .
        "(select b.idusuario from usuariosgrupousuario b, acesso c " .
        "where c.idgrupousuario = b.idgrupousuario and c.idtela = 21) or a.id in (select idusuario from contasareceber))";
      if ($pUsuario > 0) {
        $where[] = "a.idusuario = '" . $pUsuario . "'";
      }
      $where = join(" and ", $where);

      $rows = $mysql->select(
        "e.id as idcaixa, a.nome, a.login, coalesce(sum(d.valor), 0) valorconta, e.valor as valorcaixa, e.ajuste",
        "vusuarios a " .
        "left join contasareceber d on d.idusuario = a.id and d.data = DATE('" . $datacount->format('Y-m-d') . "') " .
        "left join caixa e on d.data = e.data and a.id = e.idusuario",
        $where,
        "group by e.id, a.nome, a.login, e.valor, e.ajuste",
        'nome'
      );
      //echo $mysql->getMsgErro() ."<br><br>";

      if (is_array($rows)) {
        foreach ($rows as $row) {
          echo '<tr ' . $cor . '>';
          echo '<td align="center">' . $datacount->format('d/m/Y') . '</td>';
          echo '<td>' . $row["nome"] . '</td>';
          echo '<td>' . $row["login"] . '</td>';
          echo '<td align="right">' . db_to_float($row["valorconta"]) . '</td>';
          if (!isset($row["valorcaixa"]) or $row["valorcaixa"] == "") {
            echo '<td align="right">Caixa Aberto</td>';
            echo '<td align="right">Caixa Aberto</td>';
            echo '<td align="right">Caixa Aberto</td>';
            echo '<td align="right">Caixa Aberto</td>';
            echo '<td>&nbsp;</td>';
          } else {
            $diferenca = $row["valorcaixa"] - $row["valorconta"];
            if ($diferenca < 0) {
              $corDiferenca = 'style="color:red;"';
            } else if ($diferenca > 0) {
              $corDiferenca = 'style="color:blue;"';
            } else {
              $corDiferenca = '';
            }
            echo '<td align="right">' . db_to_float($row["valorcaixa"]) . '</td>';
            echo '<td align="right" ' . $corDiferenca . '>' . db_to_float($diferenca) . '</td>';
            echo '<td align="right">' . db_to_float($row["ajuste"]) . '</td>';
            echo '<td align="right">' . db_to_float($row["valorcaixa"] - $row["ajuste"]) . '</td>';
            if ($acesso->getFuncionalidade(1)) {
              echo '<td>';
              echo '<a href="#" id="btnAjuste">Ajustar Caixa</a>';
              echo '<input type="hidden" value="' . $row["idcaixa"] . '" ?>';
              echo '</td>';
            }
          }
          echo '</tr>';
        }
      }

      if ($cor == '') {
        $cor = 'style="background:#D4D4D4;"';
      } else {
        $cor = '';
      }

      $datacount->add(new DateInterval('P1D'));
    }
    ?>
  </tbody>
</table>
<script type="text/javascript">
  $(document).ready(function () {
    $("#btnAjuste").live('click', function (event) {
      novaAbaMenuPrincipalComParametro(
        'modulos/ctrcaixa/ajuste.php',
        {
          id: $(this).next().val()
        },
        "Ajuste");
      event.preventDefault();
    });
  });
</script>
