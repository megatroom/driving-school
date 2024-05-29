<?php
include_once("../../configuracao.php");

$type_msg = NULL;
if (isset($_GET["type_msg"])) {
    $type_msg = $_GET["type_msg"];
}

$pColNames = array('Código', 'Matrícula', 'Matrícula CFC', 'Nome', 'CPF', 'RENACH', 'Telefone');
$pColModel = array( "{name:'id',index:'id', hidden:true}",
                    "{name:'matricula',index:'matricula', width:100}",
                    "{name:'matriculacfc',index:'matriculacfc', width:100}",
                    "{name:'nome',index:'nome', width:300}",
                    "{name:'cpf',index:'cpf', width:100}",
                    "{name:'renach',index:'renach', width:100}",
                    "{name:'telefone',index:'telefone', width:100}");
$pSortName = 'nome';

$mainGridCli = new modulos_global_grid(
        'mainGrdAluno',
        'Alunos',
        'modulos/servicos/index.xml.php',
        $pColNames,
        $pColModel,
        $pSortName,
        true,
        false,
        3);

$form = new modulos_global_form('Clientes');

$form->divAlert();

$closeId = 0;
if (isset($_GET["pCloseId"])) {
    $closeId = $_GET["pCloseId"];
}

$form->buttonAlt('bAltAluno','Selecionar Aluno',$mainGridCli->getGridName(), 'id', 'modulos/servicos/form.php');
$form->buttonClose($closeId, "bCloseAluno");

$form->divClear(1);

$form->startFieldSet('fdFiltroAdvanced', 'Filtro Avançado');
$form->inputText('ftNome', 'Nome', null, null, false, "500px");
$form->endFieldSet();

$form->close();

$mainGridCli->eventOnDblClickRowAlterRow('id', 'modulos/servicos/form.php');
$mainGridCli->drawGrid();

?>
<script type="text/javascript">
<?php

if (isset ($type_msg) and strlen($type_msg) > 0) {
    if ($type_msg == "save" or $type_msg == "delete") {
        echo "postSucess('".$form->getdivAlertName()."','".$type_msg."');";
    }
}

?>
    $(document).ready(function(){
        $("#ftNome").keypress(function(event){
            if ( event.which == 13 ) {
                carregarGrid();
            }
        });       
    });
    
    function carregarGrid() {
        var vUrl = 'modulos/servicos/index.xml.php';
        if ($("#ftNome").val() != "") {
            vUrl = vUrl + '?ftnome=' + $("#ftNome").val();
        }
        jQuery("#<?php echo $mainGridCli->getGridName(); ?>").jqGrid('setGridParam',{url:vUrl}).trigger("reloadGrid");
    }
    
</script>