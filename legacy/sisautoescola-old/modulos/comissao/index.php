<?php
include_once("../../configuracao.php");

$datai = date('d/m/Y');
$dataf = date('d/m/Y');

$tipos["A"] = "Analítico";
$tipos["C"] = "Consolidado";

$mysql = new modulos_global_mysql();

$rows = $mysql->select("v.id, v.nome", "vfuncionarios v", "id in (select distinct idfuncionario from carrofuncionario)", null, "nome");
$lstFuncionarios;
$lstFuncionarios["0"] = "TODOS";
if (is_array($rows)) {
    foreach ($rows as $row) {
        $lstFuncionarios[$row["id"]] = $row["nome"];
    }
}

$form = new modulos_global_form('frmCtrCaixa');

$form->divAlert();

$form->startFieldSet('ctrCaixaFiltro');
$form->inputDate('fDataI', 'Data Inicial', $datai, true);
$form->inputDate('fDataF', 'Data Final', $dataf, false);
$form->divClear(1);
$form->selectFixed('fFuncionario', "Funcionário", false, $lstFuncionarios, null, "400px");
$form->divClear(1);
$form->radiobutton('fTipo', $tipos, "A");
$form->divClear(1);
//$form->buttonPesquisar('btnPesquisarCaixas');
$form->endFieldSet();

$form->buttonVisualizar('btnVisualizarRelResultadosET');
$form->buttonImprimir('btnImprimirRelResultadosET');
$form->buttonExcel('btnGerarExcelRelResultadosET');
$form->buttonWord('btnGerarWordRelResultadosET');

$form->close();

?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnVisualizarRelResultadosET").click(function(event){
            emitirRelatorio(1);
            event.preventDefault();
        });
        $("#btnImprimirRelResultadosET").click(function(event){
            emitirRelatorio(2);
            event.preventDefault();
        });
        $("#btnGerarExcelRelResultadosET").click(function(event){
            emitirRelatorio(3);
            event.preventDefault();
        });
        $("#btnGerarWordRelResultadosET").click(function(event){
            emitirRelatorio(4);
            event.preventDefault();
        });
    });
    function emitirRelatorio(pTipo) {
        var datai = "&datai=" + $("#fDataI").val();
        var dataf = "&dataf=" + $("#fDataF").val();
        var fun = "&idfun=" + $("#fFuncionario").val();
        var opcao = "&opcao=" + $("#fTipo:checked").val();
        var vTipo = "tipo="+pTipo;
        openRelatorio("modulos/comissao/emissao.php?"+vTipo+datai+dataf+fun+opcao);
    }
    function mensagemAlert(pTexto) {
        divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>", pTexto);
    }
</script>
<!--
<div id="tblComissao"></div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnPesquisarCaixas").click(function(event) {
            $.post('modulos/comissao/index.xml.php',
            {
                datai : $("#fDataI").val(),
                dataf : $("#fDataF").val(),
                funcionario : $("#fFuncionario").val(),
                tipo : $("#fTipo:checked").val()
            }, function(data){
                $("#tblComissao").html(data);
            });
            event.preventDefault();
        });
    });
</script>
-->