<?php
include_once("../../configuracao.php");

$divName = $_POST["divName"];
$idcarro = $_POST["idcarro"];
$data = $_POST["data"];
$hora = $_POST["hora"];
$idturno = $_POST["idturno"];

/* como é exibido vários desses na tela
 * é preciso cirar um nome único para cada componente
 * entao estou usando a hora, já que esta tela nao fica mais de 24h aberta
 */
$nameKey = date('His');

$mysql = new modulos_global_mysql();

$idfuncionario = $mysql->getValue(
                            "a.idfuncionario",
                            'idfuncionario',
                            'carrofuncionario a, vfuncionarios b',
                            "a.idfuncionario = b.id ".
                                "and TIMESTAMP(a.data, a.hora) = ( ".
                                "SELECT max(TIMESTAMP(x.data, x.hora)) ".
                                "FROM carrofuncionario x ".
                                "where TIMESTAMP(x.data, x.hora) <= '".date_to_db($datacount)." ".date('H:i', $horacount)."' ".
                                " and x.idcarro = '".$idcarro."')");

$rows = $mysql->select(
                    'id, nome',
                    'vfuncionarios',
                    "id != '".$idfuncionario."'",
                    null,
                    "nome");
$lstInstrutores = null;
if (is_array($rows)) {
    foreach ($rows as $row) {
        $lstInstrutores[$row["id"]] = $row["nome"];
    }
}

$form = new modulos_global_form('frmInstNome');

$form->startFieldSet('fdInstNome'.$nameKey);
$form->selectFixed('fNovoInstrutor'.$nameKey, 'Novo Instrutor', false, $lstInstrutores);
$form->divClear(1);
$form->buttonCustom('bInstNomeCancel'.$nameKey, 'Cancelar', 'ui-icon-close');
$form->buttonSave('bInstNomeSave'.$nameKey);
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#<?php echo 'bInstNomeCancel'.$nameKey; ?>").click(function(event){
            $("#<?php echo 'fdInstNome'.$nameKey; ?>").hide();
            event.preventDefault();
        });
        $("#<?php echo 'bInstNomeSave'.$nameKey; ?>").click(function(event){
            $.post("modulos/aulaspraticas/trocarinstsave.php", {
                idfuncionario : $("#<?php echo 'fNovoInstrutor'.$nameKey; ?>").val(),
                idcarro : '<?php echo $idcarro; ?>',
                idturno : '<?php echo $idturno; ?>',
                data : '<?php echo $data; ?>',
                hora : '<?php echo $hora; ?>'
            }, function(data){
                if (data.returnStatus == "save") {
                    $("#bPesqAulasPraticas").trigger('click');
                } else {
                    alert(data.msg);
                }
            }, "json");
            $("#<?php echo 'fdInstNome'.$nameKey; ?>").hide();
            event.preventDefault();
        });
    });
</script>