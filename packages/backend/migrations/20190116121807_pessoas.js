const tableName = "pessoas";

exports.up = function(knex, Promise) {
  return knex.schema.hasTable(tableName).then(exists => {
    if (!exists) {
      return knex.schema.createTable(tableName, t => {
        t.increments("id").primary();
        t.string("nome", 100);
        t.date("dtnascimento");
        t.string("sexo", 1);
        t.string("rg", 20);
        t.string("orgaoemissor", 30);
        t.date("rgdataemissao");
        t.string("cpf", 20);
        t.string("carteiradetrabalho", 50);
        t.string("endereco", 100);
        t.string("cep", 100);
        t.string("bairro", 100);
        t.string("cidade", 100);
        t.string("estado", 100);
        t.string("telefone", 100);
        t.string("celular", 100);
        t.string("email", 200);
        t.string("pai", 100);
        t.string("mae", 100);
        t.string("telcontato", 100);
        t.string("telefone2", 100);
        t.string("tel2contato", 100);
        t.string("celular2", 100);
        t.string("celular3", 100);
      });
    }
  });
};

exports.down = function(knex, Promise) {
  return knex.schema.dropTableIfExists(tableName);
};
