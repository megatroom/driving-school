<?php

include_once("../../configuracao.php");

$id             = $_POST["id"];
$idfuncao       = $_POST["idfuncao"];
$status         = $_POST["status"];

$fieldsPes[] = getPost("nome");
$fieldsPes[] = getPost("dtnascimento");
$fieldsPes[] = getPost("rg");
$fieldsPes[] = getPost("cpf");
$fieldsPes[] = getPost("endereco");
$fieldsPes[] = getPost("cep");
$fieldsPes[] = getPost("bairro");
$fieldsPes[] = getPost("cidade");
$fieldsPes[] = getPost("estado");
$fieldsPes[] = getPost("telefone");
$fieldsPes[] = getPost("celular");
$fieldsPes[] = getPost("email");

$cpf = $_POST["cpf"];

$idpessoa = $_POST["idpessoa"];
$matricula = 1;

$validacao = true;
$msgValidacao = "";
$tmp_value = "";

$fields = null;

$mysql = new modulos_global_mysql();

if (!isset ($id) or $id == 0) {
    $totFun = (int) $mysql->getValue('max(matricula) as total', 'total', 'funcionarios');
    if (isset ($totFun) and $totFun > 0) {
        $matricula = $totFun + 1;
    }
}

if (!isset ($idfuncao) or !is_numeric($idfuncao) or $idfuncao < 1) {
    $msgValidacao["msg"][] = "Campo 'Função' é de preenchimento obrigatório.";
    $validacao = false;
}

foreach ($fieldsPes as $field) {
    foreach($field as $key => $value) {
        $tmp_value = $value;
        if ($key == "nome" and (!isset ($value) or strlen($value) == 0)) {
            $msgValidacao["msg"][] = "Campo 'Nome' é de preenchimento obrigatório.";
            $validacao = false;
        }
        if ($key == "dtnascimento") {
            if (isset ($value) and strlen($value) > 0) {
                if (is_valid_date($value)) {
                    $tmp_value = date_to_db($value);
                } else {
                    $msgValidacao["msg"][] = "A Data de Nascimento informada é inválida.";
                    $validacao = false;
                }
            } else {
                $msgValidacao["msg"][] = "Campo 'Data de Nascimento' é de preenchimento obrigatório.";
                $validacao = false;
            }
        }

        if (isset ($tmp_value) and strlen($tmp_value) > 0){
            $fields[$key] = "'". $tmp_value ."'";
        } else {
            $fields[$key] = "null";
        }
    }
}

$totFunc = $mysql->getValue(
        'count(id) as total',
        'total',
        'vfuncionarios',
        "cpf = '".$cpf."' and id != '".$id."'");

if ($totFunc > 0) {
    $msgValidacao["msg"][] = "O CPF já foi utilizado em outro funcionário.";
    $validacao = false;
}

if (!validaCPF($cpf)) {
    $msgValidacao["msg"][] = "O CPF informado é inválido.";
    $validacao = false;
}

if ($validacao) {
    $idpessoa = $mysql->save($idpessoa, 'pessoas', $fields, "id = '".$idpessoa."'");
    if ($idpessoa) {
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

$fields = null;

if (!isset ($id) or $id == 0) {
    $fields["matricula"] = "'". str_pad($matricula, 4, "0", STR_PAD_LEFT) ."'";
}
$fields["idpessoa"] = "'". $idpessoa ."'";
$fields["idfuncao"] = "'". $idfuncao ."'";
$fields["status"] = "'". $status ."'";

if ($validacao) {
    if (!$mysql->save($id, 'funcionarios', $fields,"id = '".$id."'")) {
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