import joi from 'joi'
import BaseModel from './BaseModel'
class CarType extends BaseModel {
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

  findAll(limit: number, offset: number, order: string[]) {
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

    return this.connection
      .select('id', 'descricao as description', 'comissao as commission')
      .from(this.tableName)
      .orderBy(orderBy)
      .limit(limit)
      .offset(offset)
  }

  findById(id: number) {
    return this.connection
      .select('id', 'descricao as description', 'comissao as commission')
      .from(this.tableName)
      .where({ id })
      .then((models) => (models.length ? models[0] : null))
  }
}

export default CarType
