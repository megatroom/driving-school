<?php
include_once("../../configuracao.php");

$type_msg = null;
if (isset($_GET["type_msg"])) {
    $type_msg = $_GET["type_msg"];
}

$mysql = new modulos_global_mysql();

$lstTelas = $mysql->select(
        "a.id as idmodulo, a.descricao as modulo, b.id as idtela, b.descricao as tela",
        "modulos a, telas b",
        "a.id = b.idmodulo",
        NULL,
        "a.ordem, b.ordem");

?>
<style>
    .sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
    .sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
    .sortable li span { position: absolute; margin-left: -1.3em; }
</style>
<script type="text/javascript">
    $(function() {
        <?php
            $lastModulo = "";
            foreach ($lstTelas as $row) {
                if ($lastModulo != $row["idmodulo"]) {
                    echo '$( "#ulModulo'.$row["idmodulo"].'" ).sortable(); ';
                    echo '$( "#ulModulo'.$row["idmodulo"].'" ).disableSelection(); ';
                    $lastModulo = $row["idmodulo"];
                }
            }
        ?>
    });
</script>
<?php

$form = new modulos_global_form('frmMenu');

$form->divAlert();

$form->buttonSave('btnSaveMenu');
$form->divClear(1);

$form->close();

$lastModulo = "";
foreach ($lstTelas as $row) {
    if ($lastModulo != $row["idmodulo"]) {
        if ($lastModulo != "") {
            echo '</ul>';
        }
        echo '<h3>'.$row["modulo"].'</h3>';
        echo '<ul id="ulModulo'.$row["idmodulo"].'" class="sortable" >';
        $lastModulo = $row["idmodulo"];
    }
    echo '<li class="ui-state-default">';
    echo '<input type="hidden" value="'.$row["idmodulo"].'" />';
    echo '<input type="hidden" value="'.$row["idtela"].'" />';
    echo '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>';
    echo $row["tela"];
    echo '</li>';
}

echo '</ul>';

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
        $("#btnSaveMenu").click(function(event){
            var vTelas = "";
            $("li").each(function(){
                if (vTelas == "") {
                    vTelas = $(this).children().val() + "," + $(this).children().next().val();
                } else {
                    vTelas = vTelas + "|" + $(this).children().val() + "," + $(this).children().next().val();
                }
            });
            $.post(
                "modulos/menu/save.php",
                {
                    telas : vTelas
                },
                function(data){
                    postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/menu/index.php','<?php echo $form->getdivAlertName(); ?>');
                },
                "json");
            event.preventDefault();
        });
    });
</script>