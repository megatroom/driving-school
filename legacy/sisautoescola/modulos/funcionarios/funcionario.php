<?php

include_once("../../configuracao.php");

class modulos_funcionarios_funcionario {

    private $id = null;
    private $nome = null;


    private $formConsultaNome = null;

    public function setId($pId) {
        $this->id = $pId;
    }

    public function getId() {
        return $this->id;
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

        $rows = $mysql->select('*', 'vfuncionarios', "id = '".$this->getId()."'");

        if (is_array($rows)) {
            foreach ($rows as $row) {
                $this->setNome($row["nome"]);
            }
        }
    }

    function __construct($pId = null) {
        if (isset ($pId)) {
            $this->id = $pId;
        } else {
            $this->id = 0;
        }
    }

    public function formConsulta($pName, $pNameButton, $pNameIdInput, $pNameDescInput) {
        $this->formConsultaNome = 'dvCnsFunc'.$pName;
        
        $pColNames = array('Código', 'Matrícula', 'Nome', 'Telefone', 'Celular');
        $pColModel = array("{name:'id',index:'id', hidden:true}",
                            "{name:'matricula',index:'matricula', width:80}",
                            "{name:'nome',index:'nome', width:300}",
                            "{name:'telefone',index:'telefone', width:100}",
                            "{name:'celular',index:'celular', width:100}");
        $pSortName = 'nome';

        $mainGridFun = new modulos_global_grid('grdCnsFun', 'Funcionários', 'modulos/funcionarios/index.xml.php', $pColNames, $pColModel, $pSortName, true);
        $mainGridFun->eventOnDblClickRowConsLookUp("dCnsFunLookUp", $pNameIdInput, 'nome', $pNameDescInput);

        $resultado = null;
        $resultado[] = '<div id="dCnsFunLookUp" style="display:none;">';
        $resultado = array_merge($resultado, $mainGridFun->resultGrid());
        $resultado[] = '</div>';

        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#<?php echo $pNameButton; ?>").click(function(event){
                    if ($("#dCnsFunLookUp").css('display') == 'none') {
                        $("#dCnsFunLookUp").show('slow');
                    } else {
                        $("#dCnsFunLookUp").hide('slow');
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