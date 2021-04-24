const tableName = 'alunos'

exports.up = function (knex) {
  return knex.schema.hasTable(tableName).then((exists) => {
    if (!exists) {
      return knex.schema.createTable(tableName, (t) => {
        t.increments('id').primary()
        t.integer('idpessoa').unsigned().notNullable()
        t.foreign('idpessoa').references('pessoa.id')
        t.integer('idorigem').unsigned()
        t.foreign('idorigem').references('origens.id')
        t.int('matricula').notNullable()
        t.string('matriculacfc', 20)
        t.string('renach', 20)
        t.text('observacoes')
        t.string('regcnh', 30)
        t.string('categoriaatual', 30)
        t.date('validadeprocesso')
        t.int('codacess')
        t.string('noemail', 1)
        t.datetime('dtcreate')
        t.datetime('dtupdate')
      })
    }
  })
}

exports.down = function (knex) {
  return knex.schema.dropTableIfExists(tableName)
}
