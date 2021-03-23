import { Knex } from 'knex'

import { encryptPassword } from '../app/security'
import { getDbConnection } from '../app/database'

interface IMenuModel {
  group_id: number
  group_code: string
  group_name: string
  page_id: number
  page_code: string
  page_name: string
  page_path: string
}

interface IPage {
  id: number
  code: string
  name: string
  path: string
}

interface IMenu {
  id: number
  code: string
  name: string
  pages: IPage[]
}

const formatMenu = (models: IMenuModel[]) => {
  if (!models) {
    return []
  }

  const result: IMenu[] = []

  models.forEach((model) => {
    let index = result.length - 1

    if (result.length === 0 || result[index].id !== model.group_id) {
      result.push({
        id: model.group_id,
        code: model.group_code,
        name: model.group_name,
        pages: [],
      })

      index++
    }

    result[index].pages.push({
      id: model.page_id,
      code: model.page_code,
      name: model.page_name,
      path: model.page_path,
    })
  })

  return result
}

class User {
  connection: Knex
  tableName: string

  constructor() {
    this.connection = getDbConnection()
    this.tableName = 'usuarios'
  }

  findIdByLogin(login: string, password: string) {
    return this.connection
      .select('id', 'senha as password')
      .from(this.tableName)
      .where({ login })
      .then((rows) => {
        if (rows.length > 0 && encryptPassword(password) === rows[0].password) {
          return rows[0].id
        }

        return false
      })
  }

  findById(id: number) {
    return this.connection
      .first('id', 'login', 'nome as name')
      .from(this.tableName)
      .where({ id })
  }

  findAllPages() {
    return this.connection
      .select(
        'm.id as group_id',
        'm.descricao as group_name',
        'm.codigo as group_code',
        't.id as page_id',
        't.codigo as page_code',
        't.descricao as page_name',
        't.endereco as page_path'
      )
      .from({ t: 'telas' })
      .innerJoin({ m: 'modulos' }, 't.idmodulo', 'm.id')
      .orderBy(['m.ordem', 't.ordem'])
      .then(formatMenu)
  }

  findPagesByUser(id: number) {
    return this.connection
      .select(
        'm.id as group_id',
        'm.descricao as group_name',
        'm.codigo as group_code',
        't.id as page_id',
        't.codigo as page_code',
        't.descricao as page_name',
        't.endereco as page_path'
      )
      .from({ t: 'telas' })
      .innerJoin({ m: 'modulos' }, 't.idmodulo', 'm.id')
      .leftJoin({ a: 'acesso' }, 'a.idtela', 't.id')
      .leftJoin(
        { ugu: 'usuariosgrupousuario' },
        'ugu.idgrupousuario',
        'a.idgrupousuario'
      )
      .leftJoin({ u: 'usuarios' }, 'u.id', 'ugu.idusuario')
      .where('u.id', id)
      .orWhere('t.padrao', 1)
      .orderBy(['m.ordem', 't.ordem'])
      .then(formatMenu)
  }
}

export default User
