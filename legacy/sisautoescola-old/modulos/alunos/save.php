<?php
if (session_id() === "") {
    session_start();
}
include_once("../../configuracao.php");

$id = $_POST["id"];
$observacoes = $_POST["observacoes"];
$idpessoa = $_POST["idpessoa"];
$matricula = $_POST["matricula"];
$matriculacfc = $_POST["matriculacfc"];
$renach = $_POST["renach"];
$regcnh = $_POST["regcnh"];
$categoriaatual = $_POST["categoriaatual"];
$idorigem = $_POST["idorigem"];
$validadeprocesso = $_POST["validadeprocesso"];
$noemail = $_POST['noemail'];

$cpf = $_POST["cpf"];

$validacao = true;
$msgValidacao = "";
$tmp_value = "";

$mysql = new modulos_global_mysql();

$totAlunos = $mysql->getValue(
        'count(id) as total',
        'total',
        'valunos',
        "cpf = '".$cpf."' and id != '".$id."'");

if ($totAlunos > 0) {
    $msgValidacao["msg"][] = "O CPF já foi utilizado em outro aluno.";
    $validacao = false;
}

if (!isset ($id) or $id == 0) {
    $totAluno = (int) $mysql->getValue('max(matricula) as total', 'total', 'alunos');
    if (isset ($totAluno) and $totAluno > 0) {
        $matricula = $totAluno + 1;
    } else {
        $matricula = 1;
    }
} else {
    if (!is_numeric($idpessoa)) {
        $msgValidacao["msg"][] = "IdPessoa não informado.";
        $validacao = false;
    }
}

$fieldsPes[] = getPost("nome");
$fieldsPes[] = getPost("dtnascimento");
$fieldsPes[] = getPost("sexo");
$fieldsPes[] = getPost("rg");
$fieldsPes[] = getPost("orgaoemissor");
$fieldsPes[] = getPost("rgdataemissao");
$fieldsPes[] = getPost("cpf");
$fieldsPes[] = getPost("endereco");
$fieldsPes[] = getPost("cep");
$fieldsPes[] = getPost("bairro");
$fieldsPes[] = getPost("cidade");
$fieldsPes[] = getPost("estado");
$fieldsPes[] = getPost("telefone");
$fieldsPes[] = getPost("telefone2");
$fieldsPes[] = getPost("telcontato");
$fieldsPes[] = getPost("tel2contato");
$fieldsPes[] = getPost("celular");
$fieldsPes[] = getPost("celular2");
$fieldsPes[] = getPost("celular3");
$fieldsPes[] = getPost("email");
$fieldsPes[] = getPost("carteiradetrabalho");
$fieldsPes[] = getPost("pai");
$fieldsPes[] = getPost("mae");

$fields = null;

$mysql = new modulos_global_mysql();

foreach ($fieldsPes as $field) {
    foreach($field as $key => $value) {
        //echo "chave: ".$key." valor: ".$value." | ";
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
            }
        }
        if ($key == "rgdataemissao") {
            if (isset ($value) and strlen($value) > 0) {
                if (is_valid_date($value)) {
                    $tmp_value = date_to_db($value);
                } else {
                    $msgValidacao["msg"][] = "A Data de Emissão do RG informada é inválida.";
                    $validacao = false;
                }
            }
        }
        if ($key == "cpf") {
            if (isset ($value) and strlen($value) > 0) {
                if (!validaCPF($value)) {
                    $msgValidacao["msg"][] = "O CPF informado é inválido.";
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
    $idpessoa = $mysql->save($idpessoa, 'pessoas', $fields, "id = '".$idpessoa."'");
    if (!$idpessoa) {
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
$fields["idpessoa"] = "'". $idpessoa ."'";
$fields["observacoes"] = "'". $observacoes ."'";
$fields["matricula"] = "'". $matricula ."'";
$fields["matriculacfc"] = "'". $matriculacfc ."'";
$fields["renach"] = "'". $renach ."'";
$fields["regcnh"] = "'". $regcnh ."'";
$fields["categoriaatual"] = "'". $categoriaatual ."'";
$fields["idorigem"] = "'". $idorigem ."'";
$fields["noemail"] = "'". $noemail ."'";
if (isset ($validadeprocesso) && $validadeprocesso != "") {
    $fields["validadeprocesso"] = "'". date_to_db($validadeprocesso) ."'";
}

if ($validacao) {
    $idAlunoSaved = $mysql->save($id, 'alunos', $fields,"id = '".$id."'");
    if (!$idAlunoSaved) {
        $msgValidacao["retornoStatus"][] = "erro";
        $msgValidacao["titulo"][] = "Ocorreu um erro no banco de dados.";
        $msgValidacao["msg"][] = "Mensagem técnica: ". $mysql->getMsgErro();
    } else {
        $msgValidacao["retornoStatus"][] = "save";
        $msgValidacao["titulo"][] = "Aluno salvo com sucesso!";
        $msgValidacao["idaluno"][] = $idAlunoSaved;
    }
} else {
    $msgValidacao["retornoStatus"][] = "validacao";
    $msgValidacao["titulo"][] = "Aviso.";
}

echo json_encode($msgValidacao);

?>
