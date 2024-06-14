<?php
include_once("../../configuracao.php");

$form = new modulos_global_form('frmDeclara');

$form->buttonCustom('btnEditor', 'Editar declaração', 'ui-icon-note');

$form->close();

?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnEditor").click(function(event){
            window.open("modulos/declarapagamento/editor.php");
            event.preventDefault();
        });
    });
    function mensagemAlert(pTexto) {
        divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>", pTexto);
    }
</script>