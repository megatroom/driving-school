const tableName = "tipocarros";

exports.up = function(knex, Promise) {
  return knex.schema.hasTable(tableName).then(exists => {
    if (!exists) {
      return knex.schema.createTable(tableName, t => {
        t.increments("id").primary();
        t.string("descricao", 100);
        t.decimal("comissao");
      });
    }
  });
};

exports.down = function(knex, Promise) {
  return knex.schema.dropTableIfExists(tableName);
};
