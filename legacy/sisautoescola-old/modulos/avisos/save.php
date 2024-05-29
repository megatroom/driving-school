<?php
include_once("../../configuracao.php");

$id           = $_POST["id"];
$fieldsPost[] = getPost("data");
$fieldsPost[] = getPost("status");
$fieldsPost[] = getPost("prioridade");
$fieldsPost[] = getPost("iddestinatario");
$fieldsPost[] = getPost("idremetente");
$fieldsPost[] = getPost("mensagem");

$validacao = true;
$msgValidacao = "";
$tmp_value = "";

$fields = null;

$mysql = new modulos_global_mysql();

foreach ($fieldsPost as $field) {
    foreach($field as $key => $value) {
        $tmp_value = $value;
        if ($key == "mensagem" and (!isset ($value) or strlen($value) == 0)) {
            $msgValidacao["msg"][] = "Campo 'Mensagem' é de preenchimento obrigatório.";
            $validacao = false;
        }
        if ($key == "iddestinatario" and (!isset ($value) or strlen($value) == 0)) {
            $msgValidacao["msg"][] = "Campo 'Destinatário' é de preenchimento obrigatório.";
            $validacao = false;
        }
        if ($key == "idremetente" and (!isset ($value) or strlen($value) == 0)) {
            $msgValidacao["msg"][] = "Campo 'Remetente' é de preenchimento obrigatório.";
            $validacao = false;
        }
        if ($key == "data") {
            if (isset ($value) and strlen($value) > 0) {
                if (is_valid_date($value)) {
                    $tmp_value = date_to_db($value);
                } else {
                    $msgValidacao["msg"][] = "A Data informada é inválida.";
                    $validacao = false;
                }
            } else {
                $msgValidacao["msg"][] = "Campo 'Data' é de preenchimento obrigatório.";
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

if ($validacao) {
    $id = $mysql->save($id, 'avisos', $fields, "id = '".$id."'");
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