<?php
ob_start(); session_start(); ob_end_clean();
include_once("../../configuracao.php");

$pId = null;
if (isset($_GET["pId"])) {
    $pId = $_GET["pId"];
}

$pReturn = null;
if (isset($_GET["pReturn"])) {
    $pReturn = $_GET["pReturn"];
}

$urlRetorno = 'modulos/contasareceber/index.php';

$acesso = new modulos_usuarios_funcionalidades(19);

$mysql = new modulos_global_mysql();

$usuarioLogado = $_SESSION["USUARIO_NOME"];

$lstServicos = null;
$servico = null;
$deconto = null;
$vencimento = null;

if (isset ($pId) and is_numeric($pId) and $pId > 0) {
    $fieldList = $mysql->select('*', 'valunoservico', "id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }

    $valorjapago = $mysql->getValue(
                            'sum(valor) as total',
                            'total',
                            'contasareceber',
                            "idalunoservico = '".$pId."'");

    $valorparapagar = $valor - $valorjapago - $desconto;
} else {
    $valorparapagar = 0;

    $lstServicos[""] = "";
    $rows = $mysql->select('id, descricao', 'tiposervicos', "status = 'A'", null, 'descricao');
    if (is_array($rows)) {
        foreach ($rows as $row) {
            $lstServicos[$row["id"]] = $row["descricao"];
        }
    }   
}

if (isset ($pReturn) and strlen($pReturn) > 1) {
    if ($pReturn == "Servicos") {
        $urlRetorno = 'modulos/servicos/form.php?pId='.$idaluno;
    }
}

$form = new modulos_global_form('frmContasAReceber');

if (!(isset ($pId) and is_numeric($pId) and $pId > 0)) {
    $form->buttonSave('btnSaveContaReceber');
}
$form->buttonCancel('fContRecebCancelar', 'Voltar', $urlRetorno);
$form->divClear(1);

$form->divAlert();

$form->startFieldSet('fdContasAReceberBasic');
if (isset ($pId) and is_numeric($pId) and $pId > 0) {
    $form->inputTextStatic('fAluno', 'Aluno', $aluno, false, "450px");
    $form->inputTextStatic('fServico', 'Serviço', $tiposervico, false, "450px");
    $form->inputTextStatic('fValor', 'Valor', db_to_float($valor), true);
    $form->inputTextStatic('fDesconto', 'Desconto', db_to_float($desconto), true);
    $form->inputTextStatic('fVencimento', 'Vencimento', db_to_date($vencimento), false);
    /* $form->textArea('fObservacao', 'Observação', $observacao, 255, FALSE, '445px'); */
} else {
    $acessoDesconto = 'disabled';
    if ($acesso->getFuncionalidade(5)) {
        $acessoDesconto = '';
    }
    
    $form->inputTextStaticLookUp('alunos', 'fAluno', 'fIdAluno', 'Aluno', 'bAluno');
    $form->selectFixed('fServico', 'Serviço', false, $lstServicos, $servico, '405px');
    $form->inputDecimal('fDesconto', 'Desconto', $deconto, true, null, null, $acessoDesconto);
    $form->inputDate('fVencimento', 'Vencimento', $vencimento, false);
    /* $form->textArea('fObservacao', 'Observação', $observacao, 255, FALSE, '400px'); */
}
$form->endFieldSet();

if (isset ($pId) and is_numeric($pId) and $pId > 0) {

    $pColNames = array('Código', 'Data', 'Valor da Parcela');
    $pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                       "{name:'data',index:'data', width:200, align:'center'}",
                        "{name:'valor',index:'valor', width:200, align:'right'}");
    $pSortName = 'data';

    $grdParcelas = new modulos_global_grid('grdCARPrestacoes', 'Prestações', 'modulos/contasareceber/parcelas.xml.php?idalunoservico='.$pId, $pColNames, $pColModel, $pSortName, false);

    $form->startFieldSet('fdCARParcelas', 'Parcelas');
    $form->inputDate('fParcData', 'Data', null, true);
    $form->inputDecimal('fParcValor', 'Valor', null, false);
    $form->divClear(1);
    $form->buttonAdicionar('btnAddParcela');
    $form->buttonCustom('fExcParcela', 'Excluir', 'ui-icon-trash');
    $form->divClear(1);
    $form->nullArray($grdParcelas->resultGrid());
    $form->endFieldSet();


    $pColNames = array('Código', 'Data', 'Valor Pago');
    $pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                       "{name:'data',index:'data', width:200, align:'center'}",
                        "{name:'valor',index:'valor', width:200, align:'right'}");
    $pSortName = 'data';

    $grid = new modulos_global_grid('grdCadContasAReceber', 'Parcelas', 'modulos/contasareceber/form.xml.php?idalunoservico='.$pId, $pColNames, $pColModel, $pSortName, true);

    $form->startFieldSet('fdContasAReceberItens', 'Pagamento');
    $form->null('<table width="100%" border="0" cellspacing="5"><tr><td>');
    $form->inputDecimal('fValorPago', 'Valor Pago', db_to_float(0), true);
    $form->inputTextStatic('fUserLogged', 'Usuário Logado', $usuarioLogado, false, "400px");
    $form->null('</td></tr><tr><td>');
    if ($acesso->getFuncionalidade(3)) {
        $form->buttonAdicionar('btnAddItensContasAReceber');
    }
    if ($acesso->getFuncionalidade(4)) {
        $form->buttonExc('btnExcItensContasAReceber', 'Excluir', $grid->getGridName(), 'IdField', '$pUrlRetorno', '$pUrlDelete');
    }
    $form->buttonImprimir('fDeclaracao', 'Imprimir Declaração');
    $form->null('</td></tr><tr><td>');
    $form->nullArray($grid->resultGrid());
    $form->null('</td></tr></table>');
    $form->endFieldSet();
}

