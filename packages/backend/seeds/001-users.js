const crypto = require('crypto')

const peopleTableName = 'pessoas'
const employeeTableName = 'funcionarios'
const userTableName = 'usuarios'
const userGroupTableName = 'gruposusuario'
const userGroupUserTableName = 'usuariosgrupousuario'

function encryptPassword(pwd) {
  return crypto.createHash('md5').update(pwd).digest('hex')
}

exports.seed = async function (knex) {
  await knex(userGroupUserTableName).del()
  await knex(userGroupTableName).del()
  await knex(userTableName).del()
  await knex(employeeTableName).del()
  await knex(peopleTableName).del()

  await knex(peopleTableName).insert([{ id: 1, nome: 'Admin' }])

  await knex(employeeTableName).insert([{ id: 1, idpessoa: 1 }])

  await knex(userTableName).insert([
    {
      id: 1,
      idfuncionario: 1,
      login: 'admin',
      senha: encryptPassword('admin'),
    },
  ])

  await knex(userGroupTableName).insert([
    { id: 1, descricao: 'atendente' },
    { id: 2, descricao: 'instrutor' },
    { id: 3, descricao: 'administrador' },
  ])

  await knex(userGroupUserTableName).insert([
    { id: 1, idusuario: 1, idgrupousuario: 3 },
  ])
}
