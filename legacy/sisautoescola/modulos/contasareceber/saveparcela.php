<?php
include_once("../../configuracao.php");

$idalunoservico = $_POST["idalunoservico"];
$data           = $_POST["data"];
$valor          = $_POST["valor"];

$mysql = new modulos_global_mysql();

$valorpago = float_to_db($valorpago);

$validacao = true;
$msgValidacao = "";
$tmp_value = "";

$fields = null;

if (!isset ($valor)) {
    $msgValidacao["msg"][] = "O campo 'Valor' é de preenchimento obrigatório.";
    $validacao = false;
} else if ($valor <= 0) {
    $msgValidacao["msg"][] = "O valor deve ser maior que zero.";
    $validacao = false;
}

if (!isset ($data) or $data == "") {
    $msgValidacao["msg"][] = "O campo 'Data' é de preenchimento obrigatório.";
    $validacao = false;
} else if (!is_valid_date($data)) {
    $msgValidacao["msg"][] = "O campo 'Data' é inválido.";
    $validacao = false;
}

if ($validacao) {
    $valordaconta = $mysql->getValue(
                            'valor',
                            null,
                            'alunoservico',
                            "id = '".$idalunoservico."'");
    $desconto = $mysql->getValue(
                            'coalesce(desconto, 0) as desconto',
                            'desconto',
                            'alunoservico',
                            "id = '".$idalunoservico."'");
    $valorjalancado = $mysql->getValue(
                            'sum(valor) as total',
                            'total',
                            'alunoservicoparcelas',
                            "idalunoservico = '".$idalunoservico."'");
    if (($valordaconta - $valorjalancado - $desconto) < $valor) {
        $msgValidacao["msg"][] = "O valor da parcela excede o valor da conta.";
        $validacao = false;
    }
}

if ($validacao) {
    $fields['valor'] = "'".$valor."'";
    $fields['idalunoservico'] = "'".$idalunoservico."'";
    $fields['data'] = "'".  date_to_db($data)."'";

    $id = $mysql->save(0, 'alunoservicoparcelas', $fields);
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