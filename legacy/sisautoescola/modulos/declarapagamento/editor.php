<?php
include_once("../../configuracao.php");

$mysql = new modulos_global_mysql();

$rows = $mysql->select(
        'a.texto',
        'declaracaopagto a',
        "a.id = (select max(id) from declaracaopagto)",
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
    .cke_button_myAluNomeCmd .cke_icon
    {
        display: none !important;
    }
    .cke_button_myAluNomeCmd .cke_label
    {
        display: inline !important;
    }
    .cke_button_myAluCPFCmd .cke_icon
    {
        display: none !important;
    }
    .cke_button_myAluCPFCmd .cke_label
    {
        display: inline !important;
    }
    .cke_button_myAluMatrCFC .cke_icon
    {
        display: none !important;
    }
    .cke_button_myAluMatrCFC .cke_label
    {
        display: inline !important;
    }
    .cke_button_myDataAtual .cke_icon
    {
        display: none !important;
    }
    .cke_button_myDataAtual .cke_label
    {
        display: inline !important;
    }
    .cke_button_myDataPgtConta .cke_icon
    {
        display: none !important;
    }
    .cke_button_myDataPgtConta .cke_label
    {
        display: inline !important;
    }
    .cke_button_myValorPgtConta .cke_icon
    {
        display: none !important;
    }
    .cke_button_myValorPgtConta .cke_label
    {
        display: inline !important;
    }
    .cke_button_myServico .cke_icon
    {
        display: none !important;
    }
    .cke_button_myServico .cke_label
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
                ['btnAluNome', 'btnAluCPF', 'btnAluMatrCFC', 'btnDataAtual', 'btnServico', 'btnDataPgtConta', 'btnValorPgtConta'],
            ],
            skin : 'office2003'
	};

	$('.jquery_ckeditor').ckeditor(config);
        
        var editor = $('.jquery_ckeditor').ckeditorGet();

        editor.on( 'pluginsLoaded', function( ev )
        {
            editor.addCommand( 'myAluNomeCmd', {
                exec : function( editor )
                {
                    editor.insertText("{nomeAluno}");
                },
                editorFocus : true
            });
            editor.addCommand( 'myAluCPFCmd', {
                exec : function( editor )
                {
                    editor.insertText("{cpfAluno}");
                },
                editorFocus : true
            });
            editor.addCommand( 'myAluMatrCFC', {
                exec : function( editor )
                {
                    editor.insertText("{cfcAluno}");
                },
                editorFocus : true
            });
            editor.addCommand( 'myDataAtual', {
                exec : function( editor )
                {
                    editor.insertText("{dataAtual}");
                },
                editorFocus : true
            });
            editor.addCommand( 'myDataPgtConta', {
                exec : function( editor )
                {
                    editor.insertText("{dataPagamento}");
                },
                editorFocus : true
            });
            editor.addCommand( 'myValorPgtConta', {
                exec : function( editor )
                {
                    editor.insertText("{valorPagamento}");
                },
                editorFocus : true
            });
            editor.addCommand( 'myServico', {
                exec : function( editor )
                {
                    editor.insertText("{descricaoServico}");
                },
                editorFocus : true
            });
            editor.ui.addButton( 'btnAluNome',
            {
                label : 'Nome Aluno',
                command : 'myAluNomeCmd'
            } );
            editor.ui.addButton( 'btnAluCPF',
            {
                label : 'CPF Aluno',
                command : 'myAluCPFCmd'
            } );
            editor.ui.addButton( 'btnAluMatrCFC',
            {
                label : 'Matricula CFC Aluno',
                command : 'myAluMatrCFC'
            } );
            editor.ui.addButton( 'btnDataAtual',
            {
                label : 'Data Atual',
                command : 'myDataAtual'
            } );
            editor.ui.addButton( 'btnDataPgtConta',
            {
                label : 'Data Pagamento',
                command : 'myDataPgtConta'
            });
            editor.ui.addButton( 'btnValorPgtConta',
            {
                label : 'Valor Pagamento',
                command : 'myValorPgtConta'
            });
            editor.ui.addButton( 'btnServico',
            {
                label : 'Serviço',
                command : 'myServico'
            });
        });
    });
</script>
</head>
<body>
    <form action="save.php" method="post">
    <input type="submit" value="Salvar" />
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