<?php
include_once("../../configuracao.php");

$pIdCarro = $_POST["idcarro"];
$pIdFuncionario = $_POST["idfuncionario"];
$pData = $_POST["data"];
$pHora = $_POST["hora"];

$validacao = true;
$msgValidacao = "";

if (!isset($pIdCarro) or strlen($pIdCarro) == 0) {
    $msgValidacao["msg"][] = "Campo 'Carro' é de preenchimento obrigatório.";
    $validacao = false;
}
if (!isset($pIdFuncionario) or strlen($pIdFuncionario) == 0) {
    $msgValidacao["msg"][] = "Campo 'Funcionário' é de preenchimento obrigatório.";
    $validacao = false;
}
if (!isset($pData) or strlen($pData) == 0) {
    $msgValidacao["msg"][] = "Campo 'Data' é de preenchimento obrigatório.";
    $validacao = false;
}
if (!isset($pHora) or strlen($pHora) == 0) {
    $msgValidacao["msg"][] = "Campo 'Hora' é de preenchimento obrigatório.";
    $validacao = false;
}

$fields = null;
$fields["idcarro"] = $pIdCarro;
$fields["idfuncionario"] = $pIdFuncionario;
$fields["data"] = "'".date_to_db($pData)."'";
$fields["hora"] = "'".$pHora."'";

$mysql = new modulos_global_mysql();

/*
$countRetorno = $mysql->getValue('count(*) as total', 'total', 'carrofuncionario a',
        "a.idcarro = '".$pIdCarro."' and a.data >= '".date_to_db($pData)."'");
if ($countRetorno > 0) {
    $msgValidacao["msg"][] = "Não é possível adicionar um funcionário para esta data. A data deve ser maior que a última data lançada.";
    $validacao = false;
}
*/
$countRetorno = $mysql->getValue('count(*) as total', 'total', 'carrofuncionario a',
        "a.idcarro = '".$pIdCarro."' and a.idfuncionario ='".$pIdFuncionario."' and a.data = (select max(b.data) from carrofuncionario b where a.idcarro = b.idcarro)");
if ($countRetorno > 0) {
    $msgValidacao["msg"][] = "O último funcionário já é este que está adicionando.";
    $validacao = false;
}

if ($validacao) {
    $id = $mysql->save(0, 'carrofuncionario', $fields);
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