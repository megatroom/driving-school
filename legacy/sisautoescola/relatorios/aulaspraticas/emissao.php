<?php
include_once("../../configuracao.php");

$tipoRel = $_GET["tipoRel"];
$idcarro = $_GET["idcarro"];
$data = $_GET["data"];
$turno = $_GET["turno"];

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

$rel = new modulos_global_relatorio($tipoRel, "Diário de Aula Prática", $_SERVER["PHP_SELF"] . $args);

$mysql = new modulos_global_mysql();

if (!is_valid_date($data)) {
    $rel->alertAndClose('Data informada inválida!');
    $rel->close();
    exit;
}

$dataValid = new DateTime(date_to_db($data));
$diasemana = date_format($dataValid, 'w') + 1;

$expedientes = $mysql->select(
        'horai, horaf',
        "expediente",
        "idturno = '".$turno."' and diasemana = '".$diasemana."'");

$horai = null;
$horaf = null;
if (is_array($expedientes)) {
    foreach ($expedientes as $expediente) {
        $horai = $expediente["horai"];
        $horaf = $expediente["horaf"];
    }
} else {
    $rel->alertAndClose('Não há expediente para o turno/data informada!');
    $rel->close();
    exit;
}

$duracaoaula = $mysql->getValue('duracaoaula', null, 'turnos', "id = '".$turno."'");

$whereFiltroCarro = "";
if (isset ($idcarro) and $idcarro > 0) {
    $whereFiltroCarro = "and b.id = '".$idcarro."'";
}

$lstCarros = $mysql->select(
        "distinct b.carro, a.idcarro",
        "carrofuncionario a, vcarros b, vfuncionarios c, aulaspraticas d",
        "a.idcarro = b.id and a.idfuncionario = c.id and d.idcarro = a.idcarro ".
            "and a.data = ".
            "(select max(data) ".
            "from carrofuncionario x ".
            "where x.id = a.id and x.data <= date('".  date_to_db($data) ."')) ".
            $whereFiltroCarro,
        null,
        "carro");

if (!is_array($lstCarros)) {
    $rel->alertAndClose('Não há carro com instrutores no banco de dados!');
    $rel->close();
    exit;
}

$quebrarPagina = false;
$lastCarro = 0;

foreach ($lstCarros as $carro) {

    if ($quebrarPagina) {
        $rel->pageBreak();
    } else {
        $quebrarPagina = true;
    }

    $rowNomeInstrutor = $mysql->select(
            'b.nome',
            'carrofuncionario a, vfuncionarios b',
            "a.idfuncionario = b.id and a.idcarro= '".$carro["idcarro"]."' ".
                "and a.data = (select max(b.data) from carrofuncionario b where a.idcarro = b.idcarro and b.data <= '".  date_to_db($data) ."') ",
            null,
            "a.hora desc");
    //echo "SQL1 - ".$mysql->getMsgErro();
    $nomeInstrutor = "";
    if (is_array($rowNomeInstrutor)) {
        $nomeInstrutor = $rowNomeInstrutor[0]["nome"];
    }
    tabelaInstrutor($rel, "", $data, $nomeInstrutor, $carro["carro"]);

    $linhas = $mysql->select(
        "distinct a.idcarro, a.hora, b.nome, b.matriculacfc, a.comentario",
        "aulaspraticas a, valunos b, vcarros c",
        "a.idaluno = b.id ".
        "and a.idcarro = c.id ".
        "and a.idcarro = '".$carro["idcarro"]."' ".
        "and a.data = '".  date_to_db($data) ."' ",
        "union ".
            "select x.idcarro, x.hora, '*** BLOQUEADO ***', '***', x.motivo ".
            "from aulaspraticasbloqueio x ".
            "where x.data = '".  date_to_db($data) ."' ".
            "and x.idcarro = '".$carro["idcarro"]."' ",
        "1, 2");
    //echo "SQL2 - ". $mysql->getMsgErro() ."<br><br>";

    $datacount = new DateTime(date_to_db($data) ." ". $horai);
    $datacond  = new DateTime(date_to_db($data) ." ". $horaf);
    while ($datacount <= $datacond) {

        $rel->newLine();
        $rel->newCel(date_format($datacount, 'H:i'), array("align"=>"center"));

        if (is_array($linhas)) {
            $key = getIndexArraySelect($linhas, "hora", date_format($datacount, 'H:i:s'));
        } else {
            $key = -1;
        }
        if ($key >= 0) {
            $rel->newCel($linhas[$key]["matriculacfc"], array("align"=>"center"));
            $rel->newCel($linhas[$key]["nome"]);
            $rel->newCel($linhas[$key]["comentario"]);
        } else {
            $rel->newCel('&nbsp;', array("align"=>"right"));
            $rel->newCel('&nbsp;');
            $rel->newCel('&nbsp;');
        }

        $rel->newCel("&nbsp;");
        $rel->closeLine();

        //echo date_format($datacount, 'd/m/Y H:i') ."<br>";

        $datacount->add(new DateInterval('PT'.$duracaoaula.'M'));
    }

    $rel->closeTable();

    $rel->divClear("40px");

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
    $rel->newCel($data." (".  diasemanaextenso($data).") &nbsp;&nbsp;&nbsp;");
    $rel->newCel("Instrutor:");
    $rel->newCel($nomeFunc." &nbsp;&nbsp;&nbsp;");
    $rel->newCel("Carro:");
    $rel->newCel($carro);
    $rel->closeLine();
    $rel->closeTable();

    $rel->divClear("10px");

    $rel->openTable('tblRelAPAlunosProf'.$func, array('border'=> '1', 'cellpadding' => '0', "style" => "width:100%;"));
    $rel->newLine(array('align'=>'center'));
    $rel->newCel("Hora", array("width"=>"5%"));
    $rel->newCel("Matrícula CFC", array("width"=>"10%"));
    $rel->newCel("Aluno");
    $rel->newCel("Comentários", array("width"=>"20%"));
    $rel->newCel("Assinatura do Aluno", array("width"=>"20%"));
    $rel->closeLine();
}

?>