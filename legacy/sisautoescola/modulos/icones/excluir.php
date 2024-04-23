<?php
include_once("../../configuracao.php");

$idtela = $_POST["idtela"];

$diretorio = str_replace("modulos\\icones", "", getcwd()) ."icones";
$pDir = opendir($diretorio);
$lstIconesDir = null;
while ($nome_itens = readdir($pDir)) {
	if (strrpos(strtolower($nome_itens), ".jpg") > 0 or
		strrpos(strtolower($nome_itens), ".gif") > 0 or
		strrpos(strtolower($nome_itens), ".png") > 0) {
		$lstIconesDir[] = $nome_itens;
	}
}

$mysql = new modulos_global_mysql();

$form = new modulos_global_form('frmChangeIcones');

$form->divAlert();

$form->buttonCustom('btnVoltar', 'Voltar', 'ui-icon-close');

$form->divClear(1);

$form->startFieldSet('fdIconeDisponivel', 'Escolha o novo ícone: ');
$iContIcon = 1;
foreach ($lstIconesDir as $iconesDir) {
	$txt  = '<img width="50px" height="50px" style="padding:10px;cursor:pointer;" ';
	$txt .= 'id="iconeDir" src="icones/'.$iconesDir.'" />';
	$form->null($txt);
	if ($iContIcon == 5) {
		$form->null('<br />');
		$iContIcon = 1;
	} else {
		$iContIcon++;
	}
}
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnVoltar").click(function(){
            novaAbaMenuPrincipalComParametro("modulos/icones/change.php", { idtela : "<?php echo $idtela; ?>" }, "Alteração do ícone");
            event.preventDefault;
        });
        $("#iconeDir").live('click', function(){
            var vIcone = $(this).attr("src");
            if (confirm("Deseja realmente excluir o ícone selecionado?")) {
                $.post("modulos/icones/excfile.php", { icone : vIcone }, function(){
                    novaAbaMenuPrincipalComParametro("modulos/icones/change.php", { idtela : "<?php echo $idtela; ?>" }, "Alteração do ícone");
                });
            }
        })
    });
</script>