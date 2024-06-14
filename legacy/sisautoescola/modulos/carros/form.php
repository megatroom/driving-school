<?php
include_once("../../configuracao.php");

$pId = null;
if (isset($_GET["pId"])) {
    $pId = $_GET["pId"];
}

$idtipocarro = null;
$descricao   = null;
$placa       = null;
$ano         = null;
$anomodelo   = null;
$datacompra  = null;
$datavenda   = null;
$idfunfixo   = null;

$mysql = new modulos_global_mysql();

$lstTipos = null;
$tiposcarros = $fieldList = $mysql->select('id, descricao', 'tipocarros', null, null, "descricao");
if (is_array($tiposcarros)) {
    foreach ($tiposcarros as $tipo) {
        $lstTipos[$tipo["id"]] = $tipo["descricao"];
    }
} else {
    echo '<h2>Não há tipos de carros cadastrados</h2>';
}

$lstInstrutores = null;
$instrutores = $mysql->select('id, nome', 'vfuncionarios', null, null, 'nome');
if (is_array($instrutores)) {
    $lstInstrutores[""] = "";
    foreach ($instrutores as $instrutor) {
        $lstInstrutores[$instrutor["id"]] = $instrutor["nome"];
    }
}

if (isset ($pId)) {    
    $fieldList = $mysql->select('*', 'carros', "id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }

    if (isset ($datacompra)) {
        $datacompra = db_to_date($datacompra);
    }
    if (isset ($datavenda)) {
        $datavenda = db_to_date($datavenda);
    }
}

$form = new modulos_global_form('Carros');

$form->buttonSave("saveCarro");
$form->buttonCancel("closeCarro",null,"modulos/carros/index.php");
$form->divClear(1);

$form->divAlert();

$form->inputHidden('fidcarro',$pId);

$form->startFieldSet('carro_basic');
$form->selectFixed('ftipo', 'Tipo', false, $lstTipos, $idtipocarro, "450px");
$form->inputText('fdescricao', 'Descrição', $descricao, "30", false, "450px");
$form->inputPlaca('fplaca', 'Placa', $placa, true);
$form->inputAno('fAno', 'Ano Fabricação', $ano, true);
$form->inputAno('fAnoModelo', 'Ano Modelo', $anomodelo, false);
$form->inputDate('fdatacompra', 'Data de Compra', $datacompra, true);
$form->inputDate('fdatavenda', 'Data de Venda', $datavenda, false);
$form->selectFixed('ffunfixo', 'Instrutor Fixo', false, $lstInstrutores, $idfunfixo, "450px");
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#saveCarro").click(function(event){
        $.post(
            "modulos/carros/save.php",
            { id : $("#fidcarro").val(),
                idtipocarro : $("#ftipo").val(),
                descricao : $("#fdescricao").val(),
                placa : $("#fplaca").val(),
                ano : $("#fAno").val(),
                anomodelo : $("#fAnoModelo").val(),
                datacompra : $("#fdatacompra").val(),
                datavenda : $("#fdatavenda").val(),
                idfunfixo : $("#ffunfixo").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/carros/index.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
</script>