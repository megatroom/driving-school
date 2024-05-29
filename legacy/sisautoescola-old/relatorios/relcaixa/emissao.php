<?php
include_once("../../configuracao.php");

$datai = $_GET["datai"];
$dataf = $_GET["dataf"];
$usuario = $_GET["usuario"];
$tipoRel = $_GET["tiporel"];
$opcoes = explode(",", $_GET["opcoes"]);

$rel = new modulos_global_relatorio($tipoRel, "Relatório de Caixa");

$mysql = new modulos_global_mysql();

if (!is_valid_date($datai)) {
    $rel->alertAndClose('Data Inicial informada inválida!');
    $rel->close();
    exit;
}

if (!is_valid_date($dataf)) {
    $rel->alertAndClose('Data Final informada inválida!');
    $rel->close();
    exit;
}

if ($_GET["opcoes"] == "") {
    $rel->alertAndClose('Selecione pelo menos uma opção de relatório!');
    $rel->close();
    exit;
}

if (isset ($usuario) and is_numeric($usuario) and $usuario > 0) {
    if (in_array("3", $opcoes) == false and in_array("4", $opcoes) == false) {
        $rel->alertAndClose('Para filtrar por um Caixa, escolha um tipo de relatório por Caixa!');
        $rel->close();
        exit;
    }
}

/*
 * Cabeçalho - Início
 */
$cbcRel = $mysql->select(
        "a.campo, a.valor",
        "sistema a",
        "a.campo = 'reltitulo' or a.campo = 'reldesc'");

$titulo = "";
$cabecalho = "";
if (is_array($cbcRel)) {
    foreach ($cbcRel as $cabecalhoRel) {
        if ($cabecalhoRel["campo"] == "reltitulo") {
            $titulo = $cabecalhoRel["valor"];
        }
        if ($cabecalhoRel["campo"] == "reldesc") {
            $cabecalho = $cabecalhoRel["valor"];
            $cabecalho = str_replace("\n",'<br />', $cabecalho);
        }
    }
}

$rel->openTable('tblCabecalho', $rel->attrCabTable());
$rel->newLine();
$rel->newCel($titulo, $rel->attrCabTitulo());
$rel->closeLine();
$rel->newLine();
$rel->newCel($cabecalho, $rel->attrCabDesc());
$rel->closeLine();
$rel->closeTable();

$rel->hr();
/*
 * Cabeçalho - Fim
 */

$rel->titulo('Relatório Financeiro');
$rel->subTitulo('Período de '.$datai.' de '.$dataf);

$vDataI = new DateTime(date_to_db($datai));
$vDataF  = new DateTime(date_to_db($dataf));
$groupDate = "";
if ($vDataI->format('Y') == $vDataF->format('Y')) {
    if ($vDataI->format('m') == $vDataF->format('m')) {
        $groupDate = "dia";
    } else {
        $groupDate = "mes";
    }
} else {
    $groupDate = "ano";
}

$attrTable = null;
$attrTable["border"] = "1";
$attrTable["cellpadding"] = "0px";

if (in_array("1", $opcoes)) {

    $rel->h2("Consolidado Geral");

    $totValorConta = 0;
    $totValorCaixa = 0;
    $totAjuste = 0;
    $totCorrigido = 0;

    $totValorConta = $mysql->getValue(
            "coalesce(sum(a.valor), 0) as total",
            "total",
            "contasareceber a",
            "a.data between DATE('".$vDataI->format('Y-m-d')."') and DATE('".$vDataF->format('Y-m-d')."')");
    $rowsConsolidadoGeral = $mysql->select(
            "coalesce(sum(a.valor), 0) as valorcaixa, coalesce(sum(a.ajuste), 0) as ajuste",
            "caixa a",
            "a.data between DATE('".$vDataI->format('Y-m-d')."') and DATE('".$vDataF->format('Y-m-d')."')");
    foreach ($rowsConsolidadoGeral as $row) {
        $totValorCaixa = $row["valorcaixa"];
        $totAjuste = $row["ajuste"];
    }
    $totCorrigido = $totValorCaixa-$totAjuste;

    $rel->openTable('tblConsolidadoGeral', $attrTable);
    $rel->newLine();
    $rel->newCel('Valor Conta a Receber', $rel->attrDadosLabel());
    $rel->newCel(db_to_float($totValorConta), array("align"=>"right"));
    $rel->closeLine();
    $rel->newLine();
    $rel->newCel('Valor Caixa', $rel->attrDadosLabel());
    $rel->newCel(db_to_float($totValorCaixa), array("align"=>"right"));
    $rel->closeLine();
    $rel->newLine();
    $rel->newCel('Valor Ajuste', $rel->attrDadosLabel());
    $rel->newCel(db_to_float($totAjuste), array("align"=>"right"));
    $rel->closeLine();
    $rel->newLine();
    $rel->newCel('Valor Caixa Corrigido', $rel->attrDadosLabel());
    $rel->newCel(db_to_float($totCorrigido), array("align"=>"right"));
    $rel->closeLine();
    $rel->closeTable();
}

