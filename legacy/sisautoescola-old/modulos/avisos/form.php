<?php
include_once("../../configuracao.php");

$pId = null;
if (isset($_GET["pId"])) {
    $pId = $_GET["pId"];
}

$mysql = new modulos_global_mysql();

$lstPrioridade = null;
$lstPrioridade["0"] = avisos_prioridade_to_str(0);
$lstPrioridade["1"] = avisos_prioridade_to_str(1);
$lstPrioridade["2"] = avisos_prioridade_to_str(2);

$lstStatus = null;
$lstStatus["A"] = avisos_status_to_str('A');
$lstStatus["C"] = avisos_status_to_str('C');

$lstUsuarios = null;
$lstUsuarios[""] = '';
$rows = $mysql->select('id, nome', 'vusuarios', null, null, 'nome');
if (is_array($rows)) {
    foreach ($rows as $row) {
        $lstUsuarios[$row["id"]] = $row["nome"];
    }
}

if (isset ($pId)) {
    $fieldList = $mysql->select(
            'a.*, b.nome as nomeremetente',
            'avisos a, vusuarios b',
            "a.idremetente = b.id and a.id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }

    if (isset ($data)) {
        $data = db_to_date($data);
    }
} else {
    $idremetente = $_SESSION["IDUSUARIO"];
    $nomeremetente = $_SESSION["USUARIO_NOME"];
    $data = date('d/m/Y');
    $status = 'A';
    $prioridade = '1';
    $iddestinatario = '-1';
    $mensagem = '';
}

$form = new modulos_global_form('Carros');

$form->buttonSave("saveCarro");
$form->buttonCancel("closeCarro",null,"modulos/avisos/index.php");
$form->divClear(1);

$form->divAlert();

$form->inputHidden('fidcarro',$pId);

$form->startFieldSet('carro_basic');
$form->inputTextStatic('fDestinatario', 'Remetente:', $nomeremetente, false, '400px');
$form->inputDate('fData', 'Data', $data, true);
$form->selectFixed('fStatus', 'Status', true, $lstStatus, $status);
$form->selectFixed('fPrioridade', 'Prioridade', false, $lstPrioridade, $prioridade);
$form->null('<div style="clear:both;"></div>');
$form->selectFixed('fUsuario', 'Usuários', false, $lstUsuarios, $iddestinatario, "405px");
$form->textArea('fMensagem', 'Mensagem', $mensagem, null, false, "400px", null, 'rows="5"');
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#saveCarro").click(function(event){
        $.post(
            "modulos/avisos/save.php",
            { id : '<?php echo $pId; ?>',
                idremetente : '<?php echo $idremetente; ?>',
                data : $("#fData").val(),
                status : $("#fStatus").val(),
                prioridade : $("#fPrioridade").val(),
                iddestinatario : $("#fUsuario").val(),
                mensagem : $("#fMensagem").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/avisos/index.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
</script>