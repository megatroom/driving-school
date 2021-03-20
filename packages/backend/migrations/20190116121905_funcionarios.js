const tableName = "funcionarios";

exports.up = function(knex, Promise) {
  return knex.schema.hasTable(tableName).then(exists => {
    if (!exists) {
      return knex.schema.createTable(tableName, t => {
        t.increments("id").primary();
        t.integer("idfuncao").unsigned();
        t.integer("idpessoa").unsigned();
        t.foreign("idpessoa").references("pessoas.id");
        t.string("matricula", 10);
        t.string("status", 1);
      });
    }
  });
};

exports.down = function(knex, Promise) {
  return knex.schema.dropTableIfExists(tableName);
};
