<?php
include_once("../../configuracao.php");

$form = new modulos_global_form('frmOpcaorel');

$form->null('<h3>Escolha o tipo de relatório a se editar o conteúdo:<h3>');

$form->buttonCustom('btnAulaPratica', 'Aula Prática', 'ui-icon-comment');
$form->buttonCustom('btnAulaTeorica', 'Aula Teórica', 'ui-icon-comment');
$form->buttonCancel('btnCancelar', 'Voltar', 'relatorios/aulasalunos/index.php');

$form->close();

?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnAulaPratica").click(function(event){
            editarConteudo(1);
            event.preventDefault();
        });
        $("#btnAulaTeorica").click(function(event){
            editarConteudo(2);
            event.preventDefault();
        });
    });
    function editarConteudo(pTipo) {
        vTipo = "pTipo=" + pTipo;
        window.open('relatorios/aulasalunos/editor.php?' + vTipo);
    }
</script>