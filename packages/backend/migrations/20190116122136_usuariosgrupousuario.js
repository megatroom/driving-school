const tableName = "usuariosgrupousuario";

exports.up = function(knex, Promise) {
  return knex.schema.hasTable(tableName).then(exists => {
    if (!exists) {
      return knex.schema.createTable(tableName, t => {
        t.increments("id").primary();
        t.integer("idusuario").unsigned();
        t.foreign("idusuario").references("usuarios.id");
        t.integer("idgrupousuario").unsigned();
        t.foreign("idgrupousuario").references("gruposusuario.id");
      });
    }
  });
};

exports.down = function(knex, Promise) {
  return knex.schema.dropTableIfExists(tableName);
};
