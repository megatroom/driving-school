<?php

include_once("../../configuracao.php");

$id             = $_POST["id"];
$senha          = $_POST["senha"];
$senhaconfirm   = $_POST["senhaconfirm"];

$validacao = true;
$msgValidacao = "";
$tmp_value = "";

$fields = null;

$mysql = new modulos_global_mysql();

if (!isset ($senha) or strlen($senha) == 0) {
    $msgValidacao["msg"][] = "Campo 'Senha' é de preenchimento obrigatório.";
    $validacao = false;
} else {
    if (strlen($senha) > 0 and $senha != $senhaconfirm) {
        $msgValidacao["msg"][] = "A confirmação da senha está diferente da senha digitada.";
        $validacao = false;
    } else if (strlen($senha) < 4) {
        $msgValidacao["msg"][] = "A senha deve conter mais de 4 caracteres.";
        $validacao = false;
    } else {
        $fields["senha"] = "md5('". $senha ."')";
    }
}

if ($validacao) {
    $id = $mysql->save($id, 'usuarios', $fields, "id = '".$id."'");
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