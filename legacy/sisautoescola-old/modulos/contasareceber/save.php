<?php
ob_start(); session_start(); ob_end_clean();
include_once("../../configuracao.php");

$idusuario = $_SESSION["IDUSUARIO"];

$idalunoservico = $_POST["idalunoservico"];
$valorpago      = $_POST["valorpago"];

$mysql = new modulos_global_mysql();

$valorpago = float_to_db($valorpago);

$validacao = true;
$msgValidacao = "";
$tmp_value = "";

$fields = null;

if (!isset ($valorpago)) {
    $msgValidacao["msg"][] = "O campo 'Valor' é de preenchimento obrigatório.";
    $validacao = false;
} else {
    if ($valorpago <= 0) {
        $msgValidacao["msg"][] = "O valor deve ser maior que zero.";
        $validacao = false;
    }
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
    $valorjapago = $mysql->getValue(
                            'sum(valor) as total',
                            'total',
                            'contasareceber',
                            "idalunoservico = '".$idalunoservico."'");
    if (($valordaconta - $valorjapago - $desconto) < $valorpago) {
        $msgValidacao["msg"][] = "O valor do pagamento é maior do que o valor da conta.";
        $validacao = false;
    }
}

if ($validacao) {
    $total = $mysql->getValue(
            'count(c.id) as total',
            "total",
            "caixa c",
            "curdate() = c.data and c.idusuario = '".$_SESSION["IDUSUARIO"]."'");
    if ($total > 0) {
        $msgValidacao["msg"][] = "O seu caixa já foi fechado hoje.";
        $validacao = false;
    }
}

if ($validacao) {
    $fields['valor'] = "'".$valorpago."'";
    $fields['idalunoservico'] = "'".$idalunoservico."'";
    $fields['data'] = "curdate()";
    $fields["idusuario"] = $idusuario;

    $id = $mysql->save(0, 'contasareceber', $fields);
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