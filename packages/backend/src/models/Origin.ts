import joi from 'joi'

import { pluralize } from '../formatters/string'
import BaseModel from './BaseModel'
import Student from './Student'

export default class Origin extends BaseModel {
  constructor() {
    super('origens')
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
    const studentCount = await new Student().countByOriginId(id)
    if (studentCount > 0) {
      return `Origem usada em ${pluralize(studentCount as number, 'aluno')}.`
    }

    return null
  }

  async findAll(
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

    const selectConnection = this.connection
      .select('id', 'descricao as description')
      .from(this.tableName)

    const countConnection = this.connection(this.tableName).count('id as total')

    if (search) {
      selectConnection.where('descricao', 'like', `%${search}%`)
      countConnection.where('descricao', 'like', `%${search}%`)
    }

    selectConnection.orderBy(orderBy).limit(limit).offset(offset)

    const [countRes, data] = await Promise.all([
      countConnection,
      selectConnection,
    ])

    return {
      total: countRes[0].total,
      data,
    }
  }

  findById(id: number) {
    return this.connection
      .select('id', 'descricao as description')
      .from(this.tableName)
      .where({ id })
      .then((models) => (models.length ? models[0] : null))
  }
}
