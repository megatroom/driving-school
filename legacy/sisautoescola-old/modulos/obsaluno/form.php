<?php
include_once("../../configuracao.php");

$pId = $_GET["pId"];

$anoAtual = date("Y");

$mysql = new modulos_global_mysql();

$fieldList = $mysql->select(
        'a.idpessoa, a.matricula, a.matriculacfc, a.observacoes, '.
        'b.orgaoemissor, b.rgdataemissao, a.renach, '.
        'a.regcnh, a.categoriaatual, a.validadeprocesso, a.idorigem, '.
        'b.nome,b.dtnascimento, b.rg, b.cpf, b.carteiradetrabalho, '.
        'b.endereco, b.bairro, b.cep, b.sexo, '.
        'b.cidade, b.estado as estado, '.
        'b.telefone, b.celular, b.telefone2, b.telcontato, b.tel2contato, '.
        'b.email, b.pai, b.mae ',
        'alunos a '.
        'left join pessoas b on a.idpessoa = b.id '.
        'left join origens c on a.idorigem = b.id ',
        "a.id = '".$pId."'");

if (is_array($fieldList)) {
    foreach ($fieldList as $fields) {
        extract($fields);
    }
}
    
$form = new modulos_global_form('CadClientes');

$form->buttonSave("saveAluno", "Salvar");
$form->buttonCancel("closeAluno",null,"modulos/obsaluno/index.php");

$form->divClear(1);

$form->divAlert();

$form->startFieldSet('fdAlunoCad');
$form->inputTextStatic('', 'Nome', $nome, FALSE, "420px");
$form->inputTextStatic('', 'Matrícula', $matricula, true, "200px");
$form->inputTextStatic('', 'Matrícula CFC', $matriculacfc, true, "200px");
$form->endFieldSet();

$form->startFieldSet('aluno_obs','', true);
$form->textArea('fObservacaoAtual', 'Observações', $observacoes, null, false, "420px", null, 'rows="10" disabled');
$form->textArea('fNovaObservacao', 'Nova Observação', '', null, false, "420px", null, 'rows="3"');
$form->endFieldSet();

$form->close();
    
?>
<script type="text/javascript">
$(document).ready(function(){
    $("#fNovaObservacao").focus();
    $("#saveAluno").click(function(event){
        $.post(
            "modulos/obsaluno/save.php",
            { id : "<?php echo $pId; ?>",
                observacoes : $("#fNovaObservacao").val()
            },
            function(data){
                if (data.retornoStatus == "save") {
                    openAjax("modulos/obsaluno/index.php");
                } else {
                    postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/alunos/index.php','<?php echo $form->getdivAlertName(); ?>');
                }
            }, "json");
        event.preventDefault();
    });
});
</script>
