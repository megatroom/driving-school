import joi from 'joi'
import { pluralize } from '../formatters/string'
import BaseModel from './BaseModel'
import Employee from './Employee'

export default class EmployeeRole extends BaseModel {
  constructor() {
    super('funcoes')
  }

  static postSchema() {
    return {
      description: joi.string().required(),
    }
  }

  static labelsSchema() {
    return {
      description: 'Descrição',
    }
  }

  castPayloadToModel(payload: any) {
    return {
      descricao: payload.description,
    }
  }

  async canDelete(id: number) {
    const employeeCount = await new Employee().countByRoleId(id)
    if (employeeCount > 0) {
      return `Função usada em ${pluralize(
        employeeCount as number,
        'funcionário'
      )}.`
    }

    return null
  }

  findById(id: number) {
    return this.connection
      .select('id', 'descricao as description')
      .from(this.tableName)
      .where({ id })
      .then((models) => (models.length ? models[0] : null))
  }

  findAll(
    limit: number,
    offset: number,
    order: string[],
    search: string | undefined
  ) {
    const orderBy = order.reduce((accumulator: string[], field: string) => {
      switch (field) {
        case 'description':
          return accumulator.concat(['descricao'])
        default:
          return accumulator
      }
    }, [])

    const newConnection = this.connection
      .select('id', 'descricao as description')
      .from(this.tableName)

    if (search) {
      newConnection.where('descricao', 'like', `%${search}%`)
    }

    return newConnection.orderBy(orderBy).limit(limit).offset(offset)
  }
}
