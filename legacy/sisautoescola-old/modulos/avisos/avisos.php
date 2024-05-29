<?php
include_once("../configuracao.php");

class modulos_avisos_avisos {
    private $id = null;

    private $formConsultaNome = null;

    function __construct($pId = null) {
        if (isset ($pId)) {
            $this->id = $pId;
        } else {
            $this->id = 0;
        }
    }

}

?>