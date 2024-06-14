<?php
include_once("../../configuracao.php");

$iddeclaracao = $_GET["iddeclaracao"];

$mysql = new modulos_global_mysql();

$rows = $mysql->select(
        'a.texto',
        'declaracoesitens a',
        "a.iddeclaracao = '".$iddeclaracao."'",
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
    .cke_button_myDataExPr .cke_icon
    {
        display: none !important;
    }
    .cke_button_myDataExPr .cke_label
    {
        display: inline !important;
    }
    .cke_button_myInstrExPr .cke_icon
    {
        display: none !important;
    }
    .cke_button_myInstrExPr .cke_label
    {
        display: inline !important;
    }
    .cke_button_myUserLogged .cke_icon
    {
        display: none !important;
    }
    .cke_button_myUserLogged .cke_label
    {
        display: inline !important;
    }
    .cke_button_myHoraExPr .cke_icon
    {
        display: none !important;
    }
    .cke_button_myHoraExPr .cke_label
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
                ['btnAluNome', 'btnAluCPF', 'btnAluMatrCFC', 'btnDataAtual', 'btnDataExPr', 'btnInstrExPr', 'btnUserLogged', 'btnHoraExPr'],
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
            editor.addCommand( 'myDataExPr', {
                exec : function( editor )
                {
                    editor.insertText("{dataExamePratico}");
                },
                editorFocus : true
            });
            editor.addCommand( 'myInstrExPr', {
                exec : function( editor )
                {
                    editor.insertText("{instrutorExamePratico}");
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
            editor.addCommand( 'myUserLogged', {
                exec : function( editor )
                {
                    editor.insertText("{usuarioLogado}");
                },
                editorFocus : true
            });
            editor.addCommand( 'myHoraExPr', {
                exec : function( editor )
                {
                    editor.insertText("{horaExamePratico}");
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
            editor.ui.addButton( 'btnDataExPr',
            {
                label : 'Data Exame Prático',
                command : 'myDataExPr'
            });
            editor.ui.addButton( 'btnInstrExPr',
            {
                label : 'Instrutor Exame Prático',
                command : 'myInstrExPr'
            });
            editor.ui.addButton( 'btnUserLogged',
            {
                label : 'Usuário Logado',
                command : 'myUserLogged'
            });
            editor.ui.addButton( 'btnHoraExPr',
            {
                label : 'Hora Exame Prático',
                command : 'myHoraExPr'
            });
        });
    });
</script>
</head>
<body>
    <form action="save.php" method="post">
    <input type="submit" value="Salvar" />
    <input type="hidden" value="<?php echo $_GET["iddeclaracao"]; ?>" name="iddeclaracao" />
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