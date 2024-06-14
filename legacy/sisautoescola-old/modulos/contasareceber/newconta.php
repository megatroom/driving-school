<?php
include_once("../../configuracao.php");

$id           = 0;
$fieldsPost[] = getPost("idaluno");
$fieldsPost[] = getPost("idtiposervico");
$fieldsPost[] = getPost("desconto");
$fieldsPost[] = getPost("vencimento");
//$fieldsPost[] = getPost("observacao");

$idservico = $_POST["idtiposervico"];

$validacao = true;
$msgValidacao = "";
$tmp_value = "";

$fields = null;

$mysql = new modulos_global_mysql();

$valor = $mysql->getValue('valor', null, 'tiposervicos', "id = '".$idservico."'");
$qtaulaspraticas = $mysql->getValue('qtaulaspraticas', null, 'tiposervicos', "id = '".$idservico."'");
$qtaulasteoricas = $mysql->getValue('qtaulasteoricas', null, 'tiposervicos', "id = '".$idservico."'");

foreach ($fieldsPost as $field) {
    foreach($field as $key => $value) {
        $tmp_value = $value;

        if ($key == "idaluno" and (!isset ($value) or !is_numeric($value) or $value < 1)) {
            $msgValidacao["msg"][] = "Campo 'Aluno' é de preenchimento obrigatório.";
            $validacao = false;
        }

        if ($key == "idservico" and (!isset ($value) or !is_numeric($value) or $value < 1)) {
            $msgValidacao["msg"][] = "Campo 'Serviço' é de preenchimento obrigatório.";
            $validacao = false;
        }

        if ($key == "desconto") {
            $tmp_value = float_to_db($value);
            if (!isset ($tmp_value) or !is_numeric($tmp_value) or $tmp_value < 0) {
                $msgValidacao["msg"][] = "Campo 'Desconto' é de preenchimento obrigatório (pode ser zero).";
                $validacao = false;
            }
        }
        if ($key == "vencimento") {
            $tmp_value = date_to_db($value);
            if (!isset ($tmp_value) or $value == "" or !is_valid_date($value)) {
                $msgValidacao["msg"][] = "Campo 'Vencimento' é de preenchimento obrigatório.";
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

$fields["valor"] = $valor;
$fields["qtaulaspraticas"] = $qtaulaspraticas;
$fields["qtaulasteoricas"] = $qtaulasteoricas;
$fields["data"] = 'CURDATE()';

if ($validacao) {
    $id = $mysql->save($id, 'alunoservico', $fields, "id = '".$id."'");
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