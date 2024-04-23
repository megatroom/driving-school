<?php
include_once("../../configuracao.php");

$id = $_POST["id"];
$descricao = $_POST["descricao"];
$status = $_POST["status"];

$validacao = true;
$msgValidacao = "";

$fields = null;
$where = null;

$mysql = new modulos_global_mysql();

if (isset ($descricao) and strlen($descricao) > 0) {
    $fields['descricao'] = "'". $descricao ."'";
} else {
    $msgValidacao["msg"][] = "Campo 'Descrição' é de preenchimento obrigatório.";
    $validacao = false;
}

if (isset ($status) and strlen($status) > 0) {
    $fields['status'] = "'". $status ."'";
} else {
    $msgValidacao["msg"][] = "Campo 'Status' é de preenchimento obrigatório.";
    $validacao = false;
}

$countfuncao = $mysql->getValue(
                        'count(id) as total',
                        'total',
                        'declaracoes',
                        "lower(descricao) = lower('".$descricao."') and id <> '".$id."'");

if ($countfuncao > 0) {
    $msgValidacao["msg"][] = "Já existe uma declaração cadastrada com essa descrição!";
    $validacao = false;
}

if ($id > 0) {
    $where = "id = ".$id;
}

if ($validacao) {
    if ($mysql->save($id, 'declaracoes', $fields, $where)) {
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