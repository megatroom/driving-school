<?php
include_once("../../configuracao.php");

$idexamepratico = 0;
$idaluno = $_POST["idaluno"];
$idexamepratico = $_POST["idexamepratico"];
$idcarro = $_POST["idcarro"];

$validacao = true;
$msgValidacao = "";
$tmp_value = "";

$mysql = new modulos_global_mysql();

$idExamePraticoCarro = $mysql->getValue(
        "id",
        "id",
        "examepraticocarro",
        "idexamepratico = '".$idexamepratico."' and idcarro = '".$idcarro."'");

if (!isset ($idExamePraticoCarro) or $idExamePraticoCarro == "" or !is_numeric($idExamePraticoCarro)) {
    $idExamePraticoCarro = 0;
}

//echo $mysql->getMsgErro();

if (!isset ($idaluno) or !is_numeric($idaluno)) {
    $msgValidacao["msg"][] = "Campo 'Aluno' é de preenchimento obrigatório.";
    $validacao = false;
}

if (!isset ($idcarro) or !is_numeric($idcarro)) {
    $msgValidacao["msg"][] = "Campo 'Carro' é de preenchimento obrigatório.";
    $validacao = false;
}

$countexistente = $mysql->getValue(
                        'count(id) as total',
                        'total',
                        'examepraticoalunos',
                        "idexamepraticocarro = '".$idExamePraticoCarro."' and idaluno = '".$idaluno."'");
if ($countexistente > 0) {
    $msgValidacao["msg"][] = "Este aluno já está neste exame.";
    $validacao = false;
} else {
    $idexamepraticoaluno = $mysql->getValue(
                        'id',
                        'id',
                        'examepraticoalunos',
                        "idexamepraticocarro = '".$idExamePraticoCarro."' and idaluno = '".$idaluno."'");
}

if ($validacao) {
    if ($idExamePraticoCarro == 0) {
        $fields = null;
        $fields["idexamepratico"] = $idexamepratico;
        $fields["idcarro"] = $idcarro;

        $idExamePraticoCarro = $mysql->save(0, 'examepraticocarro', $fields);

        if (!$idExamePraticoCarro) {
            $msgValidacao["retornoStatus"][] = "erro";
            $msgValidacao["titulo"][] = "Ocorreu um erro no banco de dados.";
            $msgValidacao["msg"][] = "Mensagem técnica: ". $mysql->getMsgErro();
            echo json_encode($msgValidacao);
            exit;
        }
    }
} else {
    $msgValidacao["retornoStatus"][] = "validacao";
    $msgValidacao["titulo"][] = "Aviso.";
    echo json_encode($msgValidacao);
    exit;
}

//echo $mysql->getMsgErro();

$fields = null;
$fields["idaluno"] = $idaluno;
$fields["idexamepraticocarro"] = $idExamePraticoCarro;
$fields["resultado"] = "'N'";
if ($validacao) {
    $id = $mysql->save($idexamepraticoaluno, 'examepraticoalunos', $fields, "id = '".$idexamepraticoaluno."'");
    if ($id) {
        $msgValidacao["retornoStatus"][] = "save";
    } else {
        $msgValidacao["retornoStatus"][] = "erro";
        $msgValidacao["titulo"][] = "Ocorreu um erro no banco de dados.";
        $msgValidacao["msg"][] = "Mensagem técnica: ". $mysql->getMsgErro();
        echo json_encode($msgValidacao);
        exit;
    }
} else {
    $msgValidacao["retornoStatus"][] = "validacao";
    $msgValidacao["titulo"][] = "Aviso.";
    echo json_encode($msgValidacao);
    exit;
}

//echo $mysql->getMsgErro();

echo json_encode($msgValidacao);

?>