import joi from 'joi'

import { pluralize } from '../formatters/string'
import BaseModel from './BaseModel'
import Car from './Car'
export default class CarType extends BaseModel {
  constructor() {
    super('tipocarros')
  }

  static postSchema() {
    return {
      description: joi.string().required(),
      commission: joi.number().required(),
    }
  }

  static labelsSchema() {
    return {
      description: 'Descrição',
      commission: 'Comissão',
    }
  }

  castPayloadToModel(payload: any) {
    return {
      descricao: payload.description,
      comissao: payload.commission,
    }
  }

  async canDelete(id: number) {
    const carCount = await new Car().countByCarTypeId(id)
    if (carCount > 0) {
      return `Tipo usado em ${pluralize(carCount as number, 'carro')}.`
    }

    return null
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
        case 'commission':
          return accumulator.concat(['comissao'])
        default:
          return accumulator
      }
    }, [])

    const newConnection = this.connection
      .select('id', 'descricao as description', 'comissao as commission')
      .from(this.tableName)

    if (search) {
      newConnection.where('descricao', 'like', `%${search}%`)
    }

    return newConnection.orderBy(orderBy).limit(limit).offset(offset)
  }

  findById(id: number) {
    return this.connection
      .select('id', 'descricao as description', 'comissao as commission')
      .from(this.tableName)
      .where({ id })
      .then((models) => (models.length ? models[0] : null))
  }
}