if (in_array("2", $opcoes)) {
    $datacount = new DateTime(date_to_db($datai));
    $datacond  = new DateTime(date_to_db($dataf));

    $rel->h2("Consolidado por Período");

    $rel->openTable('tblConsolidadoPorPeriodo', $attrTable);
    $rel->newLine();
    $rel->newCelHeader('Data');
    $rel->newCelHeader('Valor Conta a Receber');
    $rel->newCelHeader('Valor Caixa');
    $rel->newCelHeader('Valor Ajuste');
    $rel->newCelHeader('Valor Caixa Corrigido');
    $rel->closeLine();

    $totValorConta = 0;
    $totValorCaixa = 0;
    $totAjuste = 0;
    $totCorrigido = 0;

    $ultimoAno = "";
    $ultimoMes = "";
    while ($datacount <= $datacond) {

        $printCel = true;
        if ($groupDate == "ano") {
            if ($ultimoAno == "") {
                $ultimoAno = $datacount->format('Y');
            } else {
                if ($ultimoAno == $datacount->format('Y')) {
                    $printCel = false;
                } else {
                    $ultimoAno = $datacount->format('Y');
                }
            }
        } elseif ($groupDate == "mes") {
            if ($ultimoMes == "") {
                $ultimoMes = $datacount->format('m');
            } else {
                if ($ultimoMes == $datacount->format('m')) {
                    $printCel = false;
                } else {
                    $ultimoMes = $datacount->format('m');
                }
            }
        }

        if ($printCel) {
            $where = "";
            if ($groupDate == "ano") {
                $where = "YEAR(a.data) = YEAR(DATE('".$datacount->format('Y-m-d')."'))";
            } elseif ($groupDate == "mes") {
                $where = "MONTH(a.data) = MONTH(DATE('".$datacount->format('Y-m-d')."'))";
            } else {
                $where = "a.data = DATE('".$datacount->format('Y-m-d')."')";
            }
            $valorConta = $mysql->getValue(
                    "coalesce(sum(a.valor), 0) as total",
                    "total",
                    "contasareceber a",
                    $where);
            $rowsConsolidadoGeral = $mysql->select(
                    "coalesce(sum(a.valor), 0) as valorcaixa, coalesce(sum(a.ajuste), 0) as ajuste",
                    "caixa a",
                    $where);
            $valorCaixa = 0;
            $ajuste = 0;
            foreach ($rowsConsolidadoGeral as $row) {
                $valorCaixa = $row["valorcaixa"];
                $ajuste = $row["ajuste"];
            }
            $rel->newLine();
            if ($groupDate == "ano") {
                $rel->newCel($datacount->format('Y'), array("align"=>"center"));
            } elseif ($groupDate == "mes") {
                $rel->newCel($datacount->format('m/Y'), array("align"=>"center"));
            } else {
                $rel->newCel($datacount->format('d/m/Y'), array("align"=>"center"));
            }
            $rel->newCel(db_to_float($valorConta), array("align"=>"right"));
            $rel->newCel(db_to_float($valorCaixa), array("align"=>"right"));
            $rel->newCel(db_to_float($ajuste), array("align"=>"right"));
            $rel->newCel(db_to_float($valorCaixa-$ajuste), array("align"=>"right"));
            $rel->closeLine();

            $totValorConta += $valorConta;
            $totValorCaixa += $valorCaixa;
            $totAjuste += $ajuste;
            $totCorrigido += $valorCaixa-$ajuste;
        }

        $datacount->add(new DateInterval('P1D'));
    }

    $rel->newFoot();
    $rel->newLine();
    $rel->newCel("Total");
    $rel->newCel(db_to_float($totValorConta), array("align"=>"right"));
    $rel->newCel(db_to_float($totValorCaixa), array("align"=>"right"));
    $rel->newCel(db_to_float($totAjuste), array("align"=>"right"));
    $rel->newCel(db_to_float($totCorrigido), array("align"=>"right"));
    $rel->closeLine();
    $rel->closeFoot();
    $rel->closeTable();
}

