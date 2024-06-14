<?php
include_once("configuracao.php");

$mysql = new modulos_global_mysql();

$processo = false;
$msgErro = null;

$versaoSistema = $mysql->getValue('valor', 'valor', 'sistema', "campo = 'versao'");

if (!isset($versaoSistema) or $versaoSistema == "") {
    $versaoSistema = '0';
}

function addTabela($mysql, $tableName, $fields, $other, $pProcesso) {
    $retorno = $pProcesso;
    if ($retorno) {
        $retorno = $mysql->createTable($tableName, $fields, $other);
    }
    return $retorno;
}
function erroTabela($mysql, $tableName, $pProcesso) {
    $msgErro = null;
    if (!$pProcesso) {
        $msgErro[] = "Erro ao criar a tabela $tableName. ";
        $msgErro[] = "Mysql erro: " . $mysql->getMsgErro();
    }
    return $msgErro;
}
function erroView($mysql, $viewName, $pProcesso) {
    $msgErro = null;
    if (!$pProcesso) {
        $msgErro[] = "Erro ao criar a view $viewName. ";
        $msgErro[] = "Mysql erro: " . $mysql->getMsgErro();
    }
    return $msgErro;
}
function addField($mysql, $tableName, $fields, $where, $pProcesso) {
    $retorno = $pProcesso;
    if ($retorno) {
        $count = $mysql->getValue('count(id) as total', 'total', $tableName, $where);
        if ($count == 0) {
            $retorno = $mysql->save(0, $tableName, $fields);
        }
    }
    return $retorno;
}
function addModulos($mysql, $processo, $codigo, $descricao, $ordem) {
    $fields = null;
    $fields["codigo"] = $codigo;
    $fields["descricao"] = "'".$descricao."'";
    $fields["ordem"] = $ordem;
    $where = "codigo = '".$codigo."'";
    return addField($mysql, 'modulos', $fields, $where, $processo);
}
function addTela($mysql, $processo, $codigomodulo, $codigo, $descricao, $endereco, $default, $acesso, $icone = null) {
    $ordem = $mysql->getValue('coalesce(max(a.ordem) + 1, 1) as total','total','telas a, modulos b',"a.idmodulo = b.id and b.codigo = '".$codigomodulo."'");
    $fields = null;
    $fields["codigo"] = $codigo;
    $fields["idmodulo"] = $mysql->getValue('id','id','modulos',"codigo = '".$codigomodulo."'");
    $fields["descricao"] = "'".$descricao."'";
    $fields["endereco"] = "'".$endereco."'";
    $fields["ordem"] = $ordem;
    $fields["padrao"] = $default;
    if (isset ($icone)) {
        $fields["icone"] = "'".$icone."'";
    }
    $where = "codigo = '".$codigo."'";
    addField($mysql, 'telas', $fields, $where, $processo);

    if (isset ($acesso)) {
        $idtela = $mysql->getValue('id','id','telas',"codigo = '".$codigo."'");
        foreach ($acesso as $row) {
            $fields = null;
            $fields["idtela"] = $idtela;
            $fields["codigo"] = $row[0];
            $fields["descricao"] = "'".$row[1]."'";
            $where = "idtela = '".$idtela."' and codigo = '".$row[0]."'";
            addField($mysql, 'funcionalidades', $fields, $where, $processo);
        }
    }
    
    return true;
}
function addSistema($mysql, $processo, $campo, $valor) {
    $fields = null;
    $fields["campo"] = "'".$campo."'";
    $fields["valor"] = "'".$valor."'";
    $where = "campo = '".$campo."'";
    return addField($mysql, 'sistema', $fields, $where, $processo);
}
function addRelatorioFixo($mysql, $processo, $codigo, $texto) {
    $fields = null;
    $fields["codigo"] = $codigo;
    $fields["texto"] = "'".$texto."'";
    $where = "codigo = '".$codigo."'";
    return addField($mysql, 'relatorios', $fields, $where, $processo);
}
function fieldSystemId($mysql, $campo) {
    $id = $mysql->getValue('id', 'id', 'sistema', "campo = '$campo'");
    if (isset ($id) and strlen($id) > 0) {
        return $id;
    } else {
        return 0;
    }
}
function newSystemVersion($mysql, $pNovaVersao) {
    $pIdSistema = fieldSystemId($mysql, 'versao');
    $pFields = null;
    $pFields['valor'] = $pNovaVersao;
    $mysql->save($pIdSistema, 'sistema', $pFields, "campo = 'versao'");
}

$processo = $mysql->createDatabase();

if (!$processo) {
    $msgErro[] = "Erro ao criar o DATABASE";
    $msgErro[] = "Mysql erro: " . $mysql->getMsgErro();
}

/*
 * ===================== Criação de TABLEs =============================
 */

$fields = null;
$tableName = 'modulos';
$fields[] = array ('codigo', 'int(10)', true);
$fields[] = array ('descricao', 'varchar(30)', true);
$fields[] = array ('ordem', 'int(10)', true);
$processo = addTabela($mysql, $tableName, $fields, null, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);
$processo = addModulos($mysql, $processo, '1', 'Controles', '1');
$processo = addModulos($mysql, $processo, '2', 'Cadastros', '2');
$processo = addModulos($mysql, $processo, '3', 'Financeiro', '3');
$processo = addModulos($mysql, $processo, '4', 'Emissões', '4');
$processo = addModulos($mysql, $processo, '5', 'Relatórios', '5');
$processo = addModulos($mysql, $processo, '6', 'Configurações', '6');

