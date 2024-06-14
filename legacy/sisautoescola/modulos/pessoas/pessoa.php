<?php

include_once("../../configuracao.php");

class modulos_pessoas_pessoa {

    private $id;

    public function __construct($pId) {
        $this->id = $pId;
    }

    /**
     *
     * @param <type> $pTable Tabela que não incluirá na validação
     */
    public function isDeleted($pTable) {

        $mysql = new modulos_global_mysql();

        if ($pTable == 'funcionarios') {
            $funcionario = true;
        } else {
            if ($mysql->getValue('id', null, 'funcionarios', "idpessoa = '".$this->id."'")) {
                $funcionario = false;
            } else {
                $funcionario = true;
            }
        }

        if ($pTable == 'clientes') {
            $cliente = true;
        } else {
            if ($mysql->getValue('id', null, 'clientes', "idpessoa = '".$this->id."'")) {
                $cliente = false;
            } else {
                $cliente = true;
            }
        }

        if ($pTable == 'alunos') {
            $alunos = true;
        } else {
            if ($mysql->getValue('id', null, 'alunos', "idpessoa = '".$this->id."'")) {
                $alunos = false;
            } else {
                $alunos = true;
            }
        }

        return $funcionario and $cliente and $alunos;

    }

}

?>
