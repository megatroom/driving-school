<?php

include_once("../../configuracao.php");

$id = $_POST["id"];
$data = $_POST["data"];
$valor = $_POST["valor"];
$idfuncionario = $_POST["idfuncionario"];
$motivo = $_POST["motivo"];

$validacao = true;
$msgValidacao = "";

$fields = null;
$where = null;

$mysql = new modulos_global_mysql();

if (isset ($motivo) and strlen($motivo) > 0) {
    $fields['motivo'] = "'". $motivo ."'";
} else {
    $msgValidacao["msg"][] = "Campo 'Motivo' é de preenchimento obrigatório.";
    $validacao = false;
}

if (isset ($data) and is_valid_date($data)) {
    $fields['data'] = "'". date_to_db($data) ."'";
} else {
    $msgValidacao["msg"][] = "Campo 'Data' é de preenchimento obrigatório.";
    $validacao = false;
}

if (isset ($idfuncionario) and $idfuncionario > 0) {
    $fields['idfuncionario'] = "'". $idfuncionario ."'";
} else {
    $msgValidacao["msg"][] = "Campo 'Funcionário' é de preenchimento obrigatório.";
    $validacao = false;
}

if (isset ($valor) and $valor > 0) {
    $fields['valor'] = "'". float_to_db($valor) ."'";
} else {
    $msgValidacao["msg"][] = "Campo 'Valor' é de preenchimento obrigatório e deve ser maior que 0 (zero).";
    $validacao = false;
}

if ($id > 0) {
    $where = "id = ".$id;
}

if ($validacao) {
    if ($mysql->save($id, 'vales', $fields, $where)) {
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