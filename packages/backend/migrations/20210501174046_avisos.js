const tableName = 'avisos'

exports.up = function (knex, Promise) {
  return knex.schema.hasTable(tableName).then((exists) => {
    if (!exists) {
      return knex.schema.createTable(tableName, (t) => {
        t.increments('id').primary()
        t.integer('iddestinatario').unsigned()
        t.foreign('iddestinatario').references('usuarios.id')
        t.integer('idremetente').unsigned().notNullable()
        t.foreign('idremetente').references('usuarios.id')
        t.text('mensagem').notNullable()
        t.date('data').notNullable()
        t.string('prioridade', 1).notNullable()
        t.string('status', 1).notNullable()
      })
    }
  })
}

exports.down = function (knex, Promise) {
  return knex.schema.dropTableIfExists(tableName)
}
