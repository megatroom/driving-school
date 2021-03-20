const tableName = "acessofunc";

exports.up = function(knex, Promise) {
  return knex.schema.hasTable(tableName).then(exists => {
    if (!exists) {
      return knex.schema.createTable(tableName, t => {
        t.increments("id").primary();
        t.integer("idgrupousuario").unsigned();
        t.foreign("idgrupousuario").references("gruposusuario.id");
        t.integer("idfuncionalidade").unsigned();
      });
    }
  });
};

exports.down = function(knex, Promise) {
  return knex.schema.dropTableIfExists(tableName);
};
