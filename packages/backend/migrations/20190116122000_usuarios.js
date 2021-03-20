const tableName = "usuarios";

exports.up = function(knex, Promise) {
  return knex.schema.hasTable(tableName).then(exists => {
    if (!exists) {
      return knex.schema.createTable(tableName, t => {
        t.increments("id").primary();
        t.integer("idfuncionario").unsigned();
        t.foreign("idfuncionario").references("funcionarios.id");
        t.integer("idcliente").unsigned();
        t.string("login", 20);
        t.string("senha", 100);
        t.string("nome", 60);
        t.text("observacao");
      });
    }
  });
};

exports.down = function(knex, Promise) {
  return knex.schema.dropTableIfExists(tableName);
};
