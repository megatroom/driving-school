<?php
include_once("../../configuracao.php");

class modulos_carros_carro {

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

        $pColNames = array('Código', 'Tipo', 'Descrição', 'Placa');
        $pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                           "{name:'tipo',index:'tipo', width:150}",
                           "{name:'descricao',index:'descricao', width:250}",
                            "{name:'placa',index:'placa', width:100}");
        $pSortName = 'tipo,descricao';

        $mainGridFun = new modulos_global_grid('grdCnsCarro', 'Carros', 'modulos/carros/carro.xml.php', $pColNames, $pColModel, $pSortName, true);
        
        $mainGridFun->eventOnDblClickRowConsLookUp("dCnsCarroLookUp", $pNameIdInput, 'descricao', $pNameDescInput);

        $resultado = null;
        $resultado[] = '<div id="dCnsCarroLookUp" style="display:none;">';
        $resultado = array_merge($resultado, $mainGridFun->resultGrid());
        $resultado[] = '</div>';

        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#<?php echo $pNameButton; ?>").click(function(event){
                    if ($("#dCnsCarroLookUp").css('display') == 'none') {
                        $("#dCnsCarroLookUp").show('slow');
                    } else {
                        $("#dCnsCarroLookUp").hide('slow');
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