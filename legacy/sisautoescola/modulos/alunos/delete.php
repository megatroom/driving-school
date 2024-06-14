<?php

include_once("../../configuracao.php");

$id = $_POST["id"];

$validacao = true;
$msgValidacao = "";

$mysql = new modulos_global_mysql();

if (!isset ($id) or !is_numeric($id) or $id < 1) {
    $msgValidacao["msg"][] = "O código não foi informado.";
    $validacao = false;
}

$countUso = $mysql->getValue('count(id) as total', 'total', 'aulaspraticas', "idaluno = '".$id."'");

if ($countUso > 0) {
    $msgValidacao["msg"][] = "Este aluno já foi utilizado na aula prática e não pode ser excluído.";
    $validacao = false;
}

$countUso = $mysql->getValue('count(id) as total', 'total', 'aulasteoricas', "idaluno = '".$id."'");

if ($countUso > 0) {
    $msgValidacao["msg"][] = "Este aluno já foi utilizado na aula teórica e não pode ser excluído.";
    $validacao = false;
}

$countUso = $mysql->getValue('count(id) as total', 'total', 'agendamentos', "idaluno = '".$id."'");

if ($countUso > 0) {
    $msgValidacao["msg"][] = "Este aluno já foi utilizado no agendamento e não pode ser excluído.";
    $validacao = false;
}

$countUso = $mysql->getValue('count(id) as total', 'total', 'examepraticoalunos', "idaluno = '".$id."'");

if ($countUso > 0) {
    $msgValidacao["msg"][] = "Este aluno já foi utilizado no exame prático e não pode ser excluído.";
    $validacao = false;
}

$countUso = $mysql->getValue('count(id) as total', 'total', 'alunoservico', "idaluno = '".$id."'");

if ($countUso > 0) {
    $msgValidacao["msg"][] = "Este aluno já foi utilizado no serviço e não pode ser excluído.";
    $validacao = false;
}

if ($validacao) {
    $idpessoa = $mysql->getValue('idpessoa', null, 'alunos', "id = '".$id."'");

    $pessoa = new modulos_pessoas_pessoa($idpessoa);

    if ($pessoa->isDeleted('alunos')) {
        if (!$mysql->delete('pessoas', "id = '".$idpessoa."'")) {
            $msgValidacao["retornoStatus"][] = "erro";
            $msgValidacao["titulo"][] = "Ocorreu um erro no banco de dados.";
            $msgValidacao["msg"][] = "Mensagem técnica: ". $mysql->getMsgErro();
            echo json_encode($msgValidacao);
            exit;
        }
    }

    if ($mysql->delete('alunos', "id = '".$id."'")) {
        $msgValidacao["retornoStatus"][] = "delete";
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