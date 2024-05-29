<?php
include_once("../../configuracao.php");

$mysql = new modulos_global_mysql();

$acesso = new modulos_usuarios_funcionalidades(46);

$form = new modulos_global_form('RelResultadosET');

$rows = $mysql->select('v.nome, v.id', 'vfuncionarios v', null, null, 'v.nome');
$lstFuncionarios[""] = "TODOS";
if (is_array($rows)) {
    foreach ($rows as $row) {
        $lstFuncionarios[$row["id"]] = $row["nome"];
    }
}
$selectFun = null;
$enableFun = null;
if (!$acesso->getFuncionalidade(1)) {
    $selectFun = $mysql->getValue(
            'idfuncionario',
            null,
            'vusuarios',
            "id = '".$acesso->getIdUsaurioLogado()."'",
            null,
            null,
            null);
    $enableFun = 'disabled="true"';
}

$form->divAlert();

$form->startFieldSet('fdRelResultadosET');
$form->inputDate('fDataI', 'Data Inicial', '01'.date('/m/Y'), true);
$form->inputDate('fDataF', 'Data Final', date('t/m/Y'), true);
$form->divClear(1);
$form->selectFixed('fFuncionario', 'Funcionário', FALSE, $lstFuncionarios, $selectFun, "420px", $enableFun);
$form->divClear();
$form->endFieldSet();

$form->buttonVisualizar('btnVisualizarRelVales');
$form->buttonImprimir('btnImprimirRelVales');
$form->buttonExcel('btnGerarExcelRelVales');
$form->buttonWord('btnGerarWordRelVales');

$form->close();

?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnVisualizarRelVales").click(function(event){
            emitirRelatorio(1);
            event.preventDefault();
        });
        $("#btnImprimirRelVales").click(function(event){
            emitirRelatorio(2);
            event.preventDefault();
        });
        $("#btnGerarExcelRelVales").click(function(event){
            emitirRelatorio(3);
            event.preventDefault();
        });
        $("#btnGerarWordRelVales").click(function(event){
            emitirRelatorio(4);
            event.preventDefault();
        });
    });
    function emitirRelatorio(pTipo) {
        var datai = "&datai=" + $("#fDataI").val();
        var dataf = "&dataf=" + $("#fDataF").val();
        var vTipo = "tipo="+pTipo;
        var fun = "&idfuncionario=" + $("#fFuncionario").val();
        openRelatorio("relatorios/vales/emissao.php?"+vTipo+datai+dataf+fun);
    }
    function mensagemAlert(pTexto) {
        divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>", pTexto);
    }
</script>