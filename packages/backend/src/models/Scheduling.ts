import joi from 'joi'

import { pluralize } from '../formatters/string'
import BaseModel from './BaseModel'
import Car from './Car'

export default class SchedulingType extends BaseModel {
  constructor() {
    super('agendamentos')
  }

  static postSchema() {
    return {
      description: joi.string().required(),
      studentId: joi.number().required(),
      schedulingTypeId: joi.number().required(),
      date: joi.date().iso().required(),
      time: joi.date().iso().allow(null),
      approved: joi.string().valid('N', 'A', 'C', 'F', 'R', 'T').allow(null),
    }
  }

  static labelsSchema() {
    return {
      description: 'Descrição',
      studentId: 'Aluno',
      schedulingTypeId: 'Tipo',
      date: 'Data',
      time: 'Hora',
      approved: 'Resultado',
    }
  }

  castPayloadToModel(payload: any) {
    return {
      descricao: payload.description,
      idaluno: payload.studentId,
      idtipoagendamento: payload.schedulingTypeId,
      data: payload.date,
      hora: payload.time,
      aprovado: payload.approved,
    }
  }

  async canDelete(id: number) {
    const carCount = await new Car().countByCarTypeId(id)
    if (carCount > 0) {
      return `Tipo de agendamento usado em ${pluralize(
        carCount as number,
        'carro'
      )}.`
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

  findById(id: number) {
    return this.connection
      .select('id', 'descricao as description')
      .from(this.tableName)
      .where({ id })
      .then((models) => (models.length ? models[0] : null))
  }
}
