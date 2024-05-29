<?php
include_once("../../configuracao.php");

$id           = $_POST["id"];
$fieldsPost[] = getPost("idtipocarro");
$fieldsPost[] = getPost("descricao");
$fieldsPost[] = getPost("placa");
$fieldsPost[] = getPost("ano");
$fieldsPost[] = getPost("anomodelo");
$fieldsPost[] = getPost("datacompra");
$fieldsPost[] = getPost("datavenda");
$fieldsPost[] = getPost("idfunfixo");

$descricao  = $_POST["descricao"];
$placa      = $_POST["placa"];

$validacao = true;
$msgValidacao = "";
$tmp_value = "";

$fields = null;

$mysql = new modulos_global_mysql();

$countexistente = $mysql->getValue(
                        'count(id) as total',
                        'total',
                        'carros',
                        "lower(descricao) = lower('".$descricao."') and id <> '".$id."'");
if ($countexistente > 0) {
    $msgValidacao["msg"][] = "Já existe um carro cadastrada com essa descrição";
    $validacao = false;
}
$countexistente = $mysql->getValue(
                        'count(id) as total',
                        'total',
                        'carros',
                        "placa = '".$placa."' and id <> '".$id."'");
if ($countexistente > 0) {
    $msgValidacao["msg"][] = "Já existe um carro cadastrada com essa placa";
    $validacao = false;
}

foreach ($fieldsPost as $field) {
    foreach($field as $key => $value) {
        $tmp_value = $value;
        if ($key == "descricao" and (!isset ($value) or strlen($value) == 0)) {
            $msgValidacao["msg"][] = "Campo 'Descrição' é de preenchimento obrigatório.";
            $validacao = false;
        }
        if ($key == "idtipocarro" and (!isset ($value) or !is_numeric($value) or $value < 1)) {
            $msgValidacao["msg"][] = "Campo 'Tipo' é de preenchimento obrigatório.";
            $validacao = false;
        }
        if ($key == "datacompra") {
            if (isset ($value) and strlen($value) > 0) {
                if (is_valid_date($value)) {
                    $tmp_value = date_to_db($value);
                } else {
                    $msgValidacao["msg"][] = "A Data de Compra informada é inválida.";
                    $validacao = false;
                }
            }
        }
        if ($key == "datavenda") {
            if (isset ($value) and strlen($value) > 0) {
                if (is_valid_date($value)) {
                    $tmp_value = date_to_db($value);
                } else {
                    $msgValidacao["msg"][] = "A Data de Venda informada é inválida.";
                    $validacao = false;
                }
            }
        }
        if ($key == "placa") {
            if (isset ($value) and strlen($value) > 0) {
                if (strlen($value) <> 7) {
                    $msgValidacao["msg"][] = "A Placa informada é inválida.";
                    $validacao = false;
                } else {
                    $tmp_value = strtoupper($value);
                }
            } else {
                $msgValidacao["msg"][] = "Campo 'Placa' é de preenchimento obrigatório.";
                $validacao = false;
            }
        }
        if ($key == "ano") {
            if (isset ($value) and strlen($value) > 0) {
                if ($value < 1000 or $value > 9999) {
                    $msgValidacao["msg"][] = "O ano de fabricação informado é inválido.";
                    $validacao = false;
                }
            }
        }
        if ($key == "anomodelo") {
            if (isset ($value) and strlen($value) > 0) {
                if ($value < 1000 or $value > 9999) {
                    $msgValidacao["msg"][] = "O ano do modelo informado é inválido.";
                    $validacao = false;
                }
            }
        }

        if (isset ($tmp_value) and strlen($tmp_value) > 0){
            $fields[$key] = "'". $tmp_value ."'";
        } else {
            $fields[$key] = "null";
        }
    }
}

if ($validacao) {
    $id = $mysql->save($id, 'carros', $fields, "id = '".$id."'");
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