<?php
include_once("../../configuracao.php");

$pId = '';
if (isset($_GET["pId"])) {
    $pId = $_GET["pId"];
}

$idpessoa           = null;
$matricula          = null;
$matriculacfc       = null;
$idorigem           = null;
$nome               = null;
$dtnascimento       = null;
$sexo               = null;
$cpf                = null;
$rg                 = null;
$orgaoemissor       = null;
$rgdataemissao      = null;
$carteiradetrabalho = null;
$pai                = null;
$mae                = null;
$renach             = null;
$regcnh             = null;
$categoriaatual     = null;
$validadeprocesso   = null;
$duda               = null;
$endereco           = null;
$cep                = null;
$bairro             = null;
$cidade             = null;
$estado             = null;
$telefone           = null;
$telefone2          = null;
$telcontato         = null;
$tel2contato        = null;
$celular            = null;
$celular2           = null;
$celular3           = null;
$email              = null;
$observacoes        = null;

$anoAtual = date("Y");

$mysql = new modulos_global_mysql();

if (isset ($pId)) {
    $fieldList = $mysql->select(
            'a.idpessoa, a.matricula, a.matriculacfc, a.observacoes, '.
            'b.orgaoemissor, b.rgdataemissao, a.renach, a.noemail, '.
	    'a.regcnh, a.categoriaatual, a.validadeprocesso, a.idorigem, '.
            'b.nome,b.dtnascimento, b.rg, b.cpf, b.carteiradetrabalho, '.
            'b.endereco, b.bairro, b.cep, b.sexo, '.
            'b.cidade, b.estado as estado, '.
            'b.telefone, b.celular, b.telefone2, b.telcontato, b.tel2contato, '.
            'b.celular2, b.celular3, b.email, b.pai, b.mae ',
            'alunos a '.
            'left join pessoas b on a.idpessoa = b.id '.
            'left join origens c on a.idorigem = b.id ',
            "a.id = '".$pId."'");
   
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }
} else {
    $pId = 0;
}

$lstOrigem = null;
$rows = $mysql->select('a.id, a.descricao', 'origens a', NULL, null, 'a.descricao');
if (is_array($rows)) {
    foreach ($rows as $row) {
        $lstOrigem[$row["id"]] = $row["descricao"];
    }
}

if (isset ($dtnascimento)) {
    $dtnascimento = date('d/m/Y', strtotime($dtnascimento));
}
if (isset ($rgdataemissao)) {
    $rgdataemissao = date('d/m/Y', strtotime($rgdataemissao));
}
if (isset ($validadeprocesso)) {
    $validadeprocesso = date('d/m/Y', strtotime($validadeprocesso));
}

$lstSexo = null;
$lstSexo["M"] = 'Masculino';
$lstSexo["F"] = 'Feminino';

$acesso = new modulos_usuarios_funcionalidades(0);
$permissaoServico = $acesso->getPermissaoTela(1);

$form = new modulos_global_form('CadClientes');

$form->buttonSave("saveAluno", "Salvar");
$form->buttonCancel("closeAluno",null,"modulos/alunos/index.php");
if ($pId > 0 and $permissaoServico) {
    $form->buttonCustom('btnGoToService', 'Ir para Serviços (Sem Salvar)', 'ui-icon-pencil');
}

$form->divClear(1);

$form->divAlert();

$form->startFieldSet('fdAlunoCad');
$form->inputHidden('idpessoa', $idpessoa);
$form->inputText('fMatricula', 'Matrícula', $matricula, null, true, null, null, 'disabled="disabled"');
$form->inputText('fMatriculaCFC', 'Matrícula CFC', $matriculacfc, "20", true);
$form->selectFixed('fOrigem', 'Origem', false, $lstOrigem, $idorigem);
$form->divClear();
$form->inputText('fNome', 'Nome', $nome, "60", false, "455px");
$form->inputDate('fDtNascimento', 'Data de Nascimento', $dtnascimento, true, null, null, null, '1950:'.$anoAtual);
$form->selectFixed('fSexo', 'Sexo', true, $lstSexo, $sexo, '145px', null);
$form->inputCPF('fCpf', 'CPF', $cpf, null, false);
$form->inputText('fRg', 'RG', $rg, null, true);
$form->inputText('fOrgaoEmissor', 'Orgão Emissor', $orgaoemissor, 30, TRUE);
$form->inputDate('fRgDataEmissao', 'Data Emissão', $rgdataemissao, false, null, NULL, null, '1950:'.$anoAtual);
$form->inputText('fCarteiraDeTrabalho', 'Carteira de Trabalho', $carteiradetrabalho, "50", false);
$form->inputText('fPai', 'Nome do Pai', $pai, "100", false, "455px");
$form->inputText('fMae', 'Nome da Mãe', $mae, "100", false, "455px");
$form->endFieldSet();

$form->startFieldSet('fdAlunoInfo');
$form->inputRenach('fRenach', 'Renach', $renach, true);
$form->inputText('fRegCNH', 'Nº Registro CNH', $regcnh, "30", false);
$form->inputText('fCatAtual', 'Categoria Atual', $categoriaatual, "30", true);
$form->inputDate('fValidadeProcesso', 'Validade do Processo', $validadeprocesso, true);
$form->endFieldSet();

$form->startFieldSet('fdAlunoDuda');
$form->null('<table><tr><td>');
$form->inputText('fDuda', 'Duda', $duda, "20", true);
$form->null('</td><td valign="bottom">');
$form->buttonAdicionar('btnAddDuda');
$form->null('</td></tr></table>');
$form->null('<div id="dDuda"></div>');
$form->endFieldSet();