if (in_array("3", $opcoes)) {
    $rel->h2("Consolidado por Caixa");

    $rel->openTable('tblConsolidadoPorCaixa', $attrTable);
    $rel->newLine();
    $rel->newCelHeader('Nome');
    $rel->newCelHeader('Login');
    $rel->newCelHeader('Valor Recebido');
    $rel->newCelHeader('Valor Caixa');
    $rel->newCelHeader('Ajuste');
    $rel->newCelHeader('Valor Corrigido');
    $rel->closeLine();

    $where = null;
    $where[] = "(a.id in ".
        "(select b.idusuario from usuariosgrupousuario b, acesso c ".
        "where c.idgrupousuario = b.idgrupousuario and c.idtela = 21) or a.id in (select idusuario from contasareceber))";
    if ($usuario > 0) {
        $where[] = "a.idusuario = '".$usuario."'";
    }
    $where = join(" and ", $where);
    
    $rowsConsTipoCaixa = $mysql->select(
                "a.id as idusuario, a.nome, a.login, coalesce(sum(d.valor), 0) valorconta",
                "vusuarios a ".
                    "left join contasareceber d on d.idusuario = a.id and d.data between DATE('".$vDataI->format('Y-m-d')."') and DATE('".$vDataF->format('Y-m-d')."') ",
                $where,
                "group by a.nome, a.login",
                'nome');
    //echo $mysql->getMsgErro() ."<br><br>";

    $totValorRecebido = 0;
    $totValorCaixa = 0;
    $totAjuste = 0;
    $totValorCorrigido = 0;
    foreach ($rowsConsTipoCaixa as $row) {
        $valorRecebido = $row["valorconta"];
        $valorCaixa = 0;
        $ajuste = 0;
        $valorCorrigido = 0;
        $rowsConsTipoCaixa2 = $mysql->select(
                "coalesce(sum(a.valor), 0) as valorcaixa, coalesce(sum(a.ajuste), 0) as ajuste",
                "caixa a",
                "a.idusuario = '".$row["idusuario"]."' and a.data between DATE('".$vDataI->format('Y-m-d')."') and DATE('".$vDataF->format('Y-m-d')."')");
        if (is_array($rowsConsTipoCaixa2)) {
            foreach ($rowsConsTipoCaixa2 as $value) {
                $valorCaixa += $value["valorcaixa"];
                $ajuste += $value["ajuste"];
            }
        }
        $valorCorrigido = $valorCaixa - $ajuste;
        
        $rel->newLine();
        $rel->newCel($row["nome"]);
        $rel->newCel($row["login"]);
        $rel->newCel(db_to_float($valorRecebido), array("align"=>"right"));
        $rel->newCel(db_to_float($valorCaixa), array("align"=>"right"));
        $rel->newCel(db_to_float($ajuste), array("align"=>"right"));
        $rel->newCel(db_to_float($valorCorrigido), array("align"=>"right"));
        $rel->closeLine();

        $totValorRecebido += $valorRecebido;
        $totValorCaixa += $valorCaixa;
        $totAjuste += $ajuste;
        $totValorCorrigido += $valorCorrigido;
    }

    $rel->newFoot();
    $rel->newLine();
    $rel->newCel("Total", array("colspan"=>"2"));
    $rel->newCel(db_to_float($totValorRecebido), array("align"=>"right"));
    $rel->newCel(db_to_float($totValorCaixa), array("align"=>"right"));
    $rel->newCel(db_to_float($totAjuste), array("align"=>"right"));
    $rel->newCel(db_to_float($totValorCorrigido), array("align"=>"right"));
    $rel->closeLine();
    $rel->closeFoot();
    $rel->closeTable();
}

