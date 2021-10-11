import joi from 'joi'
import { encryptPassword } from '../app/security'
import { optionalForeignKey } from '../validators/fields'
import BaseModel from './BaseModel'

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

class User extends BaseModel {
  constructor() {
    super('usuarios')
  }

  static postSchema() {
    return {
      login: joi.string().required(),
      employeeId: joi.number().custom(optionalForeignKey).allow(null),
      name: joi.any().when('employeeId', {
        is: joi.number().min(1),
        then: joi.string().allow(null),
        otherwise: joi.string().required(),
      }),
      observations: joi.string().allow(null),
    }
  }

  static labelsSchema() {
    return {
      login: 'Login',
      name: 'Nome',
      observations: 'Observação',
      employeeId: 'Funcionário',
      employeeName: 'Funcionário',
    }
  }

  castPayloadToModel(payload: any) {
    return {
      login: payload.login,
      nome: payload.name,
      observacao: payload.observations,
      idfuncionario: payload.employeeId,
    }
  }

  canDelete(id: number): Promise<string | null> {
    throw new Error('Method not implemented.')
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
      .first(
        'u.id',
        'u.login',
        'u.nome as name',
        'u.observacao as observations',
        'f.id as employeeId',
        'p.nome as employeeName'
      )
      .from({ u: this.tableName })
      .leftJoin({ f: 'funcionarios' }, 'u.idfuncionario', 'f.id')
      .leftJoin({ p: 'pessoas' }, 'f.idpessoa', 'p.id')
      .where({ 'u.id': id })
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

  async findAll(
    limit: number,
    offset: number,
    order: string[],
    orderDirection: string,
    search: string | undefined
  ) {
    const orderBy = order.reduce((accumulator: any[], field: string) => {
      switch (field) {
        case 'name':
          return accumulator.concat([{ column: 'name', order: orderDirection }])
        case 'login':
          return accumulator.concat([
            { column: 'u.login', order: orderDirection },
          ])
        default:
          return accumulator
      }
    }, [])

    const selectConnection = this.connection
      .select(
        'u.id',
        'u.login',
        'f.id as employeeId',
        this.connection.raw('coalesce(p.nome, u.nome) as name'),
        'u.observacao as observations'
      )
      .from({ u: this.tableName })

    const countConnection = this.connection({ u: this.tableName })
      .count('u.id as total')
      .leftJoin({ f: 'funcionarios' }, 'u.idfuncionario', 'f.id')
      .leftJoin({ p: 'pessoas' }, 'f.idpessoa', 'p.id')

    if (search) {
      selectConnection
        .where('u.login', 'like', `%${search}%`)
        .orWhere('u.nome', 'like', `%${search}%`)
        .orWhere('p.nome', 'like', `%${search}%`)
      countConnection
        .where('u.login', 'like', `%${search}%`)
        .orWhere('u.nome', 'like', `%${search}%`)
        .orWhere('p.nome', 'like', `%${search}%`)
    }

    selectConnection
      .leftJoin({ f: 'funcionarios' }, 'u.idfuncionario', 'f.id')
      .leftJoin({ p: 'pessoas' }, 'f.idpessoa', 'p.id')
      .orderBy(orderBy)
      .limit(limit)
      .offset(offset)

    const [countRes, data] = await Promise.all([
      countConnection,
      selectConnection,
    ])

    return {
      total: countRes[0].total,
      data,
    }
  }
}

export default User
