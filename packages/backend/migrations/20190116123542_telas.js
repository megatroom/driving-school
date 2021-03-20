const tableName = "telas";

exports.up = function(knex, Promise) {
  return knex.schema.hasTable(tableName).then(exists => {
    if (!exists) {
      return knex.schema.createTable(tableName, t => {
        t.increments("id").primary();
        t.integer("idmodulo").unsigned();
        t.foreign("idmodulo").references("modulos.id");
        t.integer("codigo");
        t.string("descricao", 30);
        t.string("endereco", 60);
        t.integer("ordem");
        t.integer("padrao");
        t.string("icone", 100);
      });
    }
  });
};

exports.down = function(knex, Promise) {
  return knex.schema.dropTableIfExists(tableName);
};

/*
[
	{
		"id" : 1,
		"idmodulo" : 1,
		"codigo" : 1,
		"descricao" : "Serviços",
		"endereco" : "modulos\/servicos\/index.php",
		"ordem" : 1,
		"padrao" : 0,
		"icone" : "cracha.png"
	},
	{
		"id" : 2,
		"idmodulo" : 1,
		"codigo" : 2,
		"descricao" : "Aulas Práticas",
		"endereco" : "modulos\/aulaspraticas\/index.php",
		"ordem" : 2,
		"padrao" : 0,
		"icone" : "sinal.png"
	},
	{
		"id" : 3,
		"idmodulo" : 1,
		"codigo" : 3,
		"descricao" : "Aulas Teóricas",
		"endereco" : "modulos\/aulasteoricas\/index.php",
		"ordem" : 3,
		"padrao" : 0,
		"icone" : "cadeira.png"
	},
	{
		"id" : 4,
		"idmodulo" : 1,
		"codigo" : 4,
		"descricao" : "Exame Prático",
		"endereco" : "modulos\/examepratico\/index.php",
		"ordem" : 4,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 5,
		"idmodulo" : 1,
		"codigo" : 5,
		"descricao" : "Turmas",
		"endereco" : "modulos\/turmas\/index.php",
		"ordem" : 5,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 6,
		"idmodulo" : 1,
		"codigo" : 6,
		"descricao" : "Avisos",
		"endereco" : "modulos\/avisos\/index.php",
		"ordem" : 6,
		"padrao" : 0,
		"icone" : "correio.png"
	},
	{
		"id" : 7,
		"idmodulo" : 1,
		"codigo" : 7,
		"descricao" : "Agendamentos",
		"endereco" : "modulos\/agendamentos\/index.php",
		"ordem" : 7,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 8,
		"idmodulo" : 2,
		"codigo" : 8,
		"descricao" : "Funções",
		"endereco" : "modulos\/funcoes\/index.php",
		"ordem" : 1,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 9,
		"idmodulo" : 2,
		"codigo" : 9,
		"descricao" : "Funcionários",
		"endereco" : "modulos\/funcionarios\/index.php",
		"ordem" : 2,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 10,
		"idmodulo" : 2,
		"codigo" : 10,
		"descricao" : "Alunos",
		"endereco" : "modulos\/alunos\/index.php",
		"ordem" : 3,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 11,
		"idmodulo" : 2,
		"codigo" : 11,
		"descricao" : "Carros",
		"endereco" : "modulos\/carros\/index.php",
		"ordem" : 4,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 12,
		"idmodulo" : 2,
		"codigo" : 12,
		"descricao" : "Carros x Func.",
		"endereco" : "modulos\/carrosfun\/index.php",
		"ordem" : 5,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 13,
		"idmodulo" : 2,
		"codigo" : 13,
		"descricao" : "Salas",
		"endereco" : "modulos\/salas\/index.php",
		"ordem" : 6,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 14,
		"idmodulo" : 2,
		"codigo" : 14,
		"descricao" : "Turnos",
		"endereco" : "modulos\/turnos\/index.php",
		"ordem" : 7,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 15,
		"idmodulo" : 2,
		"codigo" : 15,
		"descricao" : "Expediente",
		"endereco" : "modulos\/expediente\/index.php",
		"ordem" : 8,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 16,
		"idmodulo" : 2,
		"codigo" : 16,
		"descricao" : "Tipos de Agendamento",
		"endereco" : "modulos\/tipoagendamento\/index.php",
		"ordem" : 9,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 17,
		"idmodulo" : 2,
		"codigo" : 17,
		"descricao" : "Tipos de Serviço",
		"endereco" : "modulos\/tiposervicos\/index.php",
		"ordem" : 10,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 18,
		"idmodulo" : 2,
		"codigo" : 18,
		"descricao" : "Tipos de Carros",
		"endereco" : "modulos\/tipocarros\/index.php",
		"ordem" : 11,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 19,
		"idmodulo" : 3,
		"codigo" : 19,
		"descricao" : "Contas a Receber",
		"endereco" : "modulos\/contasareceber\/index.php",
		"ordem" : 1,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 20,
		"idmodulo" : 3,
		"codigo" : 20,
		"descricao" : "Comissão",
		"endereco" : "modulos\/comissao\/index.php",
		"ordem" : 2,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 21,
		"idmodulo" : 3,
		"codigo" : 21,
		"descricao" : "Caixa",
		"endereco" : "modulos\/caixa\/index.php",
		"ordem" : 3,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 22,
		"idmodulo" : 3,
		"codigo" : 22,
		"descricao" : "Controle de Caixas",
		"endereco" : "modulos\/ctrcaixa\/index.php",
		"ordem" : 4,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 23,
		"idmodulo" : 6,
		"codigo" : 23,
		"descricao" : "Usuários",
		"endereco" : "modulos\/usuarios\/index.php",
		"ordem" : 1,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 24,
		"idmodulo" : 6,
		"codigo" : 24,
		"descricao" : "Trocar Senha",
		"endereco" : "modulos\/usuarios\/senha.php",
		"ordem" : 2,
		"padrao" : 1,
		"icone" : null
	},
	{
		"id" : 25,
		"idmodulo" : 6,
		"codigo" : 25,
		"descricao" : "Grupos de Usuário",
		"endereco" : "modulos\/gruposusuario\/index.php",
		"ordem" : 3,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 26,
		"idmodulo" : 6,
		"codigo" : 26,
		"descricao" : "Controle de Acessos",
		"endereco" : "modulos\/usuarios\/acesso.php",
		"ordem" : 4,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 27,
		"idmodulo" : 6,
		"codigo" : 27,
		"descricao" : "Ícones",
		"endereco" : "modulos\/icones\/form.php",
		"ordem" : 5,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 28,
		"idmodulo" : 6,
		"codigo" : 28,
		"descricao" : "Sistema",
		"endereco" : "modulos\/sistema\/form.php",
		"ordem" : 6,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 29,
		"idmodulo" : 6,
		"codigo" : 29,
		"descricao" : "Sair (Logout)",
		"endereco" : "modulos\/usuarios\/logout.php",
		"ordem" : 7,
		"padrao" : 1,
		"icone" : null
	},
	{
		"id" : 30,
		"idmodulo" : 2,
		"codigo" : 30,
		"descricao" : "Origens",
		"endereco" : "modulos\/origens\/index.php",
		"ordem" : 12,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 31,
		"idmodulo" : 4,
		"codigo" : 31,
		"descricao" : "Planilha do Instrutor",
		"endereco" : "relatorios\/aulaspraticas\/index.php",
		"ordem" : 1,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 32,
		"idmodulo" : 4,
		"codigo" : 32,
		"descricao" : "Declaração",
		"endereco" : "relatorios\/declaracao\/index.php",
		"ordem" : 2,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 33,
		"idmodulo" : 5,
		"codigo" : 33,
		"descricao" : "Agendamentos",
		"endereco" : "relatorios\/agendamentos\/index.php",
		"ordem" : 1,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 34,
		"idmodulo" : 5,
		"codigo" : 34,
		"descricao" : "Exame Prático",
		"endereco" : "relatorios\/examepratico\/index.php",
		"ordem" : 2,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 35,
		"idmodulo" : 3,
		"codigo" : 35,
		"descricao" : "Relatórios",
		"endereco" : "relatorios\/relcaixa\/index.php",
		"ordem" : 5,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 36,
		"idmodulo" : 4,
		"codigo" : 36,
		"descricao" : "Aulas Alunos",
		"endereco" : "relatorios\/aulasalunos\/index.php",
		"ordem" : 3,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 37,
		"idmodulo" : 6,
		"codigo" : 37,
		"descricao" : "Menu",
		"endereco" : "modulos\/menu\/index.php",
		"ordem" : 8,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 38,
		"idmodulo" : 5,
		"codigo" : 38,
		"descricao" : "Validade Processo",
		"endereco" : "relatorios\/validprocesso\/index.php",
		"ordem" : 3,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 39,
		"idmodulo" : 5,
		"codigo" : 39,
		"descricao" : "Ficha Aluno",
		"endereco" : "relatorios\/fichaaluno\/index.php",
		"ordem" : 4,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 40,
		"idmodulo" : 5,
		"codigo" : 40,
		"descricao" : "Exame Prático Alunos",
		"endereco" : "relatorios\/examepraticoalunos\/index.php",
		"ordem" : 5,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 41,
		"idmodulo" : 3,
		"codigo" : 41,
		"descricao" : "Recibos",
		"endereco" : "relatorios\/recibopagto\/index.php",
		"ordem" : 6,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 42,
		"idmodulo" : 6,
		"codigo" : 42,
		"descricao" : "Backup",
		"endereco" : "modulos\/backup\/index.php",
		"ordem" : 9,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 43,
		"idmodulo" : 3,
		"codigo" : 43,
		"descricao" : "Declaração Pagto",
		"endereco" : "modulos\/declarapagamento\/index.php",
		"ordem" : 7,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 44,
		"idmodulo" : 4,
		"codigo" : 44,
		"descricao" : "Aulas Teóricas",
		"endereco" : "relatorios\/aulasteoricas\/index.php",
		"ordem" : 4,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 45,
		"idmodulo" : 2,
		"codigo" : 45,
		"descricao" : "Vales",
		"endereco" : "modulos\/vales\/index.php",
		"ordem" : 13,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 46,
		"idmodulo" : 5,
		"codigo" : 46,
		"descricao" : "Vales",
		"endereco" : "relatorios\/vales\/index.php",
		"ordem" : 6,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 47,
		"idmodulo" : 6,
		"codigo" : 47,
		"descricao" : "Sobre",
		"endereco" : "modulos\/sobre\/index.php",
		"ordem" : 10,
		"padrao" : 1,
		"icone" : null
	},
	{
		"id" : 48,
		"idmodulo" : 5,
		"codigo" : 48,
		"descricao" : "Caixa Por Usuário",
		"endereco" : "relatorios\/caixaporusuario\/index.php",
		"ordem" : 7,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 49,
		"idmodulo" : 2,
		"codigo" : 49,
		"descricao" : "Bônus",
		"endereco" : "modulos\/bonus\/index.php",
		"ordem" : 14,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 50,
		"idmodulo" : 5,
		"codigo" : 50,
		"descricao" : "Tipo de Serviços",
		"endereco" : "relatorios\/tiposervicos\/index.php",
		"ordem" : 8,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 51,
		"idmodulo" : 5,
		"codigo" : 51,
		"descricao" : "Ranking Exame Prático",
		"endereco" : "relatorios\/rankingexamepratico\/index.php",
		"ordem" : 9,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 52,
		"idmodulo" : 5,
		"codigo" : 52,
		"descricao" : "Aula Prática Duplicada",
		"endereco" : "relatorios\/aulapraticaduplicada\/index.php",
		"ordem" : 10,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 53,
		"idmodulo" : 2,
		"codigo" : 53,
		"descricao" : "Horário Exame Prático",
		"endereco" : "modulos\/examepraticohorario\/index.php",
		"ordem" : 15,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 54,
		"idmodulo" : 1,
		"codigo" : 54,
		"descricao" : "Observação Aluno",
		"endereco" : "modulos\/obsaluno\/index.php",
		"ordem" : 8,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 55,
		"idmodulo" : 3,
		"codigo" : 55,
		"descricao" : "Tipo Contas a Pagar",
		"endereco" : "app\/tipocontasapagar\/",
		"ordem" : 8,
		"padrao" : 0,
		"icone" : null
	},
	{
		"id" : 56,
		"idmodulo" : 3,
		"codigo" : 56,
		"descricao" : "Contas a Pagar",
		"endereco" : "app\/contasapagar\/",
		"ordem" : 9,
		"padrao" : 0,
		"icone" : null
	}
]
*/
