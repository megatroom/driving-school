<?php

include_once("../../configuracao.php");

$id             = $_POST["id"];
$login          = $_POST["login"];
$nome           = $_POST["nome"];
$senha          = $_POST["senha"];
$senhaconfirm   = $_POST["senhaconfirm"];
$idfuncionario  = $_POST["idfuncionario"];
$observacao     = $_POST["observacao"];

$validacao = true;
$msgValidacao = "";
$tmp_value = "";

$fields = null;

$mysql = new modulos_global_mysql();

if (!isset ($id)) {
    $id = 0;
}

if (!isset ($login) or strlen($login) == 0) {
    $msgValidacao["msg"][] = "Campo 'Login' é de preenchimento obrigatório.";
    $validacao = false;
} else {
    $fields["login"] = "'". $login ."'";
}

if ((!isset ($nome) or strlen($nome) == 0) and
        (!isset ($idfuncionario) or strlen($idfuncionario) == 0)) {
    $msgValidacao["msg"][] = "Digite um nome, ou escolha um funcionário.";
    $validacao = false;
} else if ((strlen($nome) > 0 and strlen($idfuncionario) > 0)) {
    $msgValidacao["msg"][] = "Só é possível uma das três opções: Escolher um funcionário ou digitar um nome.";
    $validacao = false;
} else if (strlen($nome) > 0) {
    $fields["nome"] = "'". $nome ."'";
    $fields["idfuncionario"] = "null";
} else {
    $fields["idfuncionario"] = "'". $idfuncionario ."'";
    $fields["nome"] = "null";
}

if (isset ($observacao)) {
    $fields["observacao"] = "'". $observacao ."'";
}

if ($id == 0) {
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
} else {
    if (isset ($senha) and strlen($senha) > 0) {
        if ($senha != $senhaconfirm) {
            $msgValidacao["msg"][] = "A confirmação da senha está diferente da senha digitada.";
            $validacao = false;
        } else if (strlen($senha) < 4) {
            $msgValidacao["msg"][] = "A senha deve conter mais de 4 caracteres.";
            $validacao = false;
        } else {
            $fields["senha"] = "md5('". $senha ."')";
        }
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