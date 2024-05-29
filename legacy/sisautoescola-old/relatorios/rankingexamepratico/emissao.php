<?php
include_once("../../configuracao.php");

$tipo  = $_GET["tipo"];
$datai = $_GET["datai"];
$dataf = $_GET["dataf"];
$relTipo1 = $_GET["tipo1"];
$relTipo2 = $_GET["tipo2"];
$relTipo3 = $_GET["tipo3"];

$mysql = new modulos_global_mysql();

$rel = new modulos_global_relatorio($tipo, "Tipo de Serviços", $_SERVER["PHP_SELF"] . $args);

$pWhere  = "c.idexamepratico = e.id ";
$pWhere .= "and a.idexamepraticocarro = c.id ";
$pWhere .= "and e.data between '".date_to_db($datai)."' and '".date_to_db($dataf)."' ";

$rowCount = $mysql->getValue(
        "count(*) as total",
        "total",
        "examepratico e, examepraticocarro c, examepraticoalunos a",
        $pWhere);
if ($rowCount == 0) {
    $rel->alertAndClose('Não há nenhum registro para o período informado!');
    $rel->close();
    exit;
}

$rows = $mysql->select(
        "e.data, c.idcarro, a.resultado, count(*) as total",
        "examepratico e, examepraticocarro c, examepraticoalunos a",
        $pWhere,
        "group by e.data, c.idcarro, a.resultado",
        "e.data");
//echo $mysql->getMsgErro();

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

$tuplas = null;
foreach ($rows as $row) {
    $tmpIdInstrutor = $mysql->getValue(
            'b.id',
            'id',
            'carrofuncionario a, vfuncionarios b',
            "a.idfuncionario = b.id and a.idcarro= '".$row["idcarro"]."' ".
                "and a.data = (select max(b.data) from carrofuncionario b where a.idcarro = b.idcarro and b.data <= '". $row["data"] ."') ",
            null,
            "a.hora desc");

    $tmpNomeInstrutor = $mysql->getValue(
            'nome',
            null,
            'vfuncionarios',
            "id = '".$tmpIdInstrutor."'");

    $tuplas[] = array (
        'id' => $tmpIdInstrutor,
        'nome' => $tmpNomeInstrutor,
        'resultado' => $row["resultado"],
        'total' => $row["total"]
    );
}
$instrutores = null;
foreach ($tuplas as $tupla) {
    if ($instrutores == null) {
        $instrutores[] = array(
            "id" => $tupla["id"],
            "nome" => $tupla["nome"],
            "resultado" => $tupla["resultado"],
            "total" => $tupla["total"]
        );
    } else {
        $isFound = false;
        foreach ($instrutores as $key => $value) {
            if ($value["id"] == $tupla["id"] && $value["resultado"] == $tupla["resultado"]) {
                $isFound = true;
                $instrutores[$key]["total"] += $tupla["total"];
                break;
            }
        }
        if ($isFound == false) {
            $instrutores[] = array(
                "id" => $tupla["id"],
                "nome" => $tupla["nome"],
                "resultado" => $tupla["resultado"],
                "total" => $tupla["total"]
            );
        }
    }
}

$completo = null;
foreach ($instrutores as $instrutor) {
    $val["A"] = 0;
    $val["R"] = 0;
    $val["M"] = 0;
    $val["T"] = 0;
    $val["N"] = 0;
    $val["F"] = 0;
    if ($completo == null) {        
        $val[$instrutor["resultado"]] = $instrutor["total"];
        $completo[] = array(
            "id" => $instrutor["id"],
            "nome" => $instrutor["nome"],
            "resultado" => $val,
            "aproveitamento" => 0
        );
    } else {
        $isFound = false;
        foreach ($completo as $key => $value) {
            if ($value["id"] == $instrutor["id"]) {
                $isFound = true;
                $completo[$key]["resultado"][$instrutor["resultado"]] += $instrutor["total"];
                if ($completo[$key]["resultado"]["A"] > 0) {
                    $completo[$key]["aproveitamento"] =
                        ($completo[$key]["resultado"]["A"] * 100) /
                        ($completo[$key]["resultado"]["A"] + $completo[$key]["resultado"]["R"]);
                }
                break;
            }
        }
        if ($isFound == false) {
            $val[$instrutor["resultado"]] = $instrutor["total"];
            $completo[] = array(
                "id" => $instrutor["id"],
                "nome" => $instrutor["nome"],
                "resultado" => $val,
                "aproveitamento" => 0
            );
        }
    }
}

$rel->h2("Período: ".$datai." a ".$dataf);

