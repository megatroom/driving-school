<?php
include_once("../../configuracao.php");

$mysql = new modulos_global_mysql();

$carros = $mysql->select("id, carro", "vcarros", "datavenda is null", null, "carro");
$lstCarros = null;
$lstCarros["TODOS"] = "TODOS";
if (is_array($carros)) {
    foreach ($carros as $carro) {
        $lstCarros[$carro["id"]] = $carro["carro"];
    }
}

$acesso = new modulos_usuarios_funcionalidades(8);

$pColNames = array('Código', 'Data', 'Categoria');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'data',index:'data', width:200, align:'center'}",
                    "{name:'categoria',index:'categoria', width:200, align:'center'}");
$pSortName = 'data';

$mainGridFun = new modulos_global_grid('grdExamePratico', 'Datas de Exame Prático', 'modulos/examepratico/index.xml.php?status=A', $pColNames, $pColModel, $pSortName, false);

$form = new modulos_global_form('frmExamePratico');

$form->divAlert();

$form->selectFixed('fCarro', 'Carro', false, $lstCarros, null, "400px");

$form->divClear(1);

$form->checkbox('fStatus', 'Exibir somente exames ativos', null, true);

$form->divClear(1);

$form->checkbox('fDebito', 'Exibir débito do aluno.', null, true);

$form->divClear(1);

$form->nullArray($mainGridFun->resultGrid());

$form->divClear(1);

$form->buttonVisualizar('btnVisualizar');
$form->buttonImprimir('btnImprimir');
$form->buttonExcel('btnGerarExcel');
$form->buttonWord('btnGerarWord');

$form->close();

?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#fStatus").click(function(){
            carregarGrid();
        });
        $("#btnVisualizar").click(function(event){
            emitirRelatorio(1);
            event.preventDefault();
        });
        $("#btnImprimir").click(function(event){
            emitirRelatorio(2);
            event.preventDefault();
        });
        $("#btnGerarExcel").click(function(event){
            emitirRelatorio(3);
            event.preventDefault();
        });
        $("#btnGerarWord").click(function(event){
            emitirRelatorio(4);
            event.preventDefault();
        });
    });
    function emitirRelatorio(pTipoRel) {
        var idSelRow = jQuery("#<?php echo $mainGridFun->getGridName(); ?>").jqGrid('getGridParam','selrow');
        if (idSelRow) {
            var ret = jQuery("#<?php echo $mainGridFun->getGridName(); ?>").jqGrid('getRowData', idSelRow);
            var idaluno = "&idexamepratico=" + ret.id;
            var idcarro = "&idcarro=" + $("#fCarro").val();
            var vTipoRel = "tiporel="+pTipoRel;
            var vDebito = "&debito=N";
            if ($("#fDebito").attr('checked')) {
                vDebito = "&debito=S";
            }
            openRelatorio("relatorios/examepraticoalunos/emissao.php?"+vTipoRel+idaluno+idcarro+vDebito);
        } else {
            mensagemAlert("Selecione um exame!");
        }
    }
    function mensagemAlert(pTexto) {
        divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>", pTexto);
    }
    function carregarGrid() {
        var vUrl = 'relatorios/examepraticoalunos/index.xml.php';
        if ($("#fStatus").attr('checked')) {
            vUrl = vUrl + '?status=A';
        }
        jQuery("#<?php echo $mainGridFun->getGridName(); ?>").jqGrid('setGridParam',{url:vUrl}).trigger("reloadGrid");
    }
</script>