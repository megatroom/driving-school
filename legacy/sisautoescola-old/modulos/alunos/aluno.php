<?php
include_once("../../configuracao.php");

class modulos_alunos_aluno {

    private $id = null;
    private $nome = null;

    private $formConsultaNome = null;

    function __construct($pId = null) {
        if (isset ($pId)) {
            $this->id = $pId;
        } else {
            $this->id = 0;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    function findById($pId) {
        $this->setId($pId);

        $mysql = new modulos_global_mysql();

        $rows = $mysql->select('*', 'valunos', "id = '".$this->getId()."'");

        if (is_array($rows)) {
            foreach ($rows as $row) {
                $this->setNome($row["nome"]);
            }
        }
    }

    public function formConsulta($pName, $pNameButton, $pNameIdInput, $pNameDescInput) {
        $this->formConsultaNome = 'dvCnsFunc'.$pName;

        $pColNames = array('Código', 'Matrícula', 'Matrícula CFC', 'Nome', 'CPF');
        $pColModel = array( "{name:'id',index:'id', hidden:true}",
                            "{name:'matricula',index:'matricula', width:100}",
                            "{name:'matriculacfc',index:'matriculacfc', width:100}",
                            "{name:'nome',index:'nome', width:300}",
                            "{name:'cpf',index:'cpf', width:100}");
        $pSortName = 'nome';

        $mainGridFun = new modulos_global_grid('grdCnsAlunos', 'Alunos', 'modulos/alunos/aluno.xml.php', $pColNames, $pColModel, $pSortName, true);
        $mainGridFun->eventOnDblClickRowConsLookUp("dCnsAlunosLookUp", $pNameIdInput, 'nome', $pNameDescInput);

        $resultado = null;
        $resultado[] = '<div id="dCnsAlunosLookUp" style="display:none;">';
        $resultado = array_merge($resultado, $mainGridFun->resultGrid());
        $resultado[] = '</div>';

        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#<?php echo $pNameButton; ?>").click(function(event){
                    if ($("#dCnsAlunosLookUp").css('display') == 'none') {
                        $("#dCnsAlunosLookUp").show('slow');
                    } else {
                        $("#dCnsAlunosLookUp").hide('slow');
                    }
                    event.preventDefault();
                });
            });
        </script>
        <?php

        return $resultado;

    }

    public function getNomeFormConsulta() {
        return $this->formConsultaNome;
    }

}

?>