$form->startFieldSet('aluno_adress','Endereço');
$form->inputText('fEndereco', 'Endereço', $endereco, "60", true, "300px");
$form->inputText('fCEP', 'CEP', $cep, "8");
$form->inputText('fBairro', 'Bairro', $bairro, "45", true);
$form->inputText('fCidade', 'Cidade', $cidade, "45", true);
$form->inputText('fEstado', 'Estado', $estado, "2", true);
$form->endFieldSet();

$form->startFieldSet('aluno_contact','Contatos', true);
$form->inputPhone('fTelefone1', 'Telefone Principal', $telefone, true);
$form->inputText('fTel1Contato', 'Contato', $telcontato, 100, false, "300px");
$form->inputPhone('fTelefone2', 'Telefone Secundário', $telefone2, true);
$form->inputText('fTel2Contato', 'Contato', $tel2contato, 100, false, "300px");
$form->inputCelPhone('fCelular', 'Celular', $celular, true);
$form->inputText('fEmail', 'E-mail', $email, 100, false, "300px");
$form->checkbox('fNoEmail', 'Não possui e-mail', null, ($noemail == 'S'));
$form->inputCelPhone('fCelular2', 'Celular 2', $celular2, true);
$form->inputCelPhone('fCelular3', 'Celular 3', $celular3, false);
$form->endFieldSet();

$form->startFieldSet('aluno_obs','', true);
$form->textArea('fobservacoes', 'Observações', $observacoes, null, false, "420px");
$form->endFieldSet();

//$form->null('</td></tr>');
//$form->null('<tr><td>');
//
//// colocar algo aki, embaixo da foto
//
//$form->null('</td></tr>');
//$form->null('</table>');

$form->startFieldSet('fdServicosCadAluno');
$form->null('<div id="tblServicos"></div>');
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
function validarCPF() {
    $.post('modulos/alunos/cpfduplicado.php', {
        cpf: $("#fCpf").val(),
        idaluno: "<?php echo $pId; ?>"
    }, function(data){
        if (data.status == "no") {
            if (confirm("O CPF "+$("#fCpf").val()+" já pertence a outro aluno.\nDeseja recarregar o usuário com o CPF cadastrado?")) {
                novaAbaMenuPrincipal(0, 'modulos/alunos/form.php?pId='+data.idusercpf, 'Aluno');
            }
        }
    }, "json");
}
function carregarDudas() {
    $.post(
        'modulos/alunos/dudas.php',
        {
            idaluno : '<?php echo $pId; ?>'
        },
        function(data){
            $("#dDuda").html(data);
        });
}
$(document).ready(function(){
    <?php if ($pId > 0) { ?>
    $.post('modulos/alunos/tblservicos.php',
    {
        idaluno : '<?php echo $pId; ?>'
    }, function(data){
        $("#tblServicos").html(data);
    });
    <?php } ?>
    <?php if ($permissaoServico) { ?>
    $("#btnGoToService").click(function(event){
        openAjax("modulos/servicos/form.php?pId=<?php echo $pId; ?>");
        event.preventDefault();
    });
    <?php } ?>
    $("#fCpf").blur(function(){
        validarCPF();
    });
    $("#saveAluno").click(function(event){
        var noEmail = 'N';
        if ($("#fNoEmail").is(':checked')) {
            noEmail = 'S';
        }
	$.post(
            "modulos/alunos/save.php",
            { id : "<?php echo $pId; ?>",
                idpessoa : $("#idpessoa").val(),
                idempresa : $("#idempresa").val(),
                nome : $("#fNome").val(),
                dtnascimento : $("#fDtNascimento").val(),
                sexo : $("#fSexo").val(),
                rg : $("#fRg").val(),
                orgaoemissor : $("#fOrgaoEmissor").val(),
                rgdataemissao : $("#fRgDataEmissao").val(),
                cpf : $("#fCpf").val(),
                endereco : $("#fEndereco").val(),
                cep : $("#fCEP").val(),
                bairro : $("#fBairro").val(),
                cidade : $("#fCidade").val(),
                estado : $("#fEstado").val(),
                telefone : $("#fTelefone1").val(),
                telefone2 : $("#fTelefone2").val(),
                telcontato : $("#fTel1Contato").val(),
                tel2contato : $("#fTel2Contato").val(),
                celular : $("#fCelular").val(),
                celular2 : $("#fCelular2").val(),
                celular3 : $("#fCelular3").val(),
                email : $("#fEmail").val(),
                carteiradetrabalho : $("#fCarteiraDeTrabalho").val(),
                pai : $("#fPai").val(),
                mae : $("#fMae").val(),
                matricula : $("#fMatricula").val(),
                matriculacfc : $("#fMatriculaCFC").val(),
                renach : $("#fRenach").val(),
                observacoes : $("#fobservacoes").val(),
                regcnh : $("#fRegCNH").val(),
                categoriaatual : $("#fCatAtual").val(),
                idorigem : $("#fOrigem").val(),
                validadeprocesso : $("#fValidadeProcesso").val(),
		noemail: noEmail
            },
            function(data){
                if (data.retornoStatus == "save") {
                    openAjax("modulos/servicos/form.php?pId="+data.idaluno);
                } else {
                    postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/alunos/index.php','<?php echo $form->getdivAlertName(); ?>');
                }
            }, "json");
        event.preventDefault();
    });
    $("#btnAddDuda").click(function(event){
        $.post(
            "modulos/alunos/saveduda.php",
            {
                idaluno : '<?php echo $pId; ?>',
                duda : $("#fDuda").val()
            },
            function(data){
                if (data.retornoStatus == "save") {
                    carregarDudas();
                    $("#fDuda").val("");
                } else {
                    alert(data.msg);
                }
                $("#fDuda").focus();
            },
            "json");
        event.preventDefault();
    });
    carregarDudas();
});
</script>
