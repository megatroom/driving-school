<?php
include_once("../../configuracao.php");

$id = $_POST["id"];
$descricao = $_POST["descricao"];
$comissao = $_POST["comissao"];

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
if (isset ($comissao) and is_numeric(float_to_db($comissao))) {
    $fields['comissao'] = "'". float_to_db($comissao) ."'";
} else {
    $msgValidacao["msg"][] = "Campo 'Comissão' é de preenchimento obrigatório.";
    $validacao = false;
}

$countfuncao = $mysql->getValue(
                        'count(id) as total',
                        'total',
                        'tipocarros',
                        "lower(descricao) = lower('".$descricao."') and id <> '".$id."'");

if ($countfuncao > 0) {
    $msgValidacao["msg"][] = "Já existe um tipo cadastrada com esse nome";
    $validacao = false;
}

if ($id > 0) {
    $where = "id = ".$id;
}

if ($validacao) {
    if ($mysql->save($id, 'tipocarros', $fields, $where)) {
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