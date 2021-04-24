const tableName = 'tiposagendamentos'

exports.seed = async function (knex) {
  await knex(tableName).del()

  await knex(tableName).insert([
    {
      id: 1,
      descricao: 'Cadastro no Detran RJ',
    },
    {
      id: 2,
      descricao: 'Exame na clínica',
    },
    {
      id: 3,
      descricao: 'Prova teórica',
    },
  ])
}
