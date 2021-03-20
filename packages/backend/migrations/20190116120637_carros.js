const tableName = "carros";

exports.up = function(knex, Promise) {
  return knex.schema.hasTable(tableName).then(exists => {
    if (!exists) {
      return knex.schema.createTable(tableName, t => {
        t.increments("id").primary();
        t.integer("idtipocarro").unsigned();
        t.foreign("idtipocarro").references("tipocarros.id");
        t.string("descricao", 100);
        t.string("placa", 7);
        t.integer("ano");
        t.integer("anomodelo");
        t.date("datacompra");
        t.date("datavenda");
        t.integer("idfunfixo").unsigned();
      });
    }
  });
};

exports.down = function(knex, Promise) {
  return knex.schema.dropTableIfExists(tableName);
};
