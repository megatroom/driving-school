<?php
ob_start(); session_start(); ob_end_clean();

include_once("../../configuracao.php");

class modulos_usuarios_funcionalidades {

    private $idUsuarioLogado = 0;
    private $arrayFuncionalidades = null;
    private $mysql = null;

    public function __construct($codigoTela = 0) {
        $this->idUsuarioLogado = $_SESSION["IDUSUARIO"];

        $this->mysql = new modulos_global_mysql();

        if ($codigoTela > 0) {
            $this->setTela($codigoTela);
        }
    }

    public function getIdUsaurioLogado() {
        return $this->idUsuarioLogado;
    }

    public function setTela($codigoTela) {
        unset ($this->arrayFuncionalidades);

        $rows = $this->mysql->select(
                "a.codigo",
                "funcionalidades a ".
                "inner join acessofunc b on b.idfuncionalidade = a.id ".
                "inner join gruposusuario c on c.id = b.idgrupousuario ".
                "inner join usuariosgrupousuario d on d.idgrupousuario = c.id and d.idusuario = '".$this->idUsuarioLogado."' ".
                "inner join telas e on a.idtela = e.id and e.codigo = '".$codigoTela."'",
                null,
                null,
                "a.codigo");

        if (is_array($rows)) {
            foreach ($rows as $row) {
                $this->arrayFuncionalidades[] = $row["codigo"];
            }
        }
    }

    public function __destruct() {

    }

    public function isAdmin() {
        $isAdmin = strtolower($_SESSION["LOGIN"]) == "admin";
        return $isAdmin;
    }

    public function getFuncionalidade($pId) {
        $resultado = false;
        if ($this->isAdmin()) {
            $resultado = true;
        } else {
            if (is_array($this->arrayFuncionalidades)) {
                $resultado = in_array($pId, $this->arrayFuncionalidades);
            }
        }
        return $resultado;
    }

    public function getPermissaoTela($codigoTela) {
        $resultado = false;
        if ($this->isAdmin()) {
            $resultado = true;
        } else {
            $row = $this->mysql->getValue(
                    'count(*) as total',
                    'total',
                    'acesso a, usuariosgrupousuario b, telas c',
                    "a. idgrupousuario = b.id and a.idtela = c.id and c.codigo = '".$codigoTela."' and b.idusuario = '".$this->idUsuarioLogado."'");
            if (isset ($row) and is_numeric($row) and $row > 0) {
                $resultado = true;
            }
        }
        return $resultado;
    }

}

?>