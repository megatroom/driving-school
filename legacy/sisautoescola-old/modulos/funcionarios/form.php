<?php

include_once("../../configuracao.php");

$pId = null;
if (isset($_GET["pId"])) {
    $pId = $_GET["pId"];
}

$dtnascimento = null;
$matricula = null;
$nome = null;
$rg = null;
$cpf = null;
$idfuncao = null;
$status = null;
$endereco = null;
$cep = null;
$bairro = null;
$cidade = null;
$estado = null;
$telefone = null;
$celular = null;
$email = null;

if (isset ($pId)) {
    $mysql = new modulos_global_mysql();
    $fieldList = $mysql->select('*', 'funcionarios a, pessoas b', "a.idpessoa = b.id and a.id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }
} else {
    $pId = 0;
}

$commisaoList = null;
$commisaoList['per'] = '%';
$commisaoList['val'] = 'R$';

if (!isset ($idpessoa) or !is_numeric($idpessoa)) {
    $idpessoa = 0;
}

$mysql = new modulos_global_mysql();

$funcoes = $mysql->select('id, descricao', 'funcoes', null, null, 'descricao');
$funcaoList = null;
if (is_array($funcoes)) {
    foreach($funcoes as $funcao) {
        $funcaoList[$funcao["id"]] = $funcao["descricao"];
    }
}

$lstStatus = null;
$lstStatus["A"] = "Ativo";
$lstStatus["I"] = "Inativo";

if (isset ($comissao_val[0])) {
    $comissao_value = sprintf("%01.2f", $comissao_val);
    $comissao_key = "val";
} else {
    if(isset ($comissao_per[0])) {
        $comissao_value = sprintf("%01.2f", $comissao_per);
        $comissao_key = "per";
    } else {
        $comissao_value = "";
        $comissao_key = "per";
    }
}

if (isset ($dtnascimento)) {
    $dtnascimento = date('d/m/Y', strtotime($dtnascimento));
}

$form = new modulos_global_form('CadFuncionarios');

$form->buttonSave("saveFun");
$form->buttonCancel("closeFun",null,"modulos/funcionarios/index.php");
$form->divClear(1);

$form->divAlert();

$form->inputHidden('idpessoa', $idpessoa);

$form->startFieldSet('fun_basic');
$form->inputTextStatic('fMatricula', 'Matrícula', $matricula, true, null, "text-align:right;");
$form->inputText('fNome', 'Nome', $nome, "60", false, "295px");
$form->inputDate('fDtNascimento', 'Data de Nascimento', $dtnascimento, true, null, null, null, '1950:2010');
$form->inputText('fRg', 'RG', $rg, null, true);
$form->inputCPF('fCpf', 'CPF', $cpf, false);
$form->selectFixed('ffuncao', 'Função', true, $funcaoList, $idfuncao, '145px');
$form->selectFixed('fStatus', 'Status', false, $lstStatus, $status);
$form->endFieldSet();

$form->startFieldSet('fun_adress','Endereço');
$form->inputText('fEndereco', 'Endereço', $endereco, "60", true, "295px");
$form->inputText('fCEP', 'CEP', $cep, "8");
$form->inputText('fBairro', 'Bairro', $bairro, "45", true);
$form->inputText('fCidade', 'Cidade', $cidade, "45", true);
$form->inputText('fEstado', 'Estado', $estado, "2", true);
$form->endFieldSet();

$form->startFieldSet('fun_contact','Contatos', true);
$form->inputPhone('fTelefone', 'Telefone', $telefone, true);
$form->inputPhone('fCelular', 'Celular', $celular, true);
$form->inputText('fEmail', 'E-mail', $email, null, true);
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#saveFun").click(function(event){
        $.post(
            "modulos/funcionarios/save.php",
            { id : "<?php echo $pId ?>",
                idpessoa : $("#idpessoa").val(),
                nome : $("#fNome").val(),
                dtnascimento : $("#fDtNascimento").val(),
                rg : $("#fRg").val(),
                cpf : $("#fCpf").val(),
                endereco : $("#fEndereco").val(),
                cep : $("#fCEP").val(),
                bairro : $("#fBairro").val(),
                cidade : $("#fCidade").val(),
                estado : $("#fEstado").val(),
                telefone : $("#fTelefone").val(),
                celular : $("#fCelular").val(),
                email : $("#fEmail").val(),
                idfuncao : $("#ffuncao").val(),
                status : $("#fStatus").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/funcionarios/index.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
</script>