if ($relTipo1 == "S") {
    $rel->divClear(10);

    $ranking = null;    
    $countRow = 0;
    foreach ($completo as $rowLine) {
        $maxValor = 0;
        $maxId = 0;
        $maxNome = "";
        foreach ($completo as $linha) {
            $isFound = false;
            if ($ranking != null) {
                foreach ($ranking as $ranLine) {
                    if ($ranLine["id"] == $linha["id"]) {
                        $isFound = true;
                        break;
                    }
                }
            }
            if ($isFound == false && $linha["resultado"]["A"] >= $maxValor) {
                $maxValor = $linha["resultado"]["A"];
                $maxId = $linha["id"];
                $maxNome = $linha["nome"];
            }
        }
        $ranking[$countRow]["ordem"] = ($countRow + 1)."º";
        $ranking[$countRow]["id"] = $maxId;
        $ranking[$countRow]["nome"] = $maxNome;
        $ranking[$countRow]["valor"] = $maxValor;
        $maxValorLast = $maxValor;
        $countRow++;
    }

    $attr = null;
    $attr["border"] = "1";
    $attr["cellpadding"] = "5px";
    $rel->openTable('tblRanking2', $attr);
    $rel->newLine();
    $rel->newCelHeader('Ranking');
    $rel->newCelHeader('Instrutor');
    $rel->newCelHeader('Total');
    $rel->closeLine();

    foreach ($ranking as $value) {
        $rel->newLine();
        $rel->newCel($value["ordem"]);
        $rel->newCel($value["nome"]);
        $rel->newCel($value["valor"], array("align"=>"center"));
        $rel->closeLine();
    }

    $rel->closeTable();
}

if ($relTipo2 == "S") {
    if ($relTipo1 == "S") {
        $rel->divClear("10px");
    }

    $ranking = null;
    $countRow = 0;
    foreach ($completo as $rowLine) {
        $maxValor = 0;
        $maxId = 0;
        $maxAprov = 0;
        $maxReprov = 0;
        $maxNome = "";
        foreach ($completo as $linha) {
            $isFound = false;
            if ($ranking != null) {
                foreach ($ranking as $ranLine) {
                    if ($ranLine["id"] == $linha["id"]) {
                        $isFound = true;
                        break;
                    }
                }
            }
            if ($isFound == false && $linha["aproveitamento"] >= $maxValor) {
                $maxValor = $linha["aproveitamento"];
                $maxId = $linha["id"];
                $maxNome = $linha["nome"];
                $maxAprov = $linha["resultado"]["A"];
                $maxReprov = $linha["resultado"]["R"];
            }
        }
        $ranking[$countRow]["ordem"] = ($countRow + 1)."º";
        $ranking[$countRow]["id"] = $maxId;
        $ranking[$countRow]["nome"] = $maxNome;
        $ranking[$countRow]["valor"] = $maxValor;
        $ranking[$countRow]["A"] = $maxAprov;
        $ranking[$countRow]["R"] = $maxReprov;
        $maxValorLast = $maxValor;
        $countRow++;
    }

    $attr = null;
    $attr["border"] = "1";
    $attr["cellpadding"] = "5px";
    $rel->openTable('tblRanking2', $attr);
    $rel->newLine();
    $rel->newCelHeader('Ranking');
    $rel->newCelHeader('Instrutor');
    $rel->newCelHeader('Aprovação');
    $rel->newCelHeader('Reprovação');
    $rel->newCelHeader('Aproveitamento');
    $rel->closeLine();

    foreach ($ranking as $value) {
        $rel->newLine();
        $rel->newCel($value["ordem"]);
        $rel->newCel($value["nome"]);
        $rel->newCel($value["A"], array("align"=>"center"));
        $rel->newCel($value["R"], array("align"=>"center"));
        $rel->newCel(db_to_float($value["valor"])."%", array("align"=>"right"));
        $rel->closeLine();
    }

    $rel->closeTable();
}

if ($relTipo3 == "S") {
    if ($relTipo1 == "S" || $relTipo2 == "S") {
        $rel->divClear("10px");
    }

    $attr = null;
    $attr["border"] = "1";
    $attr["cellpadding"] = "5px";
    $rel->openTable('tblRanking2', $attr);
    $rel->newLine();
    $rel->newCelHeader('Instrutor');
    $rel->newCelHeader('Resultados');
    $rel->newCelHeader('Total');
    $rel->closeLine();

    foreach ($completo as $linha) {
        $nomeInstrutor = $linha["nome"];
        $countLine = 1;
        foreach ($linha["resultado"] as $key => $value) {
            $rel->newLine();
            if ($countLine == 1) {
                $rel->newCel($nomeInstrutor, array("rowspan" => "6"));
            }
            $rel->newCel(examepratico_resultado_to_str($key));
            $rel->newCel($value);
            $rel->closeLine();
            $countLine++;
        }
    }

    $rel->closeTable();
}

$rel->close();
?>