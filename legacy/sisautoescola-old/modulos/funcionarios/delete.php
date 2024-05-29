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

$countUso = $mysql->getValue('count(id) as total', 'total', 'carrofuncionario', "idfuncionario = '".$id."'");

if ($countUso > 0) {
    $msgValidacao["msg"][] = "Este funcionário já foi utilizado em carro e não pode ser excluído.";
    $validacao = false;
}

$countUso = $mysql->getValue('count(id) as total', 'total', 'usuarios', "idfuncionario = '".$id."'");

if ($countUso > 0) {
    $msgValidacao["msg"][] = "Este funcionário já foi utilizado em usuário e não pode ser excluído.";
    $validacao = false;
}

$countUso = $mysql->getValue('count(id) as total', 'total', 'turmas', "idfuncionario = '".$id."'");

if ($countUso > 0) {
    $msgValidacao["msg"][] = "Este funcionário já foi utilizado em uma turma e não pode ser excluído.";
    $validacao = false;
}

if ($validacao) {
    $idpessoa = $mysql->getValue('idpessoa', null, 'funcionarios', "id = '".$id."'");

    $pessoa = new modulos_pessoas_pessoa($idpessoa);

    if ($pessoa->isDeleted('funcionarios')) {
        if (!$mysql->delete('pessoas', "id = '".$idpessoa."'")) {
            $msgValidacao["retornoStatus"][] = "erro";
            $msgValidacao["titulo"][] = "Ocorreu um erro no banco de dados.";
            $msgValidacao["msg"][] = "Mensagem técnica: ". $mysql->getMsgErro();
            echo json_encode($msgValidacao);
            exit;
        }
    }

    if ($mysql->delete('funcionarios', "id = '".$id."'")) {
        $msgValidacao["retornoStatus"][] = "delete";
    } else {
        $msgValidacao["retornoStatus"][] = "erro";
        $msgValidacao["titulo"][] = "Ocorreu um erro no banco de dados.";
        $msgValidacao["msg"][] = "Mensagem técnica: ". $mysql->getMsgErro();
    }
} else {
    $msgValidacao["retornoStatus"][] = "validacao";
    $msgValidacao["titulo"][] = "Aviso.";
}

echo json_encode($msgValidacao);

?>
