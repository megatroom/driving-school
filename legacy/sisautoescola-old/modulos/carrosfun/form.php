<?php
include_once("../../configuracao.php");

$pIdCarro = $_GET["pId"];

$acesso = new modulos_usuarios_funcionalidades(12);

if (!isset ($pIdCarro)) {
    echo "<h2>Carro não definido!</h2>";
    exit;
}

$mysql = new modulos_global_mysql();
$fieldList = $mysql->select('*', 'carros', "id = '".$pIdCarro."'");
if (is_array($fieldList)) {
    foreach ($fieldList as $fields) {
        $carrodesc = $fields["descricao"];
        $carroplaca = $fields["placa"];
    }
}

$pColNames = array('Código', 'Matrícula', 'Nome', 'Data', 'Hora');
$pColModel = array("{name:'id',index:'id', hidden:true}",
                    "{name:'matricula',index:'matricula', width:80}",
                    "{name:'nome',index:'nome', width:300}",
                    "{name:'data',index:'data', width:100,align:'center'}",
                    "{name:'hora',index:'hora', width:100,align:'center'}");
$pSortName = 'data,hora';
$gridCarFun = new modulos_global_grid('grdCarFuncionarios', 'Funcionários', 'modulos/carrosfun/form.xml.php?pIdCarro='.$pIdCarro, $pColNames, $pColModel, $pSortName, true);

$form = new modulos_global_form('Carros');

$form->buttonCancel("closeCarroFun","Sair","modulos/carrosfun/index.php");
$form->divClear(1);

$form->divAlert();

$form->inputHidden('fidcarro',$pIdCarro);

$form->startFieldSet('carro_basico', 'Carro');
$form->inputText('fdescricao', 'Descrição', $carrodesc, "30", true, "300px", null, "disabled");
$form->inputPlaca('fplaca', 'Placa', $carroplaca, false, null, null, "disabled");
$form->endFieldSet();

$form->startFieldSet('carro_funcionario', 'Funcionário responsável');
$form->inputTextStaticLookUp('funcionarios', 'ffuncionario', 'fidfuncionario', 'Funcionário', 'fbFun');
$form->null('<table><tr><td>');
$form->inputDate('fdata', 'Data', date('d/m/Y'));
$form->null('</td><td>');
$form->inputTime('fHora', 'Hora');
$form->null('</td><td valign="bottom">');
if ($acesso->getFuncionalidade(1)) {
    $form->buttonAdicionar('bAddFunToCar');
} else {
    $form->null('&nbsp;');
}
$form->null('</td><td valign="bottom">');
if ($acesso->getFuncionalidade(2)) {
    $form->buttonExc('bExcFunToCar', null, $gridCarFun->getGridName(), 'id', 'modulos/carrosfun/form.php?pId='.$pIdCarro, 'modulos/carrosfun/delete.php');
} else {
    $form->null('&nbsp;');
}
$form->null('</td><tr></table>');
$form->nullArray($gridCarFun->resultGrid());
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#bAddFunToCar").click(function(event){
        $.post(
            "modulos/carrosfun/save.php",
            { idcarro : '<?php echo $pIdCarro; ?>',
                idfuncionario : $("#fidfuncionario").val(),
                data : $("#fdata").val(),
                hora : $("#fHora").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/carrosfun/form.php?pId=<?php echo $pIdCarro; ?>','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
<?php
if (isset ($type_msg) and strlen($type_msg) > 0) {
    if ($type_msg == "save" or $type_msg == "delete") {
        echo "postSucess('".$form->getdivAlertName()."','".$type_msg."');";
    }
}
?>
</script>