if (in_array("6", $opcoes)) {

    $totValorServico = 0;
    $totValorDesconto = 0;
    $totValorComDesconto = 0;
    $totValorRecebido = 0;
    $totValorReceber = 0;

    $rowsConsTipo = $mysql->select(
            "c.id, c.descricao, coalesce(sum(c.valor), 0) as valorservico, coalesce(sum(a.desconto), 0) as desconto",
            "alunoservico a, tiposervicos c", 
            "a.idtiposervico = c.id and ".
            "a.data between DATE('".$vDataI->format('Y-m-d')."') and DATE('".$vDataF->format('Y-m-d')."')",
            "group by c.descricao",
            "descricao");
    if (is_array($rowsConsTipo)) {

        $rel->h2("Consolidado por Tipo de Serviço");

        $rel->openTable('tblConsolidadoPorTipo', $attrTable);
        $rel->newLine();
        $rel->newCelHeader('Tipo de Serviço');
        $rel->newCelHeader('Valor do Tipo');
        $rel->newCelHeader('Desconto');
        $rel->newCelHeader('Valor com Desconto');
        $rel->newCelHeader('Valor Recebido');
        $rel->newCelHeader('Valor Valor a Receber');
        $rel->closeLine();

        foreach ($rowsConsTipo as $row) {
            $valorServico = $row["valorservico"];
            $valorDesconto = $row["desconto"];
            $valorComDesconto = $valorServico - $valorDesconto;
            $valorRecebido = 0;
            $valorReceber = 0;

            $rowsConsTipoCaixa = $mysql->select(
                    "coalesce(sum(b.valor), 0) as valorrecebido",
                    "alunoservico a, contasareceber b",
                    "a.idtiposervico = '".$row["id"]."' and b.idalunoservico = a.id and ".
                    "a.data between DATE('".$vDataI->format('Y-m-d')."') and DATE('".$vDataF->format('Y-m-d')."')");
            if (is_array($rowsConsTipoCaixa)) {
                foreach ($rowsConsTipoCaixa as $value) {
                    $valorRecebido += $value["valorrecebido"];
                }
            }
            $valorReceber = $valorComDesconto - $valorRecebido;

            $rel->newLine();
            $rel->newCel($row["descricao"]);
            $rel->newCel(db_to_float($valorServico), array("align"=>"right"));
            $rel->newCel(db_to_float($valorDesconto), array("align"=>"right"));
            $rel->newCel(db_to_float($valorComDesconto), array("align"=>"right"));
            $rel->newCel(db_to_float($valorRecebido), array("align"=>"right"));
            $rel->newCel(db_to_float($valorReceber), array("align"=>"right"));
            $rel->closeLine();

            $totValorServico += $valorServico;
            $totValorDesconto += $valorDesconto;
            $totValorComDesconto += $valorComDesconto;
            $totValorRecebido += $valorRecebido;
            $totValorReceber += $valorReceber;
        }

        $rel->newFoot();
        $rel->newLine();
        $rel->newCel("Total");
        $rel->newCel(db_to_float($totValorServico), array("align"=>"right"));
        $rel->newCel(db_to_float($totValorDesconto), array("align"=>"right"));
        $rel->newCel(db_to_float($totValorComDesconto), array("align"=>"right"));
        $rel->newCel(db_to_float($totValorRecebido), array("align"=>"right"));
        $rel->newCel(db_to_float($totValorReceber), array("align"=>"right"));
        $rel->closeLine();
        $rel->closeFoot();
        $rel->closeTable();
    }
}

if (in_array("4", $opcoes)) {
    $rel->h2("Analítico Geral");

    $rel->openTable('tblAnaliticoGeral', $attrTable);
    $rel->newLine();
    $rel->newCelHeader('Data Lançamento');
    $rel->newCelHeader('Tipo de Serviço');
    $rel->newCelHeader('Valor do Tipo');
    $rel->newCelHeader('Desconto');
    $rel->newCelHeader('Valor com Desconto');
    $rel->newCelHeader('Valor Recebido');
    $rel->newCelHeader('Valor Valor a Receber');
    $rel->closeLine();

    $rowAnalGeral = $mysql->select(
            "a.id, a.data as datalancamento, b.descricao as tiposervico, a.valor as valorservico, a.desconto, ".
                "(select coalesce(sum(x.valor), 0) from contasareceber x where x.idalunoservico = a.id) as valorrecebido",
            "alunoservico a, tiposervicos b",
            "a.idtiposervico = b.id and ".
                "a.data between DATE('".$vDataI->format('Y-m-d')."') and DATE('".$vDataF->format('Y-m-d')."')",
            null,
            "data, tiposervico");

    $totValorServico = 0;
    $totDesconto = 0;
    $totValorDesconto = 0;
    $totValorRecebido = 0;
    $totValorReceber = 0;
    if (is_array($rowAnalGeral)) {
        foreach ($rowAnalGeral as $row) {
            $valorServico = $row["valorservico"];
            $desconto = $row["desconto"];
            $valorDesconto = $valorServico - $desconto;
            $valorRecebido = $row["valorrecebido"];
            $valorReceber = $valorDesconto - $valorRecebido;

            $rel->newLine();
            $rel->newCel(db_to_date($row["datalancamento"]), array("align"=>"center"));
            $rel->newCel($row["tiposervico"]);
            $rel->newCel(db_to_float($valorServico), array("align"=>"right"));
            $rel->newCel(db_to_float($desconto), array("align"=>"right"));
            $rel->newCel(db_to_float($valorDesconto), array("align"=>"right"));
            $rel->newCel(db_to_float($valorRecebido), array("align"=>"right"));
            $rel->newCel(db_to_float($valorReceber), array("align"=>"right"));
            $rel->closeLine();

            $totValorServico += $valorServico;
            $totDesconto += $desconto;
            $totValorDesconto += $valorDesconto;
            $totValorRecebido += $valorRecebido;
            $totValorReceber += $valorReceber;
        }
    }

    $rel->newFoot();
    $rel->newLine();
    $rel->newCel("Total", array("colspan"=>"2"));
    $rel->newCel(db_to_float($totValorServico), array("align"=>"right"));
    $rel->newCel(db_to_float($totDesconto), array("align"=>"right"));
    $rel->newCel(db_to_float($totValorDesconto), array("align"=>"right"));
    $rel->newCel(db_to_float($totValorRecebido), array("align"=>"right"));
    $rel->newCel(db_to_float($totValorReceber), array("align"=>"right"));
    $rel->closeLine();
    $rel->closeFoot();
    $rel->closeTable();
}

