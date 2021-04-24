const tableName = 'agendamentos'

exports.up = function (knex, Promise) {
  return knex.schema.hasTable(tableName).then((exists) => {
    if (!exists) {
      return knex.schema.createTable(tableName, (t) => {
        t.increments('id').primary()
        t.string('descricao', 100)
        t.integer('idaluno').unsigned().notNullable()
        t.foreign('idaluno').references('alunos.id')
        t.integer('idtipoagendamento').unsigned().notNullable()
        t.foreign('idtipoagendamento').references('tiposagendamentos.id')
        t.date('data').notNullable()
        t.time('hora')
        t.string('aprovado', 1)
      })
    }
  })
}

exports.down = function (knex, Promise) {
  return knex.schema.dropTableIfExists(tableName)
}