$form->close();

?>
<script type="text/javascript">
function mensagemAlert(pMsg) {
    alert(pMsg);
}
$(document).ready(function(){
    $("#fDeclaracao").click(function(){
        var idalunoservico = "idalunoservico=<?php echo $pId; ?>";
        var tiporel = "&tiporel=1";
        openRelatorio("modulos/declarapagamento/emissao.php?"+idalunoservico+tiporel);
        event.preventDefault();
    });
    <?php if (isset ($pId) and is_numeric($pId) and $pId > 0) { ?>
    $("#btnAddItensContasAReceber").click(function(event){
        $.post(
            "modulos/contasareceber/save.php",
            { idalunoservico : '<?php echo $pId; ?>',
                valorpago : $("#fValorPago").val()
            },
            function(data){
                if (data.retornoStatus == 'save') {
                    $("#fValorPago").val("0,00");
                    jQuery("#<?php echo $grid->getGridName(); ?>").trigger("reloadGrid");
                } else {
                    divAlertCustomBasic('<?php echo $form->getdivAlertName(); ?>', data.msg);
                }
            }, "json");
        event.preventDefault();
    });
    $("#btnAddParcela").click(function(event){
        $.post(
            "modulos/contasareceber/saveparcela.php",
            { idalunoservico : '<?php echo $pId; ?>',
                data : $("#fParcData").val(),
                valor : $("#fParcValor").val()
            },
            function(data){
                if (data.retornoStatus == 'save') {
                    $("#fParcData").val("");
                    $("#fParcValor").val("0,00");
                    jQuery("#<?php echo $grdParcelas->getGridName(); ?>").trigger("reloadGrid");
                } else {
                    divAlertCustomBasic('<?php echo $form->getdivAlertName(); ?>', data.msg);
                }
            }, "json");
        event.preventDefault();
    });
    $("#btnExcItensContasAReceber").click(function(){
        var idSelRow = jQuery("#<?php echo $grid->getGridName(); ?>").jqGrid('getGridParam','selrow');
        if (idSelRow) {
            var ret = jQuery("#<?php echo $grid->getGridName(); ?>").jqGrid('getRowData', idSelRow);
            $.post(
            "modulos/contasareceber/delpag.php",
            {
                id : ret.id
            },
            function(data){
                if (data.retornoStatus == 'delete') {
                    jQuery("#<?php echo $grid->getGridName(); ?>").trigger("reloadGrid");
                } else {
                    divAlertCustomBasic('<?php echo $form->getdivAlertName(); ?>', data.msg);
                }
            }, "json");
        } else {
            divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>","Selecione uma linha.");
        }        
        event.preventDefault();
    });
    $("#fExcParcela").click(function(){
        var idSelRow = jQuery("#<?php echo $grdParcelas->getGridName(); ?>").jqGrid('getGridParam','selrow');
        if (idSelRow) {
            var ret = jQuery("#<?php echo $grdParcelas->getGridName(); ?>").jqGrid('getRowData', idSelRow);
            $.post(
            "modulos/contasareceber/excluirparcela.php",
            {
                id : ret.id
            },
            function(data){
                if (data.retornoStatus == 'delete') {
                    jQuery("#<?php echo $grdParcelas->getGridName(); ?>").trigger("reloadGrid");
                } else {
                    divAlertCustomBasic('<?php echo $form->getdivAlertName(); ?>', data.msg);
                }
            }, "json");
        } else {
            divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>","Selecione uma linha.");
        }
        event.preventDefault();
    });
    <?php } else { ?>
    $("#btnSaveContaReceber").click(function(event){
        $.post(
            "modulos/contasareceber/newconta.php",
            { idaluno : $("#fIdAluno").val(),
                idtiposervico : $("#fServico").val(),
                desconto : $("#fDesconto").val(),
                vencimento : $("#fVencimento").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/contasareceber/index.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
    $("#fServico").change(function(){
        $.post(
            "modulos/contasareceber/vencimento.php",
            {
                id : $(this).val()
            },
            function(data) {
                $("#fVencimento").val(data.vencimento);
            },
            "json");
    });
    <?php } ?>
});
</script>