if (in_array("5", $opcoes)) {
    $rel->h2("Analítico Detalhado");

    $rel->openTable('tblAnaliticoGeral', $attrTable);

    $rowAnalGeral = $mysql->select(
            "a.id, a.data as datalancamento, b.descricao as tiposervico, a.valor as valorservico, a.desconto, ".
                "c.nome as aluno",
            "alunoservico a, tiposervicos b, valunos c",
            "a.idtiposervico = b.id and ".
                "a.data between DATE('".$vDataI->format('Y-m-d')."') and DATE('".$vDataF->format('Y-m-d')."') ".
                "and a.idaluno = c.id",
            null,
            "data, tiposervico");
    //echo $mysql->getMsgErro();

    if (is_array($rowAnalGeral)) {
        foreach ($rowAnalGeral as $row) {
            $valorServico = $row["valorservico"];
            $desconto = $row["desconto"];
            $valorDesconto = $valorServico - $desconto;
            $valorRecebido = 0;
            $valorReceber = $valorDesconto - $valorRecebido;            

            $rel->newLine();
            $rel->newCel('Data Lançamento:', array("style"=>"font-weight:bold;"));
            $rel->newCel(db_to_date($row["datalancamento"]), array("colspan"=>"5"));
            $rel->closeLine();
            $rel->newLine();
            $rel->newCel('Tipo de Serviço:', array("style"=>"font-weight:bold;"));
            $rel->newCel($row["tiposervico"], array("colspan"=>"5"));
            $rel->closeLine();
            $rel->newLine();
            $rel->newCel('Aluno:', array("style"=>"font-weight:bold;"));
            $rel->newCel($row["aluno"], array("colspan"=>"5"));
            $rel->closeLine();
            $rel->newLine();
            $rel->newCel('Valor do Tipo:', array("style"=>"font-weight:bold;"));
            $rel->newCel(db_to_float($valorServico), array("align"=>"right"));
            $rel->newCel('Desconto:', array("style"=>"font-weight:bold;"));
            $rel->newCel(db_to_float($desconto), array("align"=>"right"));
            $rel->newCel('Valor com Desconto:', array("style"=>"font-weight:bold;"));
            $rel->newCel(db_to_float($valorDesconto), array("align"=>"right"));
            $rel->closeLine();

            $rowAnalGeralDetalhe = $mysql->select(
                    "a.data, a.valor, b.nome",
                    "contasareceber a, vusuarios b",
                    "a.idalunoservico = '".$row["id"]."' and a.idusuario = b.id",
                    null,
                    "data");

            if (is_array($rowAnalGeralDetalhe)) {
                $totValor = 0;
                $rel->newLine();
                $rel->null('<td colspan="6" align="center">');
                $rel->openTable('tblRelAnalDet'.$row["id"], $attrTable);
                $rel->newLine();
                $rel->newCelHeader('Data');
                $rel->newCelHeader('Caixa');
                $rel->newCelHeader('Valor');                
                $rel->closeLine();
                foreach ($rowAnalGeralDetalhe as $value) {
                    $rel->newLine();
                    $rel->newCel(db_to_date($value["data"]));
                    $rel->newCel($value["nome"]);
                    $rel->newCel(db_to_float($value["valor"]), array("align"=>"right"));
                    $rel->closeLine();
                    $totValor += $value["valor"];
                }
                $rel->newFoot();
                $rel->newLine();
                $rel->newCel("Total", array("colspan"=>"2"));
                $rel->newCel(db_to_float($totValor), array("align"=>"right"));
                $rel->closeLine();
                $rel->closeFoot();
                $rel->closeTable();
                $rel->null('</td>');
                $rel->closeLine();
            } else {
                $rel->newLine();
                $rel->null('<td colspan="6">&nbsp;');
                $rel->null('</td>');
                $rel->closeLine();
            }
        }
    }

    $rel->closeTable();
}