$fields = null;
$tableName = 'telas';
$fields[] = array ('idmodulo', 'int(10)', true);
$fields[] = array ('codigo', 'int(10)', true);
$fields[] = array ('descricao', 'varchar(30)', true);
$fields[] = array ('endereco', 'varchar(60)', true);
$fields[] = array ('ordem', 'int(10)', true);
$fields[] = array ('padrao', 'int(10)', true);
$fields[] = array ('icone', 'varchar(100)', false);
$other = ", FOREIGN KEY (idmodulo) REFERENCES modulos(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'funcionalidades';
$fields[] = array ('codigo', 'int(10)', true);
$fields[] = array ('idtela', 'int(10)', true);
$fields[] = array ('descricao', 'varchar(30)', true);
$other = ", FOREIGN KEY (idtela) REFERENCES telas(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$acesso = null;
$acesso[] = array(1, 'Abonar aula prática');
$acesso[] = array(3, 'Dar descontos no serviço');
$processo = addTela($mysql, $processo, '1', 1, 'Serviços', 'modulos/servicos/index.php',  0, $acesso, 'cracha.png');
$acesso = null;
$acesso[] = array(1, 'Adicionar aula');
$acesso[] = array(2, 'Remover aula');
$acesso[] = array(3, 'Lançar falta');
$acesso[] = array(4, 'Validar aula');
$processo = addTela($mysql, $processo, '1', 2, 'Aulas Práticas', 'modulos/aulaspraticas/index.php', 0, $acesso, 'sinal.png');
$acesso = null;
$acesso[] = array(1, 'Adicionar aluno');
$acesso[] = array(2, 'Remover aluno');
$processo = addTela($mysql, $processo, '1', 3, 'Aulas Teóricas', 'modulos/aulasteoricas/index.php', 0, $acesso, 'cadeira.png');
$acesso = null;
$acesso[] = array(1, 'Adicionar Data do Exame');
$acesso[] = array(2, 'Remover Data do Exame');
$acesso[] = array(3, 'Adicionar aluno');
$acesso[] = array(4, 'Remover aluno');
$processo = addTela($mysql, $processo, '1', 4, 'Exame Prático', 'modulos/examepratico/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Adicionar');
$acesso[] = array(2, 'Alterar');
$acesso[] = array(3, 'Excluir');
$processo = addTela($mysql, $processo, '1', 5, 'Turmas', 'modulos/turmas/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Adicionar');
$acesso[] = array(2, 'Alterar');
$acesso[] = array(3, 'Excluir');
$processo = addTela($mysql, $processo, '1', 6, 'Avisos', 'modulos/avisos/index.php', 0, $acesso, 'correio.png');
$acesso = null;
$acesso[] = array(1, 'Adicionar');
$acesso[] = array(2, 'Alterar');
$acesso[] = array(3, 'Excluir');
$processo = addTela($mysql, $processo, '1', 7, 'Agendamentos', 'modulos/agendamentos/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Adicionar');
$acesso[] = array(2, 'Alterar');
$acesso[] = array(3, 'Excluir');
$processo = addTela($mysql, $processo, '2', 8, 'Funções', 'modulos/funcoes/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Adicionar');
$acesso[] = array(2, 'Alterar');
$acesso[] = array(3, 'Excluir');
$processo = addTela($mysql, $processo, '2', 9, 'Funcionários', 'modulos/funcionarios/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Adicionar');
$acesso[] = array(2, 'Alterar');
$acesso[] = array(3, 'Excluir');
$processo = addTela($mysql, $processo, '2', 10, 'Alunos', 'modulos/alunos/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Adicionar');
$acesso[] = array(2, 'Alterar');
$acesso[] = array(3, 'Excluir');
$processo = addTela($mysql, $processo, '2', 11, 'Carros', 'modulos/carros/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Adicionar Funcionário no carro');
$acesso[] = array(2, 'Excluir Funcionário no carro');
$processo = addTela($mysql, $processo, '2', 12, 'Carros x Func.', 'modulos/carrosfun/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Adicionar');
$acesso[] = array(2, 'Alterar');
$acesso[] = array(3, 'Excluir');
$processo = addTela($mysql, $processo, '2', 13, 'Salas', 'modulos/salas/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Adicionar');
$acesso[] = array(2, 'Alterar');
$acesso[] = array(3, 'Excluir');
$processo = addTela($mysql, $processo, '2', 14, 'Turnos', 'modulos/turnos/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Adicionar Horário');
$acesso[] = array(2, 'Excluir Horário');
$processo = addTela($mysql, $processo, '2', 15, 'Expediente', 'modulos/expediente/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Adicionar');
$acesso[] = array(2, 'Alterar');
$acesso[] = array(3, 'Excluir');
$processo = addTela($mysql, $processo, '2', 16, 'Tipos de Agendamento', 'modulos/tipoagendamento/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Adicionar');
$acesso[] = array(2, 'Alterar');
$acesso[] = array(3, 'Excluir');
$processo = addTela($mysql, $processo, '2', 17, 'Tipos de Serviço', 'modulos/tiposervicos/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Adicionar');
$acesso[] = array(2, 'Alterar');
$acesso[] = array(3, 'Excluir');
$processo = addTela($mysql, $processo, '2', 18, 'Tipos de Carros', 'modulos/tipocarros/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Criar Conta');
$acesso[] = array(2, 'Excluir Conta');
$acesso[] = array(3, 'Receber Pagamento');
$acesso[] = array(4, 'Excluir Pagamento');
$acesso[] = array(5, 'Dar Descontos');
$processo = addTela($mysql, $processo, '3', 19, 'Contas a Receber', 'modulos/contasareceber/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '3', 20, 'Comissão', 'modulos/comissao/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '3', 21, 'Caixa', 'modulos/caixa/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Ajustar Caixa');
$processo = addTela($mysql, $processo, '3', 22, 'Controle de Caixas', 'modulos/ctrcaixa/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Adicionar');
$acesso[] = array(2, 'Alterar');
$acesso[] = array(3, 'Excluir');
$processo = addTela($mysql, $processo, '6', 23, 'Usuários', 'modulos/usuarios/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '6', 24, 'Trocar Senha', 'modulos/usuarios/senha.php', 1, $acesso);
$acesso = null;
$acesso[] = array(1, 'Adicionar');
$acesso[] = array(2, 'Alterar');
$acesso[] = array(3, 'Excluir');
$processo = addTela($mysql, $processo, '6', 25, 'Grupos de Usuário', 'modulos/gruposusuario/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '6', 26, 'Controle de Acessos', 'modulos/usuarios/acesso.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '6', 27, 'Ícones', 'modulos/icones/form.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '6', 28, 'Sistema', 'modulos/sistema/form.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '6', 29, 'Sair (Logout)', 'modulos/usuarios/logout.php', 1, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '2', 30, 'Origens', 'modulos/origens/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '4', 31, 'Aulas Práticas', 'relatorios/aulaspraticas/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Editar Conteúdo Automático');
$processo = addTela($mysql, $processo, '4', 32, 'Declaração', 'relatorios/declaracao/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '5', 33, 'Agendamentos', 'relatorios/agendamentos/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '5', 34, 'Exame Prático', 'relatorios/examepratico/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '3', 35, 'Relatórios', 'relatorios/relcaixa/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '4', 36, 'Aulas Alunos', 'relatorios/aulasalunos/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '6', 37, 'Menu', 'modulos/menu/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '5', 38, 'Validade Processo', 'relatorios/validprocesso/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '5', 39, 'Ficha Aluno', 'relatorios/fichaaluno/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '5', 40, 'Exame Prático Alunos', 'relatorios/examepraticoalunos/index.php', 0, $acesso);
$acesso = null;
$acesso[] = array(1, 'Editar Conteúdo Automático');
$processo = addTela($mysql, $processo, '3', 41, 'Recibos', 'relatorios/recibopagto/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '6', 42, 'Backup', 'modulos/backup/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '3', 43, 'Declaração Pagto', 'modulos/declarapagamento/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '4', 44, 'Aulas Teóricas', 'relatorios/aulasteoricas/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '2', 45, 'Vales', 'modulos/vales/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '5', 46, 'Vales', 'relatorios/vales/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '6', 47, 'Sobre', 'modulos/sobre/index.php', 1, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '5', 48, 'Caixa Por Usuário', 'relatorios/caixaporusuario/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '2', 49, 'Bônus', 'modulos/bonus/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '5', 50, 'Tipo de Serviços', 'relatorios/tiposervicos/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '5', 51, 'Ranking Exame Prático', 'relatorios/rankingexamepratico/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '5', 52, 'Aula Prática Duplicada', 'relatorios/aulapraticaduplicada/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '2', 53, 'Horário Exame Prático', 'modulos/examepraticohorario/index.php', 0, $acesso);
$acesso = null;
$processo = addTela($mysql, $processo, '1', 54, 'Observação Aluno', 'modulos/obsaluno/index.php', 0, $acesso);

$fields = null;
$tableName = 'sistema';
$fields[] = array ('campo', 'varchar(30)', true);
$fields[] = array ('valor', 'varchar(255)', true);
$processo = addTabela($mysql, $tableName, $fields, null, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);
$processo = addSistema($mysql, $processo, 'tema', 'cupertino');
$processo = addSistema($mysql, $processo, 'janela', '2');
$processo = addSistema($mysql, $processo, 'versao', '1');

$fields = null;
$tableName = 'pessoas';
$fields[] = array ('nome', 'varchar(100)', true);
$fields[] = array ('dtnascimento', 'date', false);
$fields[] = array ('sexo', 'char(1)', false);
$fields[] = array ('rg', 'varchar(20)', false);
$fields[] = array ('orgaoemissor', 'varchar(30)', false);
$fields[] = array ('rgdataemissao', 'date', false);
$fields[] = array ('cpf', 'varchar(20)', false);
$fields[] = array ('carteiradetrabalho', 'varchar(50)', false);
$fields[] = array ('endereco', 'varchar(100)', false);
$fields[] = array ('cep', 'varchar(100)', false);
$fields[] = array ('bairro', 'varchar(100)', false);
$fields[] = array ('cidade', 'varchar(100)', false);
$fields[] = array ('estado', 'varchar(100)', false);
$fields[] = array ('telefone', 'varchar(100)', false);
$fields[] = array ('celular', 'varchar(100)', false);
$fields[] = array ('email', 'varchar(100)', false);
$fields[] = array ('pai', 'varchar(100)', false);
$fields[] = array ('mae', 'varchar(100)', false);
$processo = addTabela($mysql, $tableName, $fields, null, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'funcoes';
$fields[] = array ('descricao', 'varchar(100)', true);
$processo = addTabela($mysql, $tableName, $fields, null, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'origens';
$fields[] = array ('descricao', 'varchar(100)', true);
$processo = addTabela($mysql, $tableName, $fields, null, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'tipocarros';
$fields[] = array ('descricao', 'varchar(100)', true);
$fields[] = array ('comissao', 'decimal(5,2)', true);
$processo = addTabela($mysql, $tableName, $fields, null, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'carros';
$fields[] = array ('idtipocarro', 'int(10)', true);
$fields[] = array ('descricao', 'varchar(100)', true);
$fields[] = array ('placa', 'char(7)', true);
$fields[] = array ('ano', 'int', false);
$fields[] = array ('anomodelo', 'int', false);
$fields[] = array ('datacompra', 'date', false);
$fields[] = array ('datavenda', 'date', false);
$other = ", FOREIGN KEY (idtipocarro) REFERENCES tipocarros(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'funcionarios';
$fields[] = array ('idfuncao', 'int(10)', true);
$fields[] = array ('idpessoa', 'int(10)', true);
$fields[] = array ('matricula', 'varchar(10)', true);
$fields[] = array ('status', 'char(1)', true);
$other = ", FOREIGN KEY (idfuncao) REFERENCES funcoes(id)";
$other = ", FOREIGN KEY (idpessoa) REFERENCES pessoas(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'carrofuncionario';
$fields[] = array ('idcarro', 'int(10)', true);
$fields[] = array ('idfuncionario', 'int(10)', true);
$fields[] = array ('data', 'date', true);
$fields[] = array ('hora', 'time', true);
$other = ", FOREIGN KEY (idcarro) REFERENCES carros(id)";
$other = ", FOREIGN KEY (idfuncionario) REFERENCES funcionarios(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'empresas';
$fields[] = array ('razaosocial', 'varchar(100)', true);
$fields[] = array ('nomefantasia', 'varchar(100)', true);
$fields[] = array ('cnpj', 'varchar(20)', false);
$fields[] = array ('inscricaoestadual', 'varchar(20)', false);
$fields[] = array ('endereco', 'varchar(100)', false);
$fields[] = array ('cep', 'varchar(100)', false);
$fields[] = array ('bairro', 'varchar(100)', false);
$fields[] = array ('cidade', 'varchar(100)', false);
$fields[] = array ('estado', 'varchar(100)', false);
$fields[] = array ('telefone', 'varchar(100)', false);
$fields[] = array ('celular', 'varchar(100)', false);
$fields[] = array ('email', 'varchar(100)', false);
$processo = addTabela($mysql, $tableName, $fields, null, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'alunos';
$fields[] = array ('idpessoa', 'int(10)', true);
$fields[] = array ('idorigem', 'int(10)', false);
$fields[] = array ('matricula', 'int(10)', true);
$fields[] = array ('matriculacfc', 'varchar(20)', false);
$fields[] = array ('renach', 'varchar(20)', false);
$fields[] = array ('observacoes', 'text', false);
$fields[] = array ('regcnh', 'varchar(30)', false);
$fields[] = array ('categoriaatual', 'varchar(30)', false);
$fields[] = array ('validadeprocesso', 'date', false);
$fields[] = array ('codacess', 'int(10)', false);
$other = ", FOREIGN KEY (idpessoa) REFERENCES pessoas(id)";
$other = ", FOREIGN KEY (idorigem) REFERENCES origens(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'alunosdudas';
$fields[] = array ('idaluno', 'int(10)', true);
$fields[] = array ('duda', 'varchar(20)', true);
$fields[] = array ('data', 'date', true);
$other = ", FOREIGN KEY (idaluno) REFERENCES alunos(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'usuarios';
$fields[] = array ('idfuncionario', 'int(10)', false);
$fields[] = array ('idcliente', 'int(10)', false);
$fields[] = array ('login', 'varchar(20)', true);
$fields[] = array ('senha', 'varchar(100)', true);
$fields[] = array ('nome', 'varchar(60)', false);
$fields[] = array ('observacao', 'text', false);
$other = ", FOREIGN KEY (idfuncionario) REFERENCES funcionarios(id)";
// $other .= ", FOREIGN KEY (idcliente) REFERENCES clientes(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);
$fields = null;
$fields["login"] = "'admin'";
$fields["senha"] = "md5('admin')";
$fields["nome"] = "'Administrador do Sistema'";
$where = "login = 'admin'";
$processo = addField($mysql, $tableName, $fields, $where, $processo);

$fields = null;
$tableName = 'gruposusuario';
$fields[] = array ('descricao', 'varchar(60)', true);
$processo = addTabela($mysql, $tableName, $fields, null, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'usuariosgrupousuario';
$fields[] = array ('idusuario', 'int(10)', true);
$fields[] = array ('idgrupousuario', 'int(10)', true);
$other = ", FOREIGN KEY (idusuario) REFERENCES usuarios(id)";
$other .= ", FOREIGN KEY (idgrupousuario) REFERENCES gruposusuario(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'acesso';
$fields[] = array ('idgrupousuario', 'int(10)', true);
$fields[] = array ('idtela', 'int(10)', true);
$other = ", FOREIGN KEY (idtela) REFERENCES telas(id)";
$other = ", FOREIGN KEY (idgrupousuario) REFERENCES gruposusuario(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'acessofunc';
$fields[] = array ('idfuncionalidade', 'int(10)', true);
$fields[] = array ('idgrupousuario', 'int(10)', true);
$other = ", FOREIGN KEY (idfuncionalidade) REFERENCES funcionalidades(id)";
$other = ", FOREIGN KEY (idgrupousuario) REFERENCES gruposusuario(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'aulaspraticas';
$fields[] = array ('idaluno', 'int(10)', true);
$fields[] = array ('idcarro', 'int(10)', true);
$fields[] = array ('data', 'date', true);
$fields[] = array ('hora', 'time', true);
$fields[] = array ('comentario', 'text', false);
$fields[] = array ('falta', 'char(1)', false);
$fields[] = array ('abono', 'char(1)', false);
$fields[] = array ('abonomotivo', 'text', false);
$other = ", FOREIGN KEY (idaluno) REFERENCES alunos(id)";
$other .= ", FOREIGN KEY (idcarro) REFERENCES carros(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'aulaspraticasbloqueio';
$fields[] = array ('idcarro', 'int(10)', true);
$fields[] = array ('data', 'date', true);
$fields[] = array ('hora', 'time', true);
$fields[] = array ('motivo', 'text', false);
$other = ", FOREIGN KEY (idcarro) REFERENCES carros(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'salas';
$fields[] = array ('descricao', 'varchar(100)', true);
$processo = addTabela($mysql, $tableName, $fields, null, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'turmas';
$fields[] = array ('idsala', 'int(10)', true);
$fields[] = array ('idfuncionario', 'int(10)', true);
$fields[] = array ('data', 'date', true);
$fields[] = array ('hora', 'time', true);
$fields[] = array ('qtdalunos', 'int(10)', true);
$fields[] = array ('fechada', 'char(1)', true);
$other = ", FOREIGN KEY (idsala) REFERENCES salas(id)";
$other .= ", FOREIGN KEY (idfuncionario) REFERENCES funcionarios(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'aulasteoricas';
$fields[] = array ('idaluno', 'int(10)', true);
$fields[] = array ('idturma', 'int(10)', true);
$other = ", FOREIGN KEY (idaluno) REFERENCES alunos(id)";
$other .= ", FOREIGN KEY (idturma) REFERENCES turmas(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'avisos';
$fields[] = array ('iddestinatario', 'int(10)', false);
$fields[] = array ('idremetente', 'int(10)', false);
$fields[] = array ('mensagem', 'text', true);
$fields[] = array ('data', 'date', true);
$fields[] = array ('prioridade', 'char(1)', true);
$fields[] = array ('status', 'char(1)', true);
$other = ", FOREIGN KEY (iddestinatario) REFERENCES usuarios(id)";
$other .= ", FOREIGN KEY (idremetente) REFERENCES usuarios(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'turnos';
$fields[] = array ('idtipocarro', 'int(10)', true);
$fields[] = array ('descricao', 'varchar(100)', true);
$fields[] = array ('duracaoaula', 'int(10)', true);
$other = ", FOREIGN KEY (idtipocarro) REFERENCES tipocarros(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'expediente';
$fields[] = array ('idturno', 'int(10)', true);
$fields[] = array ('diasemana', 'int(10)', true);
$fields[] = array ('horai', 'time', true);
$fields[] = array ('horaf', 'time', true);
$other = ", FOREIGN KEY (idturno) REFERENCES turnos(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'tiposagendamentos';
$fields[] = array ('descricao', 'varchar(100)', true);
$processo = addTabela($mysql, $tableName, $fields, null, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'agendamentos';
$fields[] = array ('idaluno', 'int(10)', true);
$fields[] = array ('idtipoagendamento', 'int(10)', true);
$fields[] = array ('data', 'date', true);
$fields[] = array ('hora', 'time', false);
$fields[] = array ('aprovado', 'char(1)', false);
$other = ", FOREIGN KEY (idaluno) REFERENCES alunos(id)";
$other = ", FOREIGN KEY (idtipoagendamento) REFERENCES tiposagendamentos(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'examepratico';
$fields[] = array ('data', 'date', true);
$fields[] = array ('categoria', 'char(1)', true);
$fields[] = array ('status', 'char(1)', true);
$processo = addTabela($mysql, $tableName, $fields, null, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'examepraticocarro';
$fields[] = array ('idexamepratico', 'int(10)', true);
$fields[] = array ('idcarro', 'int(10)', true);
$other = ", FOREIGN KEY (idexamepratico) REFERENCES examepratico(id)";
$other = ", FOREIGN KEY (idcarro) REFERENCES carros(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'examepraticoalunos';
$fields[] = array ('idexamepraticocarro', 'int(10)', true);
$fields[] = array ('idaluno', 'int(10)', true);
$fields[] = array ('resultado', 'char(1)', true);
$other = ", FOREIGN KEY (idexamepraticocarro) REFERENCES examepraticocarro(id)";
$other .= ", FOREIGN KEY (idaluno) REFERENCES alunos(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'tiposervicos';
$fields[] = array ('descricao', 'varchar(100)', true);
$fields[] = array ('qtaulaspraticas', 'int(10)', true);
$fields[] = array ('qtaulasteoricas', 'int(10)', true);
$fields[] = array ('valor', 'decimal(10,2)', true);
$fields[] = array ('status', 'char(1)', true);
$fields[] = array ('diasavencer', 'int(5)', false);
$processo = addTabela($mysql, $tableName, $fields, null, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'alunoservico';
$fields[] = array ('idtiposervico', 'int(10)', true);
$fields[] = array ('idaluno', 'int(10)', true);
$fields[] = array ('data', 'date', true);
$fields[] = array ('qtaulaspraticas', 'int(10)', true);
$fields[] = array ('qtaulasteoricas', 'int(10)', true);
$fields[] = array ('valor', 'decimal(10,2)', true);
$fields[] = array ('desconto', 'decimal(10,2)', true);
$fields[] = array ('vencimento', 'date', false);
$other = ", FOREIGN KEY (idtiposervico) REFERENCES tiposervicos(id)";
$other .= ", FOREIGN KEY (idaluno) REFERENCES alunos(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'contasareceber';
$fields[] = array ('idalunoservico', 'int(10)', true);
$fields[] = array ('idusuario', 'int(10)', false);
$fields[] = array ('valor', 'decimal(10,2)', true);
$fields[] = array ('data', 'date', true);
$other = ", FOREIGN KEY (idalunoservico) REFERENCES alunoservico(id)";
$other .= ", FOREIGN KEY (idusuario) REFERENCES usuarios(id)";
//$other .= ", INDEX cr_usuario (idusuario) ";
//$other .= ", INDEX cr_alunservico (idalunoservico) ";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'alunoservicoparcelas';
$fields[] = array ('idalunoservico', 'int(10)', true);
$fields[] = array ('data', 'date', true);
$fields[] = array ('valor', 'decimal(10,2)', true);
$other = ", FOREIGN KEY (idalunoservico) REFERENCES alunoservico(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'caixa';
$fields[] = array ('idusuario', 'int(10)', true);
$fields[] = array ('data', 'date', true);
$fields[] = array ('hora', 'time', true);
$fields[] = array ('valor', 'decimal(10,2)', true);
$fields[] = array ('ajuste', 'decimal(10,2)', true);
$other = ", FOREIGN KEY (idusuario) REFERENCES usuarios(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'declaracoes';
$fields[] = array ('descricao', 'varchar(100)', true);
$fields[] = array ('status', 'char(1)', true);
$processo = addTabela($mysql, $tableName, $fields, null, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'declaracoesitens';
$fields[] = array ('iddeclaracao', 'int(10)', true);
$fields[] = array ('texto', 'text', true);
$other = ", FOREIGN KEY (iddeclaracao) REFERENCES declaracoes(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'declaracaopagto';
$fields[] = array ('texto', 'text', true);
$processo = addTabela($mysql, $tableName, $fields, null, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'relatorios';
$fields[] = array ('codigo', 'int(10)', true);
$fields[] = array ('texto', 'text', true);
$processo = addTabela($mysql, $tableName, $fields, null, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$txtRelatorio = "";
$txtRelatorio .= "Recebemos de {nomeAluno}, ";
$txtRelatorio .= "a importância supra de {valorRecebido}, ";
$txtRelatorio .= "no dia {dataRecebimento}.";
addRelatorioFixo($mysql, $processo, 1, $txtRelatorio);

$fields = null;
$tableName = 'relalunos';
$fields[] = array ('tipo', 'int(10)', true);
$fields[] = array ('texto', 'text', true);
$processo = addTabela($mysql, $tableName, $fields, null, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'vales';
$fields[] = array ('idfuncionario', 'int(10)', true);
$fields[] = array ('data', 'date', true);
$fields[] = array ('valor', 'decimal(10,2)', true);
$fields[] = array ('motivo', 'text', true);
$other = ", FOREIGN KEY (idfuncionario) REFERENCES funcionarios(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'bonus';
$fields[] = array ('idfuncionario', 'int(10)', true);
$fields[] = array ('data', 'date', true);
$fields[] = array ('valor', 'decimal(10,2)', true);
$fields[] = array ('motivo', 'text', true);
$other = ", FOREIGN KEY (idfuncionario) REFERENCES funcionarios(id)";
$processo = addTabela($mysql, $tableName, $fields, $other, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

$fields = null;
$tableName = 'examepraticohorario';
$fields[] = array ('hora', 'time', true);
$processo = addTabela($mysql, $tableName, $fields, null, $processo);
$msgErro = erroTabela($mysql, $tableName, $processo);

/*
 * ===================== Criação de VIEWs =============================
 */
if ($processo) {
    $sqlView = '';
    $viewName = 'vfuncionarios';
    $sqlView .= 'select f.id, f.matricula, f.idfuncao, fu.descricao as funcao, ';
    $sqlView .= 'f.status, p.nome, p.cpf, p.celular ';
    $sqlView .= 'from funcionarios f ';
    $sqlView .= 'inner join funcoes fu on f.idfuncao = fu.id ';
    $sqlView .= 'inner join pessoas p on f.idpessoa = p.id ';
    $processo = $mysql->createView($viewName, $sqlView);
    erroView($mysql, $viewName, $processo);

    $sqlView = '';
    $viewName = 'vusuarios';
    $sqlView .= 'select u.id, u.login, vf.id as idfuncionario, coalesce(vf.nome, u.nome) as nome, u.observacao ';
    $sqlView .= 'from usuarios u ';
    $sqlView .= 'left join vfuncionarios vf on u.idfuncionario = vf.id ';
    $processo = $mysql->createView($viewName, $sqlView);
    erroView($mysql, $viewName, $processo);

    $sqlView = '';
    $viewName = 'vavisos';
    $sqlView .= 'select a.*, vr.nome as remetente, vd.nome as destinatario ';
    $sqlView .= 'from avisos a ';
    $sqlView .= 'inner join vusuarios vd on a.iddestinatario = vd.id ';
    $sqlView .= 'inner join vusuarios vr on a.idremetente = vr.id ';
    $processo = $mysql->createView($viewName, $sqlView);
    erroView($mysql, $viewName, $processo);;

    $sqlView = '';
    $viewName = 'vexpedientes';
    $sqlView .= 'select e.*, t.descricao, t.duracaoaula ';
    $sqlView .= 'from expediente e, turnos t ';
    $sqlView .= 'where e.idturno = t.id ';
    $processo = $mysql->createView($viewName, $sqlView);
    erroView($mysql, $viewName, $processo);

    $sqlView = '';
    $viewName = 'vacessos';
    $sqlView .= 'select a.padrao, b.ordem as ordemmodulos, a.ordem as ordemtelas, a.id, b.descricao as modulo, a.descricao as tela, a.endereco, a.icone, d.idusuario ';
    $sqlView .= 'from telas a ';
    $sqlView .= 'inner join modulos b on a.idmodulo = b.id ';
    $sqlView .= 'left join acesso c on c.idtela = a.id or a.padrao = 1 ';
    $sqlView .= 'left join usuariosgrupousuario d on c.idgrupousuario = d.idgrupousuario ';
    $processo = $mysql->createView($viewName, $sqlView);
    erroView($mysql, $viewName, $processo);

    $sqlView = '';
    $viewName = 'vturmas';
    $sqlView .= 'select t.id, t.data, t.hora, t.qtdalunos, t.fechada, ';
    $sqlView .= 't.idfuncionario, f.nome as funcionario, t.idsala, s.descricao as sala ';
    $sqlView .= 'from turmas t, vfuncionarios f, salas s ';
    $sqlView .= 'where t.idfuncionario = f.id ';
    $sqlView .= 'and t.idsala = s.id ';
    $processo = $mysql->createView($viewName, $sqlView);
    erroView($mysql, $viewName, $processo);

    $sqlView = '';
    $viewName = 'vagendamentos';
    $sqlView .= 'select a.*, ';
    $sqlView .= "case a.aprovado when 'A' then 'Aprovado' ";
    $sqlView .= "when 'F' then 'Falta' ";
    $sqlView .= "when 'C' then 'Cencelado Aluno' ";
    $sqlView .= "when 'T' then 'Retirado' ";
    $sqlView .= "when 'M' then 'Não Marcado' ";
    $sqlView .= "when 'R' then 'Reprovado' else null end as aprovadotxt, ";
    $sqlView .= 'c.nome as aluno, d.descricao as tipoagendamento ';
    $sqlView .= 'from agendamentos a, alunos b, pessoas c, tiposagendamentos d ';
    $sqlView .= 'where a.idaluno = b.id ';
    $sqlView .= 'and b.idpessoa = c.id ';
    $sqlView .= 'and a.idtipoagendamento = d.id ';
    $processo = $mysql->createView($viewName, $sqlView);
    erroView($mysql, $viewName, $processo);

    $sqlView = '';
    $viewName = 'valunos';
    $sqlView .= 'select a.id, a.matriculacfc, b.nome, b.cpf, a.renach, a.matricula ';
    $sqlView .= 'from alunos a, pessoas b ';
    $sqlView .= 'where a.idpessoa = b.id ';
    $processo = $mysql->createView($viewName, $sqlView);
    erroView($mysql, $viewName, $processo);

    $sqlView = '';
    $viewName = 'vaulasteoricas';
    $sqlView .= 'select a.*, b.data, b.hora, b.qtdalunos, b.fechada, ';
    $sqlView .= 'b.idfuncionario, b.funcionario, b.sala, c.nome as aluno, ';
    $sqlView .= 'c.matriculacfc, c.renach ';
    $sqlView .= 'from aulasteoricas a, vturmas b, valunos c ';
    $sqlView .= 'where a.idturma = b.id and a.idaluno = c.id ';
    $processo = $mysql->createView($viewName, $sqlView);
    erroView($mysql, $viewName, $processo);

    $sqlView = '';
    $viewName = 'vexamepratico';
    $sqlView .= "select a.id, a.data, a.categoria, b.idcarro, b.id as idexamepraticocarro, ";
    $sqlView .= "c.carro ";
    $sqlView .= 'from examepratico a ';
    $sqlView .= 'inner join examepraticocarro b on b.idexamepratico = a.id ';
    $sqlView .= 'inner join vcarros c on b.idcarro = c.id ';
    $processo = $mysql->createView($viewName, $sqlView);
    erroView($mysql, $viewName, $processo);

    $sqlView = '';
    $viewName = 'vexamepraticoaluno';
    $sqlView .= "select a.id, a.idaluno, b.data, a.horario, b.carro, a.resultado, ";
    $sqlView .= "a.idexamepraticocarro ";
    $sqlView .= "from examepraticoalunos a ";
    $sqlView .= "inner join vexamepratico b on a.idexamepraticocarro = b.idexamepraticocarro ";
    $processo = $mysql->createView($viewName, $sqlView);
    erroView($mysql, $viewName, $processo);

    $sqlView = '';
    $viewName = 'valunoservico';
    $sqlView .= "select a.id, a.idtiposervico, a.idaluno, a.data, a.qtaulaspraticas, a.qtaulasteoricas, a.valor, a.desconto, ";
    $sqlView .= "t.descricao as tiposervico, v.nome as aluno, coalesce(sum(c.valor), 0) as valorpago, a.vencimento, ";
    $sqlView .= "((a.valor - a.desconto) - coalesce(sum(c.valor), 0)) as valorapagar, max(c.data) dtultimopagto, ";
    $sqlView .= "case when coalesce(sum(c.valor), 0) >= (a.valor - a.desconto) then 'Pago' else 'Aberto' end as Status, ";
    $sqlView .= "v.matricula, v.matriculacfc ";
    $sqlView .= "from alunoservico a ";
    $sqlView .= "inner join tiposervicos t on a.idtiposervico = t.id ";
    $sqlView .= "inner join valunos v on a.idaluno = v.id ";
    $sqlView .= "left join contasareceber c on c.idalunoservico = a.id ";
    $sqlView .= "group by a.id, a.idtiposervico, a.idaluno, a.data, a.qtaulaspraticas, a.qtaulasteoricas, a.valor, a.desconto, ";
    $sqlView .= "t.descricao, v.nome ";
    $processo = $mysql->createView($viewName, $sqlView);
    erroView($mysql, $viewName, $processo);

    $sqlView = '';
    $viewName = 'vaulaspraticas';
    $sqlView .= "SELECT a.*, concat(b.descricao, ' - ', b.placa) as carro, c.nome as aluno ";
    $sqlView .= "FROM aulaspraticas a, carros b, valunos c ";
    $sqlView .= "where a.idcarro = b.id and a.idaluno = c.id ";
    $processo = $mysql->createView($viewName, $sqlView);
    erroView($mysql, $viewName, $processo);

    $sqlView = '';
    $viewName = 'vcarros';
    $sqlView .= "select id, descricao, placa, concat(descricao, ' - ', placa) as carro, datavenda ";
    $sqlView .= "from carros ";
    $processo = $mysql->createView($viewName, $sqlView);
    erroView($mysql, $viewName, $processo);
    
}

/*
 * ALTER TABLE `autoescola`.`tiposervicos` ADD COLUMN `diasavencer` int(5) UNSIGNED AFTER `status`;
 *
 * ALTER TABLE `autoescola`.`alunoservico` ADD COLUMN `vencimento` DATE AFTER `desconto`;
 *
 */

/*
 * ===================== Alterações de Versões ========================
 */
if ($versaoSistema == '0') {
    $mysql->alterColumnType('tiposervicos', 'valor', 'DECIMAL(10,2)', true);

    $mysql->alterColumnType('alunoservico', 'valor', 'DECIMAL(10,2)', true);
    $mysql->alterColumnType('alunoservico', 'desconto', 'DECIMAL(10,2)', true);

    $mysql->alterColumnType('contasareceber', 'valor', 'DECIMAL(10,2)', true);

    $pIdSistema = fieldSystemId($mysql, 'versao');
    $pFields = null;
    $pFields['valor'] = '1';
    $mysql->save($pIdSistema, 'sistema', $pFields, "campo = 'versao'");
} else if ($versaoSistema == '1') {
    $pIdSistema = fieldSystemId($mysql, 'versao');
    $pFields = null;
    $pFields['valor'] = '2';
    $mysql->save($pIdSistema, 'sistema', $pFields, "campo = 'versao'");
} else if ($versaoSistema == '2') {

    $mysql->addColumnToTable('pessoas', 'telcontato', 'VARCHAR(100)', false);
    $mysql->addColumnToTable('pessoas', 'telefone2', 'VARCHAR(100)', false);
    $mysql->addColumnToTable('pessoas', 'tel2contato', 'VARCHAR(100)', false);

    $pIdSistema = fieldSystemId($mysql, 'versao');
    $pFields = null;
    $pFields['valor'] = '3';
    $mysql->save($pIdSistema, 'sistema', $pFields, "campo = 'versao'");
} else if ($versaoSistema == '3') {

    $idTela = $mysql->getValue('id', null, 'telas', "endereco = 'relatorios/aulaspraticas/index.php'");
    $pFields["descricao"] = "'Planilha do Instrutor'";
    $mysql->save($idTela, 'telas', $pFields, "id = '".$idTela."'");

    $pIdSistema = fieldSystemId($mysql, 'versao');
    $pFields = null;
    $pFields['valor'] = '4';
    $mysql->save($pIdSistema, 'sistema', $pFields, "campo = 'versao'");
} else if ($versaoSistema == '4') {
    $pIdSistema = fieldSystemId($mysql, 'versao');
    $pFields = null;
    $pFields['valor'] = '5';
    $mysql->save($pIdSistema, 'sistema', $pFields, "campo = 'versao'");
} else if ($versaoSistema == '5') {
    $pFields = null;
    $pFields["idtela"] = $mysql->getValue('id', null, 'telas', "codigo = '46'");
    $pFields["codigo"] = 1;
    $pFields["descricao"] = "'Visualizar todos funcionários'";
    $mysql->save(0, 'funcionalidades', $pFields, null);

    $pIdSistema = fieldSystemId($mysql, 'versao');
    $pFields = null;
    $pFields['valor'] = '6';
    $mysql->save($pIdSistema, 'sistema', $pFields, "campo = 'versao'");
}  else if ($versaoSistema == '6') {

    $mysql->alterColumnType('funcionalidades', 'descricao', 'VARCHAR(60)', true);

    $pFields = null;
    $pFields["idtela"] = $mysql->getValue('id', null, 'telas', "codigo = '1'");
    $pFields["codigo"] = 2;
    $pFields["descricao"] = "'Remover aulas anterioes da aula prática'";
    $mysql->save(0, 'funcionalidades', $pFields, null);

    $pIdSistema = fieldSystemId($mysql, 'versao');
    $pFields = null;
    $pFields['valor'] = '7';
    $mysql->save($pIdSistema, 'sistema', $pFields, "campo = 'versao'");
} else if ($versaoSistema == '7') {
    $pIdSistema = fieldSystemId($mysql, 'versao');
    $pFields = null;
    $pFields['valor'] = '8';
    $mysql->save($pIdSistema, 'sistema', $pFields, "campo = 'versao'");
} else if ($versaoSistema == '8') {    
    $mysql->addColumnToTable('carros', 'idfunfixo', 'int(10)', false);    
    newSystemVersion($mysql, '9');
} else if ($versaoSistema == '9') {
    
    $pFields = null;
    $pFields["descricao"] = "'CONTRATO DE PRESTACAO DE SERVICO'";
    $pFields["status"] = "'A'";
    $pIdDeclaracao = $mysql->save(0, 'declaracoes', $pFields);
    
    $pFields = null;
    $pFields["iddeclaracao"] = $pIdDeclaracao;
    $pFields["texto"] = '\'<p style=\"text-align: center;\">\r\n	<strong>CONTRATO DE PRESTA&Ccedil;&Atilde;O DE SERVI&Ccedil;O</strong><br />\r\n	ORIENTA&Ccedil;&Otilde;ES IMPORTANTES - FAVOR&nbsp; LER !!!</p>\r\n<p>\r\n	<br />\r\n	Contratado&nbsp; : AUTO ESCOLA 4 RODAS LTDA CNPJ.:02166011/0001-71<br />\r\n	End. &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Via Sergio Braga n&ordm; 476 ap. 101 Bairro: Ponte Alta.<br />\r\n	Cidade&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; : Volta Redonda / RJ &ndash; Tel.: (24) 33428752 / 33486575<br />\r\n	<br />\r\n	Contratante: {nomeAluno}&nbsp;&nbsp;&nbsp; CPF: {cpfAluno}<br />\r\n	<br />\r\n	Na qualidade de aluno (contratante), declaro conhecer e aceitar as cl&aacute;usulas abaixo expressas neste contrato. O contratante se compromete a ministrar o ensino correspondente ao curso de forma&ccedil;&atilde;o de condutores nos termos da legisla&ccedil;&atilde;o vigente e em conformidade com o seu regimento interno,aprovados pelos &oacute;rg&atilde;os competentes e &agrave; disposi&ccedil;&atilde;o&nbsp;&nbsp;&nbsp; do contratante.&nbsp;&nbsp;&nbsp;<br />\r\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; VALOR A SER PAGO R$ ____________________obs: a forma de pagamento n&atilde;o interfere no prazo para tirar a carteira.<br />\r\n	OBS: Cabe ao contratante o pagamento da taxa referente ao Estado (DUDA) direto no Banco&nbsp; e a taxa referente &agrave; realiza&ccedil;&atilde;o dos exames m&eacute;dico e psicot&eacute;cnico, paga em uma das cl&iacute;nicas indicadas pelo Detran ( observar o endere&ccedil;o correto da clinica ). No dia do cadastro no detran levar: identidade, CPF e comprovante de resid&ecirc;ncia original e c&oacute;pia.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n	Qualquer pagamento s&oacute; dever&aacute; ser realizado em nossa secretaria, mediante recibo, (nunca aos instrutores em &aacute;rea de exame), n&atilde;o nos responsabilizamos por qualquer outra forma de pagamento.<br />\r\n	Para habilitar-se na categoria &ldquo;A&rdquo; motocicleta, &eacute; necess&aacute;rio que o aluno saiba andar de bicicleta.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n	A marca&ccedil;&atilde;o de aulas pr&aacute;ticas e exames ser&atilde;o feitos impreterivelmente de 08h30min &agrave;s 17h30min.<br />\r\n	No valor da matr&iacute;cula, est&atilde;o inclusos: as aulas o material did&aacute;tico e a marca&ccedil;&atilde;o dos exames.<br />\r\n	A matr&iacute;cula &eacute; intransfer&iacute;vel. o aluno poder&aacute; fazer as categorias de carro e moto simultaneamente, tendo que decidir at&eacute; o&nbsp;&nbsp;&nbsp;&nbsp; dia do cadastro no detran. As aulas te&oacute;ricas, pr&aacute;ticas e o aluguel do ve&iacute;culo para o dia do exame est&atilde;o inclusos no valor da matr&iacute;cula. A marca&ccedil;&atilde;o do curso te&oacute;rico &eacute; feita de acordo com as vagas dispon&iacute;veis na escola. Tendo o aluno que chegar 20 minutos antes do hor&aacute;rio marcado do curso. ( caso chegue atrasado&nbsp; perder&aacute; a aula ) As aulas de dire&ccedil;&atilde;o ter&atilde;o seu in&iacute;cio (sem toler&acirc;ncia de atraso) e t&eacute;rmino sempre a contar das depend&ecirc;ncias da &Aacute;rea de Exames. Em caso de falta a remarca&ccedil;&atilde;o das aulas pr&aacute;ticas ser&atilde;o feitas mediante novo pagamento (no valor da aula avulso). Por motivo de for&ccedil;a maior, (doen&ccedil;a, falta...) o instrutor poder&aacute; ser substitu&iacute;do por outro. A autoescola n&atilde;o se responsabiliza por qualquer objeto desaparecido na sala de aula.<br />\r\n	A dura&ccedil;&atilde;o do curso te&oacute;rico ser&aacute; de acordo com a carga hor&aacute;ria de cada turma. Reposi&ccedil;&atilde;o de aulas te&oacute;ricas depender&aacute; de vagas.<br />\r\n	Cabe ao aluno o controle das 45 (quarenta e cinco) horas aulas te&oacute;ricas e 20 (vinte) aulas pr&aacute;ticas, sendo as aulas pr&aacute;ticas; dezesseis no hor&aacute;rio&nbsp; de 07h00min &agrave;s 18h00min h. de segunda a sexta, e no s&aacute;bado de 07:00 as 13:00, e 4 aulas&nbsp; a noite, a partir das 18:00 h, para s&oacute; ent&atilde;o marcar os respectivos exames. ( te&oacute;rico ou pratico )<br />\r\n	Alunos inscrito para 2 categorias &ldquo;AB&rdquo; ap&oacute;s marcar aulas praticas em uma categoria, retornar ap&oacute;s 10 dias para marcar as aulas praticas na outra categoria. Caso queira cancelar uma categoria, devera ser feito antes do vencimento do processo.<br />\r\n	A data e a confirma&ccedil;&atilde;o do exame pr&aacute;tico s&atilde;o determinados pelo detran, de acordo com as vagas dispon&iacute;veis, cabendo a auto escola fazer um pr&eacute;-agendamento, onde posteriormente ser&aacute; feito a confirma&ccedil;&atilde;o. S&oacute; ser&aacute; liberada marca&ccedil;&atilde;o de exame pr&aacute;tico, mediante autoriza&ccedil;&atilde;o do instrutor.O processo de habilita&ccedil;&atilde;o dever&aacute; ser conclu&iacute;do no prazo m&aacute;ximo de 01 (um) ano (sendo 10 meses para aprova&ccedil;&atilde;o na prova Te&oacute;rica e 11 meses para conclus&atilde;o das aulas praticas)sob a pena de perda da validade. Uma vez encerrada a validade do processo, o CONTRATANTE, caso deseje dar continuidade, dever&aacute; pagar o valor atual correspondente para iniciar um novo processo de habilita&ccedil;&atilde;o.O aluno s&oacute; poder&aacute; ser retirado do exame pratico no dia do exame mediante a apresenta&ccedil;&atilde;o de atestado medico. Poder&aacute; acontecer alguma falha de digital de aulas te&oacute;ricas ou praticas.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n	&nbsp;&Eacute; DE TOTAL RESPONSABILIDADE DO ALUNO QUE VERIFIQUE E ACOMPANHE A VALIDADE DO PROCESSO, DE ACORDO COM O VERSO DO DOCUMENTO EXPEDITO PELO DETRAN. ( acompanhe no site detran as aulas e validade ) Cabe ao aluno a verifica&ccedil;&atilde;o do dia da semana que a autoescola marcou sua prova te&oacute;rica. No caso de parcelam. de valores, o pagamento em atraso acarretar&aacute; juros de 3%(tr&ecirc;s por cento ao m&ecirc;s) a ser pago pelo CONTRATANTE. Em caso de devolu&ccedil;&atilde;o de cheques do contratante,&nbsp; ser&aacute; cobrado o valor de R$ 20,00(vinte reais)por cheque, a t&iacute;tulo de multa.<br />\r\n	No caso do exame de dire&ccedil;&atilde;o o contratante s&oacute; poder&aacute; fazer novo exame ap&oacute;s 16 dias e mediante o pagamento ref ao DUDA, e uma taxa referente ao aluguel do&nbsp; ve&iacute;culo para exame, e depender&aacute; de vaga dispon&iacute;vel pelo detran, correndo o risco de n&atilde;o conseguir esta vaga, caso o processo esteja vencendo.No dia do exame de tr&acirc;nsito o aluno ficar&aacute; o tempo necess&aacute;rio para a realiza&ccedil;&atilde;o da prova.<br />\r\n	Caso aconte&ccedil;a do carro dar defeito no dia da prova pr&aacute;tica, ou se o aluno estiver marcado em outro carro, a auto escola se compromete a dar 1 (uma) aula gratuita antes da pr&oacute;xima prova.<br />\r\n	Caso o CONTRATANTE VENHA A DESISTIR DE SEU PROCESSO DE HABILITA&Ccedil;&Atilde;O, os valores pagos n&atilde;o ser&atilde;o devolvidos.<br />\r\n	De comum acordo, as partes contratantes elegem o Foro da Comarca de Volta Redonda - RJ como competente para.<br />\r\n	Dirimir quaisquer controv&eacute;rsias oriundas do presente Contrato, com expressa ren&uacute;ncia de qualquer outro Foro, por mais privilegiado que seja e por estarem assim justas e contratadas, firmam o presente em duas&nbsp; vias de igual teor e forma.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n	Obs: o aluno dever&aacute; comparecer &agrave; escola para marcar curso te&oacute;rico, prova te&oacute;rica, aulas pr&aacute;ticas e prova pr&aacute;tica. Qualquer reclama&ccedil;&atilde;o de aula pratica (instrutor), devera ser feito imediatamente ap&oacute;s a aula na autoescola.<br />\r\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Volta Redonda, {dataAtual}.</p>\r\n<p>\r\n	<br />\r\n	___________________________________&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; __________________________________&nbsp;&nbsp;&nbsp;<br />\r\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Contratado&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Contratante<br />\r\n	&nbsp;</p>\r\n\'';
    $mysql->save(0, 'declaracoesitens', $pFields);
    
    newSystemVersion($mysql, '10');
} else if ($versaoSistema == '10') {
    
    $mysql->addColumnToTable('examepraticoalunos', 'horario', 'time', false);
    
    newSystemVersion($mysql, '11');
} else if ($versaoSistema == '11') {
    
    $mysql->addColumnToTable('aulaspraticas', 'validado', 'char(1)');
    
    newSystemVersion($mysql, '12');
} else if ($versaoSistema == '12') {
        
    $mysql->addColumnToTable('pessoas', 'celular2', 'varchar(20)', false);
    $mysql->addColumnToTable('pessoas', 'celular3', 'varchar(20)', false);
            
    newSystemVersion($mysql, '13');
} else if ($versaoSistema == '13') {
        
    $mysql->addColumnToTable('alunos', 'noemail', 'char(1)', false);
            
    newSystemVersion($mysql, '14');
}
/*
 * ====================================================================
 */

if (!$processo) {
?>
<div id="dvScriptsAlert" class="ui-state-highlight ui-corner-all" style="display:none;padding: 7px; margin-top: 7px;margin-bottom: 7px;">
</div>
<script type="text/javascript">
    /*
    $icon_info = '<span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"/>';
    $icon_alert = '<span class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"/>';
    divAlertCustomSplitMsg("dvScriptsAlert", "Erro", "<?php echo join('|', $msgErro); ?>");
    */
   alert("<?php echo join('\n', $msgErro); ?>");
</script>
<?php
} 
?>
