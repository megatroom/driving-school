<?php
include_once("../../configuracao.php");

$pIdAluno = $_GET["pId"];

if (!isset ($pIdAluno)) {
    exit;
}

$acesso = new modulos_usuarios_funcionalidades(0);

$mysql = new modulos_global_mysql();

$lstTipoServicos = null;
$lstTipoServicos[] = '';
$rows = $mysql->select('id, descricao', 'tiposervicos', "status = 'A'", null, 'descricao');
if (is_array($rows)) {
    foreach ($rows as $row) {
        $lstTipoServicos[$row["id"]] = $row["descricao"];
    }
}

$rows = $mysql->select(
            'a.matricula, a.matriculacfc, a.observacoes, b.nome, b.cpf',
            'alunos a, pessoas b',
            "a.idpessoa = b.id and a.id = '".$pIdAluno."'");
if (is_array($rows)) {
    foreach ($rows as $row) {
        $alunoMatricula = $row["matricula"];
        $alunoMatriculaCFC = $row["matriculacfc"];
        $alunoNome = strtoupper($row["nome"]);
        $alunoCPF = $row["cpf"];
        $observacoes = $row["observacoes"];
    }
}

$pColNames = array('Código', 'Data', 'Descrição', 'Aulas Prat.', 'Aulas Teo', 'Valor', 'Desconto');
$pColModel = array( "{name:'id',index:'id', hidden:true}",
                    "{name:'data',index:'data', width:100}",
                    "{name:'descricao',index:'descricao', width:200}",
                    "{name:'qtaulaspraticas',index:'matriculacfc', width:100}",
                    "{name:'qtaulasteoricas',index:'nome', width:100}",
                    "{name:'valor',index:'valor', width:100, align:'right'}",
                    "{name:'desconto',index:'desconto', width:100, align:'right'}");
$pSortName = 'data';
$grdAlunoServicos = new modulos_global_grid('mainGrdAluno', 'Serviços', 'modulos/servicos/servicos.xml.php?pIdAluno='.$pIdAluno, $pColNames, $pColModel, $pSortName, false);

$disable = "disabled";

$form = new modulos_global_form('frmServicos');

$form->buttonCancel('bCancServicos', 'Voltar', 'modulos/servicos/index.php');
$form->buttonCustom('bCadAlun', 'Cadastro de Aluno', 'ui-icon-contact');
$form->divClear(1);

$form->divAlert();

$form->startFieldSet('fdServAluno');
$form->null('<div style="font-size:16pt;">');
$form->null($alunoNome);
$form->null('</div><br />');
$form->inputTextStatic('fAlunoMatricula', 'Matrícula', $alunoMatricula, true);
$form->inputTextStatic('fAlunoMatriculaCFC', 'Matrícula CFC', $alunoMatriculaCFC, true);
$form->inputTextStatic('fCPF', 'CPF', $alunoCPF, false);
$form->textArea('fObsAluno', 'Observação', $observacoes, null, false, "450px", "color: red;", $disable);
$form->endFieldSet();

$form->startFieldSet('fdServAgendamentos');
$acesso->setTela(7);
if ($acesso->getFuncionalidade(1)) {
    $form->buttonAdicionar('btnAddAgandamento', 'Adicionar Novo Agendamento');
}
$form->null('<br /><br /><br />');
$form->null('<div id="tblServAgendamentos" ></div>');
$form->endFieldSet();

$form->startFieldSet('fdServTipos', 'Serviços');
$form->selectFixed('fTipoServicos', 'Tipos de Serviços', FALSE, $lstTipoServicos, null, "450px");
$form->inputTextStatic('fQtAulasPraticas', 'Qtd. Aulas Práticas', null, true);
$form->inputTextStatic('fQtAulasTeoricas', 'Qtd. Aulas Teóricas', null, true);
$form->inputTextStatic('fvalor', 'Valor (R$)', '0,00', true);

$acesso->setTela(1);
if ($acesso->getFuncionalidade(3)) {
    $form->inputDecimal('fdesconto', 'Desconto (R$)', null, false);
} else {
    $form->inputTextStatic('fdesconto', 'Desconto (R$)', null, false);
}

$form->null('<br />');
$acesso->setTela(19);
if ($acesso->getFuncionalidade(1)) {
    $form->buttonAdicionar('fAddTipoServ');
}
if ($acesso->getFuncionalidade(2)) {
    $form->buttonExc('fExcTipoServ', null, $grdAlunoServicos->getGridName(), 'id', 'modulos/servicos/form.php?pId='.$pIdAluno, 'modulos/servicos/excservicos.php');
}
$form->null('<br /><br /><br />');
$form->nullArray($grdAlunoServicos->resultGrid());
$form->null('<br />');
$form->endFieldSet();

$form->startFieldSet('fdContasAReceber');
$form->null('<div id="tblContasAReceber" ></div>');
$form->endFieldSet();

