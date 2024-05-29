<?php
include_once("../../configuracao.php");

$mysql = new modulos_global_mysql();

$rows = $mysql->select(
        'a.texto',
        'relatorios a',
        "a.codigo = '1'",
        null,
        'a.id');

?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Sistema de Auto-Escola - 4 Rodas</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type" />
<script type="text/javascript" src="../../js/ckeditor/jquery15.js"></script>
<script type="text/javascript" src="../../js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../../js/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript" src="../../js/ckeditor/sample.js"></script>
<link href="../../js/ckeditor/sample.css" rel="stylesheet" type="text/css" />
<style id="styles" type="text/css">
    .cke_button_cmdNomeAluno .cke_icon
    {
        display: none !important;
    }
    .cke_button_cmdNomeAluno .cke_label
    {
        display: inline !important;
    }
    .cke_button_cmdValorRecebido .cke_icon
    {
        display: none !important;
    }
    .cke_button_cmdValorRecebido .cke_label
    {
        display: inline !important;
    }
    .cke_button_cmdDataRecebimento .cke_icon
    {
        display: none !important;
    }
    .cke_button_cmdDataRecebimento .cke_label
    {
        display: inline !important;
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
	var config = {
            toolbar:
            [
                ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
                ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
                ['Bold', 'Italic', '-', 'Underline','Strike','-','Subscript','Superscript', '-', 'NumberedList', 'BulletedList'],
                '/',
                ['UIColor'],
                ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
                ['Image','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
                '/',
                ['Styles','Format','Font','FontSize'],
                ['TextColor','BGColor'],
                '/',
                ['btnNomeAluno', 'btnValorRecebido', 'btnDataRecebimento'],
            ],
            skin : 'office2003'
	};

	$('.jquery_ckeditor').ckeditor(config);

        var editor = $('.jquery_ckeditor').ckeditorGet();

        editor.on( 'pluginsLoaded', function( ev )
        {
            editor.addCommand( 'cmdNomeAluno', {
                exec : function( editor )
                {
                    editor.insertText("{nomeAluno}");
                },
                editorFocus : true
            });
            editor.addCommand( 'cmdValorRecebido', {
                exec : function( editor )
                {
                    editor.insertText("{valorRecebido}");
                },
                editorFocus : true
            });
            editor.addCommand( 'cmdDataRecebimento', {
                exec : function( editor )
                {
                    editor.insertText("{dataRecebimento}");
                },
                editorFocus : true
            });
            editor.ui.addButton( 'btnNomeAluno',
            {
                label : 'Nome Aluno',
                command : 'cmdNomeAluno'
            } );
            editor.ui.addButton( 'btnValorRecebido',
            {
                label : 'Valor Recebido',
                command : 'cmdValorRecebido'
            } );
            editor.ui.addButton( 'btnDataRecebimento',
            {
                label : 'Data Recebimento',
                command : 'cmdDataRecebimento'
            } );
        });
    });
</script>
</head>
<body>
    <form action="save.php" method="post">
    <input type="submit" value="Salvar" id="btnSalvar" />
    <textarea class="jquery_ckeditor" cols="80" id="txtEditor" name="txtEditor" rows="10">
        <?php
        if (is_array($rows)) {
            foreach ($rows as $row) {
                echo $row["texto"];
            }
        }
        ?>
    </textarea>
    </form>
</body>
</html>