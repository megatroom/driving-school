<?php
include_once("../../configuracao.php");

$idfuncionario = $_GET["idfuncionario"];
$data = $_GET["data"];

$rel = new modulos_global_relatorio(1, "Diário de Aula Prática", "@page { size:landscape; }");

$mysql = new modulos_global_mysql();

$rows = $mysql->select(
        "distinct (select vf.id ".
        "from carrofuncionario cf, vfuncionarios vf ".
        "where cf.idfuncionario = vf.id ".
        "and cf.idcarro = a.idcarro ".
        "and TIMESTAMP(cf.data, cf.hora) = ( ".
        "SELECT max(TIMESTAMP(x.data, x.hora)) ".
        "FROM carrofuncionario x ".
        "where TIMESTAMP(x.data, x.hora) <= TIMESTAMP(a.data, a.hora) ".
        "and x.idcarro = a.idcarro)) as instrutor",
        "aulaspraticas a, valunos b, vcarros c",
        "a.idaluno = b.id ".
        "and a.idcarro = c.id ".
        "and a.data = '".  date_to_db($data) ."'",
        null,
        "instrutor");

//echo $mysql->getMsgErro();

$lstFuncionarios = null;
$iCont = 0;
if (is_array($rows)) {
    foreach($rows as $row) {
        if (isset ($idfuncionario) and is_numeric($idfuncionario) and $idfuncionario > 0) {
            if ($idfuncionario == $row["instrutor"]) {
                $lstFuncionarios[] = $row["instrutor"];
                $iCont++;
            }
        } else {
            $lstFuncionarios[] = $row["instrutor"];
            $iCont++;
        }
    }
}

if ($iCont == 0) {
    $rel->alertAndClose("Não foi encontrado informações para o filtro selecionado!");
    $rel->close();
    exit;
}

if (isset ($_GET["pPrint"]) and $_GET["pPrint"] == "S") {
    $rel->printOnLoad();
}

$quebrarPagina = false;
$lastCarro = 0;

foreach ($lstFuncionarios as $func) {

    if ($quebrarPagina) {
        $rel->pageBreak();
    } else {
        $quebrarPagina = true;
    }

    $nomeFunc = $mysql->getValue('nome', NULL, 'vfuncionarios', "id = '".$func."'");    

    $linhas = $mysql->select(
        "distinct a.hora, b.nome, b.matriculacfc, a.comentario, a.idcarro, c.carro",
        "aulaspraticas a, valunos b, vcarros c",
        "a.idaluno = b.id ".
        "and a.idcarro = c.id ".
        "and (select vf.id ".
        "from carrofuncionario cf, vfuncionarios vf ".
        "where cf.idfuncionario = vf.id ".
        "and cf.idcarro = a.idcarro ".
        "and TIMESTAMP(cf.data, cf.hora) = ( ".
        "SELECT max(TIMESTAMP(x.data, x.hora)) ".
        "FROM carrofuncionario x ".
        "where TIMESTAMP(x.data, x.hora) <= TIMESTAMP(a.data, a.hora) ".
        "and x.idcarro = a.idcarro)) = '".$func."' ".
        "and a.data = '".  date_to_db($data) ."'",
        null,
        "idcarro, hora");
    //echo $mysql->getMsgErro() ." ==========";

    $lastCarro = 0;
    if (is_array($linhas)) {
        foreach ($linhas as $linha) {
            if ($lastCarro != $linha["idcarro"]) {
                tabelaInstrutor($rel, $func, $data, $nomeFunc, $linha["carro"]);
                $lastCarro = $linha["idcarro"];
            }
            
            $rel->newLine();
            $rel->newCel($linha["hora"], array("align"=>"center"));
            $rel->newCel($linha["matriculacfc"], array("align"=>"right"));
            $rel->newCel($linha["nome"]);
            $rel->newCel($linha["comentario"]);
            $rel->newCel("&nbsp;");
            $rel->closeLine();
        }
    } else {
        tabelaInstrutor($rel, $func, $data, $nomeFunc, "");
        
        $rel->newCel("Não há alunos lançados para este instrutor.", array("colspan" => "3"));
    }

    $rel->closeTable();

    $rel->divClear("10px");

    $rel->openTable("tblRelAPbottom", array("style" => "width:100%;"));
    $rel->newLine();
    $rel->newCel("KM INICIAL:", array("style" => "border-bottom: 1px #000000 solid;"));
    $rel->newCel("KM FINAL:", array("style" => "border-bottom: 1px #000000 solid;"));
    $rel->newCel("MÉDIA POR AULA:", array("style" => "border-bottom: 1px #000000 solid;"));
    $rel->closeLine();
    $rel->newLine();
    $rel->newCel("&nbsp;");
    $rel->newCel("QTD DE AULAS:", array("style" => "border-bottom: 1px #000000 solid;"));
    $rel->newCel("&nbsp;");
    $rel->closeLine();
    $rel->newLine();
    $rel->newCel("&nbsp;");
    $rel->newCel("VALOR TOTAL:", array("style" => "border-bottom: 1px #000000 solid;"));
    $rel->newCel("&nbsp;");
    $rel->closeLine();
    $rel->closeTable();
}

$rel->close();

function tabelaInstrutor($rel, $func, $data, $nomeFunc, $carro) {
    $rel->openTable('tblRelAPProf'.$func, array('border'=> '0', 'cellpadding' => '3'));
    $rel->newLine();
    $rel->newCel($data." &nbsp;&nbsp;&nbsp;", $rel->attrDadosLabel());
    $rel->newCel("Instrutor:", $rel->attrDadosLabel());
    $rel->newCel($nomeFunc." &nbsp;&nbsp;&nbsp;");
    $rel->newCel("Carro:", $rel->attrDadosLabel());
    $rel->newCel($carro);
    $rel->closeLine();
    $rel->closeTable();

    $rel->divClear("10px");

    $rel->openTable('tblRelAPAlunosProf'.$func, array('border'=> '1', 'cellpadding' => '3', "style" => "width:100%;"));
    $rel->newLine($rel->attrListaTitulo());
    $rel->newCel("Hora", array("width"=>"5%"));
    $rel->newCel("Matrícula CFC", array("width"=>"10%"));
    $rel->newCel("Aluno");
    $rel->newCel("Comentários", array("width"=>"20%"));
    $rel->newCel("Assinatura do Aluno", array("width"=>"20%"));
    $rel->closeLine();
}

?>