if (in_array("9", $opcoes)) {
    $rel->h2("Pagamento do Aluno Geral");

    $rel->openTable('tblPagtoAlunoGeral', $attrTable);

    $rowPagtoAlunoGeral = $mysql->select(
            "b.nome, coalesce(a.valor, 0) as valorservico, coalesce(a.desconto, 0) as desconto, d.descricao, coalesce(sum(c.valor), 0) as valorconta",
            "alunoservico a, valunos b, contasareceber c, tiposervicos d",
            "a.idaluno = b.id and a.id = c.idalunoservico and a.idtiposervico = d.id and ".
                "c.data between DATE('".$vDataI->format('Y-m-d')."') and DATE('".$vDataF->format('Y-m-d')."') ",
            "group by b.nome, a.valor, a.desconto, d.descricao",
            "b.nome, c.data");

    $rel->newLine();
    $rel->newCelHeader("Data");
    $rel->newCelHeader("Serviço");
    $rel->newCelHeader("Valor do Serviço");
    $rel->newCelHeader("Desconto do Serviço");
    $rel->newCelHeader("Valor com Desconto");
    $rel->newCelHeader("Valor Recebido no Período");
    $rel->closeLine();

    $totValorServico = 0;
    $totDesconto = 0;
    $totValorComDesconto = 0;
    $totValorRecebido = 0;
    if (is_array($rowPagtoAlunoGeral)) {
        foreach ($rowPagtoAlunoGeral as $row) {

            $valorComDesconto = $row["valorservico"] - $row["desconto"];
            $rel->newLine();
            $rel->newCel($row["nome"]);
            $rel->newCel($row["descricao"]);
            $rel->newCel(db_to_float($row["valorservico"]), array("align"=>"right"));
            $rel->newCel(db_to_float($row["desconto"]), array("align"=>"right"));
            $rel->newCel(db_to_float($valorComDesconto), array("align"=>"right"));
            $rel->newCel(db_to_float($row["valorconta"]), array("align"=>"right"));
            $rel->closeLine();

            $totValorServico += $row["valorservico"];
            $totDesconto += $row["desconto"];
            $totValorComDesconto += $valorComDesconto;
            $totValorRecebido += $row["valorconta"];
        }
        $rel->newLine(array("style"=>"font-weight:bold;"));
        $rel->newCel("Total do Aluno", array("colspan"=>"2"));
        $rel->newCel(db_to_float($totValorServico), array("align"=>"right"));
        $rel->newCel(db_to_float($totDesconto), array("align"=>"right"));
        $rel->newCel(db_to_float($totValorComDesconto), array("align"=>"right"));
        $rel->newCel(db_to_float($totValorRecebido), array("align"=>"right"));
        $rel->closeLine();

    }

    $rel->closeTable();
}

if (in_array("10", $opcoes)) {
    $rel->h2("Pagamento do Aluno - Simples Conferência");

    $rel->openTable('tblPagtoAlunoGeral', $attrTable);

    $rowPagtoAlunoGeral = $mysql->select(
            "b.nome, coalesce(a.valor, 0) as valorservico, coalesce(a.desconto, 0) as desconto, d.descricao, coalesce(sum(c.valor), 0) as valorconta",
            "alunoservico a, valunos b, contasareceber c, tiposervicos d",
            "a.idaluno = b.id and a.id = c.idalunoservico and a.idtiposervico = d.id and ".
                "c.data between DATE('".$vDataI->format('Y-m-d')."') and DATE('".$vDataF->format('Y-m-d')."') ",
            "group by b.nome, a.valor, a.desconto, d.descricao",
            "b.nome, c.data");

    $rel->newLine();
    $rel->newCelHeader("Data");
    $rel->newCelHeader("Serviço");   
    $rel->newCelHeader("Valor Recebido no Período");
    $rel->closeLine();

    $totValorServico = 0;
    $totDesconto = 0;
    $totValorComDesconto = 0;
    $totValorRecebido = 0;
    if (is_array($rowPagtoAlunoGeral)) {
        foreach ($rowPagtoAlunoGeral as $row) {

            $valorComDesconto = $row["valorservico"] - $row["desconto"];
            $rel->newLine();
            $rel->newCel($row["nome"]);
            $rel->newCel($row["descricao"]);
            $rel->newCel(db_to_float($row["valorconta"]), array("align"=>"right"));
            $rel->closeLine();

            $totValorServico += $row["valorservico"];
            $totDesconto += $row["desconto"];
            $totValorComDesconto += $valorComDesconto;
            $totValorRecebido += $row["valorconta"];
        }
        $rel->newLine(array("style"=>"font-weight:bold;"));
        $rel->newCel("Total do Aluno", array("colspan"=>"2"));
        $rel->newCel(db_to_float($totValorRecebido), array("align"=>"right"));
        $rel->closeLine();

    }

    $rel->closeTable();
}