$form->startFieldSet('fdServAulas', 'Aulas');
$form->buttonAdicionar('fAddAulasPraticas', 'Adicionar Aulas Práticas');
$form->buttonCustom('fExcAulaPratica', 'Excluir Aula(s) Prática(s)', "ui-icon-trash");
$form->null('<br /><br /><br />');
$form->null('<table><tr><td valign="top">');
$form->null('<div id="dTblServAulasPraticas"></div>');
$form->null('</td><td valign="top">');
$form->null('<div id="dTblServAulasTeoricas"></div>');
$form->null('</td></tr></table>');
$form->endFieldSet();

$form->startFieldSet('fdExamePratico');
$form->null('<div id="dTblServExamePratico"></div>');
$form->endFieldSet();

$form->close();

?>
<br /><br /><br />
<script type="text/javascript">
    $(document).ready(function(){
        carregarContasAReceber();
        carregarAulasPraticas();
        $.post(
            'modulos/servicos/tblaulasteoricas.php',
            { idaluno : '<?php echo $pIdAluno; ?>' },
            function (data) {
                $("#dTblServAulasTeoricas").html(data);
            });
        $.post(
            'modulos/servicos/tblexamepratico.php',
            { idaluno : '<?php echo $pIdAluno; ?>' },
            function (data) {
                $("#dTblServExamePratico").html(data);
            });
        $.post(
            'modulos/servicos/tblagendamentos.php',
            { idaluno : '<?php echo $pIdAluno; ?>' },
            function (data) {
                $("#tblServAgendamentos").html(data);
            });
        $("#fTipoServicos").change(function() {
            $.post('modulos/servicos/servicos.php', { id: $("#fTipoServicos").val() }, function(data) {
                $("#fQtAulasPraticas").val(data.qtaulaspraticas);
                $("#fQtAulasTeoricas").val(data.qtaulasteoricas);
                $("#fvalor").val(data.valor);
                $("#fdesconto").val('0,00');
            }, "json");
        });
        $("#fTipoServicos").trigger('change');
        $("#fAddTipoServ").click(function (event) {
            if ($("#fTipoServicos").val() == 0 || $("#fTipoServicos").val() == "") {
                divAlertCustomBasic('<?php echo $form->getdivAlertName(); ?>', 'Selecione um tipo de serviço');
                exit;
            }
            $.post('modulos/servicos/addservicos.php',
                {
                    idaluno: '<?php echo $pIdAluno; ?>',
                    idtiposervico: $("#fTipoServicos").val(),
                    desconto: $("#fdesconto").val()
                }, function(data) {
                    $("#fTipoServicos").val(0);
                    $("#fQtAulasPraticas").val("");
                    $("#fQtAulasTeoricas").val("");
                    $("#fvalor").val("");
                    $("#fdesconto").val("0,00");
                    var vUrl = 'modulos/servicos/servicos.xml.php?pIdAluno=<?php echo $pIdAluno; ?>';
                    $("#<?php echo $grdAlunoServicos->getGridName(); ?>").jqGrid('setGridParam',{url:vUrl}).trigger("reloadGrid");
                    carregarContasAReceber();
                }
            );
            event.preventDefault();
        });
        $("#btnAddAgandamento").click(function(event){
            novaAbaMenuPrincipalComParametro(
                'modulos/agendamentos/form.php',
                { pIdAluno: '<?php echo $pIdAluno; ?>', pReturn: 'modulos/servicos/form.php?pId=<?php echo $pIdAluno; ?>' },
                'Serviços');
            event.preventDefault();
        });
        $("#fAddAulasPraticas").click(function(event){
            novaAbaMenuPrincipalComParametro(
                'modulos/aulaspraticas/index.php',
                { pIdAluno: '<?php echo $pIdAluno; ?>', pReturn: 'modulos/servicos/form.php?pId=<?php echo $pIdAluno; ?>' },
                'Serviços');
            event.preventDefault();
        });
        $("#fExcAulaPratica").click(function(event){
            var vIdsExcAP = "";
            $("#chckExcAP:checked").each(function(){
                if (vIdsExcAP == "") {
                    vIdsExcAP = $(this).val();
                } else {
                    vIdsExcAP = vIdsExcAP + "," + $(this).val();
                }
            });
            $.post('modulos/servicos/excaulapratica.php', {
                idaulapratica : vIdsExcAP
            }, function(data){
                carregarAulasPraticas();
            }, "json");
            event.preventDefault();
        });
		$("#bCadAlun").click(function(event){
			openAjax('modulos/alunos/form.php?pId=<?php echo $pIdAluno; ?>');
			event.preventDefault();
		});
    });
    function carregarContasAReceber() {
        $.post(
            'modulos/servicos/contasareceber.php',
            { idaluno : '<?php echo $pIdAluno; ?>' },
            function (data) {
                $("#tblContasAReceber").html(data);
            });
    }
    function carregarAulasPraticas() {
        $.post(
            'modulos/servicos/tblaulaspraticas.php',
            { idaluno : '<?php echo $pIdAluno; ?>' },
            function (data) {
                $("#dTblServAulasPraticas").html(data);
            });
    }
</script>