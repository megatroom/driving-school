<?php
include_once("../../configuracao.php");

$datai = $_GET["datai"];
$dataf = $_GET["dataf"];
$tipo  = $_GET["tipo"];
$idFun = $_GET["idfun"];
$opcao = $_GET["opcao"];

$args = "";
foreach ($_GET as $key => $value) {
    $val = $value;
    if ($key == "tiporel") {
        $val = "2";
    }
    if ($args == "") {
        $args = "?".$key."=".$val;
    } else {
        $args .= "&".$key."=".$val;
    }
}

$rel = new modulos_global_relatorio($tipo, "Exame Prático - Resultados", $_SERVER["PHP_SELF"] . $args);

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
            $cabecalho = str_replace("\n",'<br />', $cabecalhoRel["valor"]);
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

$rows = $mysql->select(
        "a.id, a.data, a.hora, a.idcarro",
        "aulaspraticas a",
        "a.data between '".date_to_db($datai)."' and '".date_to_db($dataf)."'",
        null,
        "a.data, a.hora");

$lstFunCarro = null;
$whereFunCarro = "";
if (isset($idcarro) and is_numeric($idcarro) and $idcarro > 0) {
    $whereFunCarro = "and a.idcarro = '".$idcarro."' ";
}
$rowsFunCarro = $mysql->select(
        "a.data, a.hora, a.idcarro, a.idfuncionario, b.nome, d.comissao",
        "carrofuncionario a, vfuncionarios b, carros c, tipocarros d",
        "a.idfuncionario = b.id and a.idcarro = c.id and c.idtipocarro = d.id ".
            $whereFunCarro,
        null,
        "data, hora");
if (is_array($rowsFunCarro)) {
    foreach ($rowsFunCarro as $row) {
        $lstFunCarro[$row["idcarro"]][] = array(
            "datahora" => $row["data"] ." ". $row["hora"],
            "idfuncionario" => $row["idfuncionario"],
            "nome" => $row["nome"],
            "comissao" => $row["comissao"]);
    }
    unset ($rowsFunCarro);
} else {
    echo '<h3>Não há nenhum instrutor lançado para o(s) carro(s) neste período.</h3>';
    exit;
}

function getNomeInstrutor($pLista, $pIdCarro, $pDataHora) {
    $retorno = "";
    if (is_array($pLista[$pIdCarro])) {
        foreach ($pLista[$pIdCarro] as $value) {
            $vDataHora = new DateTime($value["datahora"]);
            if ($pDataHora >= $vDataHora) {
                $retorno = array("nome" => $value["nome"], "id" => $value["idfuncionario"], "comissao" => $value["comissao"]);
            }
        }
    }
    return $retorno;
}

$tblDataFun = null;
if (is_array($rows)) {
    /*
     * Monta a lista com as datas + instrutores
     */
    foreach ($rows as $row) {
        $auxFun = getNomeInstrutor($lstFunCarro, $row["idcarro"], new DateTime($row["data"]." ".$row["hora"]));
        $tblDataFun[] = array(
            "data" => $row["data"],
            "id" => $auxFun["id"],
            "nome" => $auxFun["nome"],
            "comissao" => $auxFun["comissao"]);
    }
    /*
     *
     */
    $funcionario = null;
    foreach ($tblDataFun as $row) {
        if ($opcao == "A") {
            $count = 0;            
            if (is_array($funcionario) && array_key_exists($row["id"], $funcionario)) {
                $count = $funcionario[$row["id"]][$row["data"]]["count"] + 1;
            }
            
            $funcionario[$row["id"]][$row["data"]] = 
                array(
                    "nome" => $row["nome"],
                    "count" => $count,
                    "comissao" => $row["comissao"]);
        } else {
            $funcionario[$row["id"]] = 
                array(
                    "nome" => $row["nome"],
                    "count" => $funcionario[$row["id"]]["count"] + 1,
                    "comissao" => $row["comissao"]);
        }
    }

    $rel->openTable("tblRel", array("border"=>"1"));
    $rel->newLine();
    $rel->newCelHeader("Instrutor");
    if ($opcao == "A") {
        $rel->newCelHeader("Data");
    }
    $rel->newCelHeader("Aulas");
    $rel->newCelHeader("Valor");
    $rel->closeLine();
    $impFun = true;
    foreach ($funcionario as $key => $value) {
        if ($idFun > 0) {
            if ($idFun == $key) {
                $impFun = true;
            } else {
                $impFun = false;
            }
        }
        if ($impFun) {
            if ($opcao == "A") {
                foreach ($value as $x => $y) {
                    $rel->newLine();
                    $rel->newCel($y["nome"]);
                    $rel->newCel(db_to_date($x));
                    $rel->newCel($y["count"], array("align"=>"right"));
                    $rel->newCel(db_to_float($y["count"] * $y["comissao"]), array("align"=>"right"));
                    $rel->closeLine();
                }
            } else {
                $rel->newLine();
                $rel->newCel($value["nome"]);
                $rel->newCel($value["count"], array("align"=>"right"));
                $rel->newCel(db_to_float($value["count"] * $value["comissao"]), array("align"=>"right"));
                $rel->closeLine();
            }
        }
    }
    $rel->closeTable();
}


$rel->close();

?>