if (in_array("7", $opcoes)) {
    $rel->h2("Pagamento do Aluno Detalhado");

    $rel->openTable('tblPagtoAlunoPorPeriodo', $attrTable);

    $rowPagtoAlunoPorPeriodo = $mysql->select(
            "b.nome, coalesce(a.valor, 0) as valorservico, coalesce(a.desconto, 0) as desconto, c.data, d.descricao, coalesce(sum(c.valor), 0) as valorconta",
            "alunoservico a, valunos b, contasareceber c, tiposervicos d",
            "a.idaluno = b.id and a.id = c.idalunoservico and a.idtiposervico = d.id and ".
                "c.data between DATE('".$vDataI->format('Y-m-d')."') and DATE('".$vDataF->format('Y-m-d')."') ",
            "group by b.nome, a.valor, a.desconto, c.data, d.descricao",
            "b.nome, c.data");

    $rel->newLine();
    $rel->newCelHeader("Data");
    $rel->newCelHeader("Serviço");
    $rel->newCelHeader("Valor do Serviço");
    $rel->newCelHeader("Desconto do Serviço");
    $rel->newCelHeader("Valor com Desconto");
    $rel->newCelHeader("Valor Recebido no Período");
    $rel->closeLine();

    $totValorServico = 0;
    $totDesconto = 0;
    $totValorComDesconto = 0;
    $totValorRecebido = 0;
    $totGeralValorServico = 0;
    $totGeralDesconto = 0;
    $totGeralValorComDesconto = 0;
    $totGeralValorRecebido = 0;
    $lastAluno = "";
    if (is_array($rowPagtoAlunoPorPeriodo)) {
        foreach ($rowPagtoAlunoPorPeriodo as $row) {
            if ($lastAluno != $row["nome"]) {

                if ($lastAluno != "") {
                    $rel->newLine(array("style"=>"font-weight:bold;"));
                    $rel->newCel("Total do Aluno", array("colspan"=>"2"));
                    $rel->newCel(db_to_float($totValorServico), array("align"=>"right"));
                    $rel->newCel(db_to_float($totDesconto), array("align"=>"right"));
                    $rel->newCel(db_to_float($totValorComDesconto), array("align"=>"right"));
                    $rel->newCel(db_to_float($totValorRecebido), array("align"=>"right"));
                    $rel->closeLine();

                    $rel->newLine();
                    $rel->newCel("&nbsp;", array("colspan"=>"6"));
                    $rel->closeLine();

                    $totValorServico = 0;
                    $totDesconto = 0;
                    $totValorComDesconto = 0;
                    $totValorRecebido = 0;
                    $totValorReceber = 0;
                }

                $lastAluno = $row["nome"];

                $rel->newLine();
                $rel->newCel($row["nome"], array("colspan"=>"6", "style"=>"font-weight:bold;font-size:12pt;color:navy;"));
                $rel->closeLine();
            }
            $valorComDesconto = $row["valorservico"] - $row["desconto"];
            $rel->newLine();
            $rel->newCel(db_to_date($row["data"]));
            $rel->newCel($row["descricao"]);
            $rel->newCel(db_to_float($row["valorservico"]), array("align"=>"right"));
            $rel->newCel(db_to_float($row["desconto"]), array("align"=>"right"));
            $rel->newCel(db_to_float($valorComDesconto), array("align"=>"right"));
            $rel->newCel(db_to_float($row["valorconta"]), array("align"=>"right"));
            $rel->closeLine();
            
            $totValorServico += $row["valorservico"];
            $totDesconto += $row["desconto"];
            $totValorComDesconto += $valorComDesconto;
            $totValorRecebido += $row["valorconta"];
            $totGeralValorServico += $row["valorservico"];
            $totGeralDesconto += $row["desconto"];
            $totGeralValorComDesconto += $valorComDesconto;
            $totGeralValorRecebido += $row["valorconta"];
        }
        $rel->newLine(array("style"=>"font-weight:bold;"));
        $rel->newCel("Total do Aluno", array("colspan"=>"2"));
        $rel->newCel(db_to_float($totValorServico), array("align"=>"right"));
        $rel->newCel(db_to_float($totDesconto), array("align"=>"right"));
        $rel->newCel(db_to_float($totValorComDesconto), array("align"=>"right"));
        $rel->newCel(db_to_float($totValorRecebido), array("align"=>"right"));
        $rel->closeLine();

        $rel->newLine();
        $rel->newCel("&nbsp;", array("colspan"=>"6"));
        $rel->closeLine();

        $rel->newFoot();
        $rel->newLine(array("style"=>"font-weight:bold;"));
        $rel->newCel("Total Geral", array("colspan"=>"2"));
        $rel->newCel(db_to_float($totGeralValorServico), array("align"=>"right"));
        $rel->newCel(db_to_float($totGeralDesconto), array("align"=>"right"));
        $rel->newCel(db_to_float($totGeralValorComDesconto), array("align"=>"right"));
        $rel->newCel(db_to_float($totGeralValorRecebido), array("align"=>"right"));
        $rel->closeLine();
        $rel->closeFoot();
    }

    $rel->closeTable();
}

