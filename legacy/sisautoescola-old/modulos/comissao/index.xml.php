<?php
include_once("../../configuracao.php");

$pDataI = $_POST["datai"];
$pDataF = $_POST["dataf"];
$pFun = $_POST["funcionario"];
$tipo = $_POST["tipo"];

$mysql = new modulos_global_mysql();

if ($pFun > 0) {
    $totColunas = 5;
} else {
    $totColunas = 4;
}

$rows = $mysql->select(
        "a.data, a.hora, b.descricao, coalesce(c.comissao, 0) comissao, ".
            "(select f.nome ".
            "from carrofuncionario cf, vfuncionarios f ".
            "where cf.idfuncionario = f.id ".
            "and cf.id = ".
            "(select max(cf2.id) ".
            "from carrofuncionario cf2 ".
            "where cf2.idcarro = a.idcarro and ".
            "TIMESTAMP(cf2.data, cf2.hora) <= TIMESTAMP(a.data, a.hora)) ".
            ") as funcionario, ".
        "(select f.id ".
            "from carrofuncionario cf, vfuncionarios f ".
            "where cf.idfuncionario = f.id ".
            "and cf.id = ".
            "(select max(cf2.id) ".
            "from carrofuncionario cf2 ".
            "where cf2.idcarro = a.idcarro and ".
            "TIMESTAMP(cf2.data, cf2.hora) <= TIMESTAMP(a.data, a.hora)) ".
            ") as idfuncionario",
        'aulaspraticas a, carros b, tipocarros c',
        "a.idcarro = b.id ".
            "and b.idtipocarro = c.id ".
            "and a.data between '".date_to_db($pDataI)."' and '".date_to_db($pDataF)."' ",
        null,
        'funcionario, a.data, a.hora');
//echo $mysql->getMsgErro();
if (!is_array($rows)) {
    echo "<h2>Não há informações para o filtro escolhido.</h2>";
    exit;
}

?>
<table id="users" class="ui-widget ui-widget-content" cellpadding="5">
    <thead>
        <tr class="ui-widget-header ">
            <td colspan="<?php echo $totColunas; ?>" align="center">Caixas</td>
        </tr>
        <tr class="ui-widget-header " align="center">
            <?php if ($pFun == 0) { ?>
                <td>Funcionários</td>
            <?php } ?>
            <?php if ($tipo == "A") { ?>
                <td>Data</td>
            <?php } ?>
            <td>Qtd. Aulas</td>
            <td>Valor Total</td>
        </tr>
    </thead>
    <tbody>
    <?php
    $lastFun = 0;
    $nomeFun = "";
    $lastDay = "";
    $comissao = 0;
    $countAulas = 0;

    $isImpFun = true;
    if ($tipo == "A")
    {
        foreach ($rows as $row) {
            if ($lastFun == 0) {
                $lastFun = $row["idfuncionario"];
                $nomeFun = $row["funcionario"];
            }
            if ($lastDay == "") {
                $lastDay = db_to_date($row["data"]);
            }

            if ($pFun > 0) {
                if ($row["idfuncionario"] == $pFun) {
                    $isImpFun = true;
                } else {
                    $isImpFun = false;
                }
            }

            if ($isImpFun) {
                if ($lastDay != db_to_date($row["data"])) {
                    echo "<tr>";
                    if ($pFun == 0)
                    {
                        echo "<td>".$nomeFun."</td>";
                    }
                    echo "<td>".$lastDay."</td>";
                    echo '<td align="right">'.$countAulas."</td>";
                    echo '<td align="right">R$ '.db_to_float($comissao)."</td>";
                    echo "</tr>";
                    $comissao = 0;
                    $countAulas = 0;
                    $lastDay = db_to_date($row["data"]);
                }
                $comissao = $comissao + $row["comissao"];
                $countAulas++;
            }

            if ($lastFun != $row["idfuncionario"]) {
                $lastFun = $row["idfuncionario"];
                $nomeFun = $row["funcionario"];
            }
        }
    }
    else
    {
        foreach ($rows as $row) {
            if ($lastFun == 0) {
                $lastFun = $row["idfuncionario"];
                $nomeFun = $row["funcionario"];
            }

            if ($pFun > 0) {
                if ($row["idfuncionario"] == $pFun) {
                    $isImpFun = true;
                } else {
                    $isImpFun = false;
                }
            }

            if ($lastFun != $row["idfuncionario"]) {
                if ($isImpFun) {
                    echo "<tr>";
                    if ($pFun == 0)
                    {
                        echo "<td>".$nomeFun."</td>";
                    }
                    echo '<td align="right">'.$countAulas."</td>";
                    echo '<td align="right">R$ '.db_to_float($comissao)."</td>";
                    echo "</tr>";
                    $comissao = 0;
                    $countAulas = 0;
                    $lastFun = $row["idfuncionario"];
                    $nomeFun = $row["funcionario"];
                }
                $comissao = $comissao + $row["comissao"];
                $countAulas++;
            }                        
        }
    }
    
    echo "<tr>";
    if ($pFun == 0)
    {
        echo "<td>".$nomeFun."</td>";
    }
    if ($tipo == "A") {
        echo "<td>".$lastDay."</td>";
    }
    echo '<td align="right">'.$countAulas."</td>";
    echo '<td align="right">R$ '.db_to_float($comissao)."</td>";
    echo "</tr>";
    
    ?>
    </tbody>
</table>