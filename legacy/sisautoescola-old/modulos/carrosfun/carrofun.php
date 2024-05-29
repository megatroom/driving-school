<?php
include_once("../../configuracao.php");

class modulos_carrosfun_carrofun {

    private $id = null;

    private $formConsultaNome = null;

    function __construct($pId = null) {
        if (isset ($pId)) {
            $this->id = $pId;
        } else {
            $this->id = 0;
        }
    }

    public function formConsulta($pName, $pNameButton, $pNameIdInput, $pNameDescInput) {
        $this->formConsultaNome = 'dvCnsFunc'.$pName;

        $pColNames = array('Código', 'Nome Completo', 'Carro', 'Placa', 'Funcionário');
        $pColModel = array("{name:'id',index:'id', hidden:true}",
                            "{name:'nomecompleto',index:'nomecompleto', hidden:true}",
                            "{name:'descricao',index:'descricao', width:200}",
                            "{name:'placa',index:'placa', width:100}",
                            "{name:'funcionario',index:'funcionario', width:200}");
        $pSortName = 'nome';

        $mainGrid = new modulos_global_grid('grdCnsCarroFun', 'Carro', 'modulos/carrosfun/carrofun.xml.php', $pColNames, $pColModel, $pSortName, true);
        $mainGrid->eventOnDblClickRowConsLookUp("dCnsCarroFunLookUp", $pNameIdInput, 'nomecompleto', $pNameDescInput);

        $resultado = null;
        $resultado[] = '<div id="dCnsCarroFunLookUp" style="display:none;">';
        $resultado = array_merge($resultado, $mainGrid->resultGrid());
        $resultado[] = '</div>';

        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#<?php echo $pNameButton; ?>").click(function(event){
                    if ($("#dCnsCarroFunLookUp").css('display') == 'none') {
                        $("#dCnsCarroFunLookUp").show('slow');
                    } else {
                        $("#dCnsCarroFunLookUp").hide('slow');
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