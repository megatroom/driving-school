<?php

include_once("../../configuracao.php");

$id = $_POST["id"];
$descricao = $_POST["descricao"];
$itens = explode("|", $_POST["itens"]);

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

if ($id > 0) {
    $where = "id = ".$id;
}

if ($validacao) {
    $id = $mysql->save($id, 'gruposusuario', $fields, $where);
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

$mysql->delete('usuariosgrupousuario', "idgrupousuario = '".$id."'");

$fieldsAcesso = null;
if (is_array($itens)) {
    foreach ($itens as $value) {
        $fieldsAcesso = null;
        $fieldsAcesso["idgrupousuario"] = $id;
        $fieldsAcesso["idusuario"] = $value;
        $mysql->save(0, 'usuariosgrupousuario', $fieldsAcesso);
    }
}

echo json_encode($msgValidacao);

?>