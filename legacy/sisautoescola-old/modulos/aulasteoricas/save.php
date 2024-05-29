<?php
include_once("../../configuracao.php");

$id = 0;
$idaluno = $_POST["idaluno"];
$idturma = $_POST["idturma"];

$validacao = true;
$msgValidacao = "";
$tmp_value = "";

$fields = null;

$mysql = new modulos_global_mysql();

if (!isset ($idaluno) or !is_numeric($idaluno)) {
    $msgValidacao["msg"][] = "Campo 'Aluno' é de preenchimento obrigatório.";
    $validacao = false;
}
if (!isset ($idturma) or !is_numeric($idturma)) {
    $msgValidacao["msg"][] = "Campo 'Turma' é de preenchimento obrigatório.";
    $validacao = false;
}

$countexistente = $mysql->getValue(
                        'count(id) as total',
                        'total',
                        'aulasteoricas',
                        "idturma = '".$idturma."' and idaluno = '".$idaluno."'");
if ($countexistente > 0) {
    $msgValidacao["msg"][] = "Este aluno já está nesta turma.";
    $validacao = false;
}

if ($validacao) {
    $qtdAlunos = $mysql->getValue(
                            'qtdalunos',
                            null,
                            'turmas',
                            "id = '".$idturma."'");
    $totTurma = $mysql->getValue(
                            'count(*) as total',
                            'total',
                            'aulasteoricas',
                            "idturma = '".$idturma."'");
    if ($totTurma >= $qtdAlunos) {
        $msgValidacao["msg"][] = "Esta turma já atingiu o número máximo de alunos.";
        $validacao = false;
    }
}

if ($validacao) {
    $countexistente = $mysql->getValue(
                        'count(id) as total',
                        'total',
                        'aulasteoricas',
                        "idaluno = '".$idaluno."'");
    
    if ($countexistente > 0) {
        $msgValidacao["msg"][] = "Este aluno já está em outra turma.";
    }
}

$fields["idaluno"] = $idaluno;
$fields["idturma"] = $idturma;

if ($validacao) {
    $id = $mysql->save($id, 'aulasteoricas', $fields, "id = '".$id."'");
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

echo json_encode($msgValidacao);

?>