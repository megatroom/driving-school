const crypto = require('crypto')

const tableName = 'tipocarros'

exports.seed = async function (knex) {
  await knex(tableName).del()

  await knex(tableName).insert([
    {
      id: 1,
      descricao: 'Carro',
      comissao: 10,
    },
    {
      id: 2,
      descricao: 'Moto',
      comissao: 8,
    },
    {
      id: 3,
      descricao: 'Caminhão',
      comissao: 5,
    },
  ])
}
