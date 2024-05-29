<?php
include_once("../../configuracao.php");

$form = new modulos_global_form("login");
$form->divAlert();
$form->close();

$mysql = new modulos_global_mysql();

$theme = $mysql->getValue('valor', 'valor', 'sistema', "campo = 'tema'");

if (!isset ($theme) or $theme == "") {
    $theme = 'humanity';
}
?>
<form method="post">
    <table cellpadding="3" cellspacing="3" border="0" >
        <tr>
            <td>
                <img style="width: 90px; height: 90px;" src="css/<?php echo $theme; ?>/images/client.png" />
            </td>
            <td>
                <table cellspacing="5" border="0">
                    <tr>
                        <td><b>Usuário:</b></td>
                        <td><input type="text" id="pLogin" name="pLogin" value="" /></td>
                    </tr>
                    <tr>
                        <td><b>Senha:</b></td>
                        <td><input type="password" id="pSenha" name="pSenha" value="" /></td>
                    </tr>
                    <tr>&nbsp;<br>
                        <td colspan="2" align="right" valign="bottom">
                            <a id="btnLogin" style="float: none;" class="fg-button ui-state-default ui-corner-all fg-button-icon-left" href="#" >
                                <span id="iconBtnLogin" class="ui-icon ui-icon-circle-check"/>
                                Acessar
                            </a> 
                        </td>
                    </tr>
                </table> 
             </td>
        </tr>
    </table>
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnLogin").click(function(event){
            $.post('modulos/usuarios/auth.php', {
                user: $("#pLogin").val(),
                pwd: $("#pSenha").val()
            }, function(data){
                if (data.retorno == "ok") {
                    window.location.href = "index.php";
                } else {
                    divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>", data.retorno);
                }
            }, "json");
            event.preventDefault();
        });
        $('#pLogin').keyup(function(key) {
            if(key.keyCode == 13) {
                $('#pSenha').focus();
            }
        });
        $('#pSenha').keyup(function(e) {
            if(e.keyCode == 13) {
                $('#btnLogin').trigger('click');
            }
        });
        $('#pLogin').focus();
    });
</script>