if (in_array("8", $opcoes)) {
    $rel->h2("Devedores por Período");

    $rel->openTable('tblDevedoresPorPeriodo', $attrTable);

    $rowDevedoresPorPeriodo = $mysql->select(
            "b.nome, coalesce(a.valor, 0) as valorservico, coalesce(a.desconto, 0) as desconto, c.data, d.descricao, coalesce(sum(c.valor), 0) as valorconta",
            "alunoservico a, valunos b, contasareceber c, tiposervicos d",
            "a.idaluno = b.id and a.id = c.idalunoservico and a.idtiposervico = d.id and ".
                "a.data between DATE('".$vDataI->format('Y-m-d')."') and DATE('".$vDataF->format('Y-m-d')."') ",
            "group by b.nome, a.valor, a.desconto, c.data, d.descricao ".
                "having coalesce(a.valor, 0) - coalesce(sum(c.valor), 0) > 0",
            "c.data, b.nome");
    //echo $mysql->getMsgErro();

    $rel->newLine();
    $rel->newCelHeader("Data Serviço");
    $rel->newCelHeader("Aluno");
    $rel->newCelHeader("Serviço");
    $rel->newCelHeader("Valor do Serviço");
    $rel->newCelHeader("Desconto do Serviço");
    $rel->newCelHeader("Valor com Desconto");
    $rel->newCelHeader("Valor Recebido");
    $rel->newCelHeader("Valor a Receber");
    $rel->closeLine();

    $totValorServico = 0;
    $totDesconto = 0;
    $totValorComDesconto = 0;
    $totValorRecebido = 0;
    $totValorReceber = 0;
    if (is_array($rowDevedoresPorPeriodo)) {
        foreach ($rowDevedoresPorPeriodo as $row) {
            
            $valorComDesconto = $row["valorservico"] - $row["desconto"];
            $valorAReceber = $valorComDesconto - $row["valorconta"];

            $rel->newLine();
            $rel->newCel(db_to_date($row["data"]));
            $rel->newCel($row["nome"]);
            $rel->newCel($row["descricao"]);
            $rel->newCel(db_to_float($row["valorservico"]), array("align"=>"right"));
            $rel->newCel(db_to_float($row["desconto"]), array("align"=>"right"));
            $rel->newCel(db_to_float($valorComDesconto), array("align"=>"right"));
            $rel->newCel(db_to_float($row["valorconta"]), array("align"=>"right"));
            $rel->newCel(db_to_float($valorAReceber), array("align"=>"right"));
            $rel->closeLine();

            $totValorServico += $row["valorservico"];
            $totDesconto += $row["desconto"];
            $totValorComDesconto += $valorComDesconto;
            $totValorRecebido += $row["valorconta"];
            $totValorReceber += $valorAReceber;
        }

        $rel->newFoot();
        $rel->newLine(array("style"=>"font-weight:bold;"));
        $rel->newCel("Total Geral", array("colspan"=>"3"));
        $rel->newCel(db_to_float($totValorServico), array("align"=>"right"));
        $rel->newCel(db_to_float($totDesconto), array("align"=>"right"));
        $rel->newCel(db_to_float($totValorComDesconto), array("align"=>"right"));
        $rel->newCel(db_to_float($totValorRecebido), array("align"=>"right"));
        $rel->newCel(db_to_float($totValorReceber), array("align"=>"right"));
        $rel->closeLine();
        $rel->closeFoot();
    }

    $rel->closeTable();

}

$rel->close();

?>