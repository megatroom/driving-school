const tableName = "modulos";

exports.up = function(knex, Promise) {
  return knex.schema.hasTable(tableName).then(exists => {
    if (!exists) {
      return knex.schema.createTable(tableName, t => {
        t.increments("id").primary();
        t.integer("codigo");
        t.string("descricao", 30);
        t.integer("ordem");
      });
    }
  });
};

exports.down = function(knex, Promise) {
  return knex.schema.dropTableIfExists(tableName);
};

/*
{
		"id" : 1,
		"codigo" : 1,
		"descricao" : "Controles",
		"ordem" : 1
	},
	{
		"id" : 2,
		"codigo" : 2,
		"descricao" : "Cadastros",
		"ordem" : 2
	},
	{
		"id" : 3,
		"codigo" : 3,
		"descricao" : "Financeiro",
		"ordem" : 3
	},
	{
		"id" : 4,
		"codigo" : 4,
		"descricao" : "Emissões",
		"ordem" : 4
	},
	{
		"id" : 5,
		"codigo" : 5,
		"descricao" : "Relatórios",
		"ordem" : 5
	},
	{
		"id" : 6,
		"codigo" : 6,
		"descricao" : "Configurações",
		"ordem" : 6
  }
  */
