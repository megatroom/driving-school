<?php
ob_start(); session_start(); ob_end_clean();
include_once("../../configuracao.php");

unset ($_SESSION["LOGIN"]);

$login = $_POST["user"];
$senha = $_POST["pwd"];

$mysql = new modulos_global_mysql();

$id = $mysql->getValue('id', 'id', 'usuarios', "login = '$login' and senha = md5('$senha')");

$retorno = null;
if (isset ($id) and is_numeric($id) and $id > 0) {

    $_SESSION["IDUSUARIO"] = $id;
    $_SESSION["LOGIN"] = $login;
    $_SESSION["USUARIO_NOME"] = $mysql->getValue(
            'coalesce(f.nome, u.nome) as nome',
            'nome',
            'usuarios u left join vfuncionarios f on u.idfuncionario = f.id',
            "login = '$login' and senha = md5('$senha')");

    $retorno["retorno"] = 'ok';

} else {
    $id = $mysql->getValue('id', 'id', 'usuarios', "login = '$login'");

    if (isset ($id) and is_numeric($id) and $id > 0) {
        $retorno["retorno"] =  "Senha inválida!";
    } else {
        $retorno["retorno"] =  "Usuário não existe!";
    }
}

echo json_encode($retorno);

?>