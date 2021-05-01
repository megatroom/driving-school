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
      studentId: joi.number().required(),
      schedulingTypeId: joi.number().required(),
      date: joi.date().iso().required(),
      time: joi.string().allow(null),
      approved: joi
        .string()
        .valid('N', 'A', 'C', 'F', 'R', 'M', 'T')
        .allow(null),
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
      approvedDesc: 'Resultado',
    }
  }

  castPayloadToModel(payload: any) {
    return {
      idaluno: payload.studentId,
      idtipoagendamento: payload.schedulingTypeId,
      data: payload.date,
      hora: payload.time,
      aprovado: payload.approved,
    }
  }

  castApprovedCodeToString(approved: string) {
    switch (approved) {
      case 'N':
        return 'Não se aplica'
      case 'A':
        return 'Aprovado'
      case 'F':
        return 'Falta'
      case 'C':
        return 'Cancelado Aluno'
      case 'T':
        return 'Retirado'
      case 'M':
        return 'Não Marcado'
      case 'R':
        return 'Reprovado'
      default:
        return null
    }
  }

  castModelToResponse(model: any) {
    return {
      ...model,
      approvedDesc: this.castApprovedCodeToString(model.approved),
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

  async findAll(
    limit: number,
    offset: number,
    order: string[],
    orderDirection: string,
    search: string | undefined,
    dateAbove: string | undefined
  ) {
    const orderBy = order.reduce((accumulator: any[], field: string) => {
      switch (field) {
        case 'description':
          return accumulator.concat([
            { column: 'd.descricao', order: orderDirection },
          ])
        case 'studentName':
          return accumulator.concat([
            { column: 'c.nome', order: orderDirection },
          ])
        case 'date':
        case 'time':
          return accumulator.concat([
            { column: 'a.data', order: orderDirection },
            { column: 'a.hora', order: orderDirection },
          ])
        default:
          return accumulator
      }
    }, [])

    const selectConnection = this.connection
      .select(
        'a.id',
        'a.data as date',
        'a.hora as time',
        'a.aprovado as approved',
        'c.nome as studentName',
        'd.descricao as description'
      )
      .from({ a: this.tableName })
      .innerJoin({ b: 'alunos' }, 'a.idaluno', 'b.id')
      .innerJoin({ c: 'pessoas' }, 'b.idpessoa', 'c.id')
      .innerJoin({ d: 'tiposagendamentos' }, 'a.idtipoagendamento', 'd.id')

    const countConnection = this.connection({ a: this.tableName })
      .count('a.id as total')
      .innerJoin({ b: 'alunos' }, 'a.idaluno', 'b.id')
      .innerJoin({ c: 'pessoas' }, 'b.idpessoa', 'c.id')
      .innerJoin({ d: 'tiposagendamentos' }, 'a.idtipoagendamento', 'd.id')

    if (search) {
      selectConnection
        .where('d.descricao', 'like', `%${search}%`)
        .orWhere('c.nome', 'like', `%${search}%`)
      countConnection
        .where('d.descricao', 'like', `%${search}%`)
        .orWhere('c.nome', 'like', `%${search}%`)
    }

    if (dateAbove) {
      selectConnection.where('a.data', '>=', dateAbove)
      countConnection.where('a.data', '>=', dateAbove)
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

  count() {
    return this.connection(this.tableName)
      .count('id as total')
      .then((models) => models[0].total)
  }

  findById(id: number) {
    return this.connection
      .select(
        'a.id',
        'a.data as date',
        'a.hora as time',
        'a.aprovado as approved',
        'a.idaluno as studentId',
        'c.nome as studentName',
        'a.idtipoagendamento as schedulingTypeId',
        'd.descricao as description'
      )
      .from({ a: this.tableName })
      .where({ 'a.id': id })
      .innerJoin({ b: 'alunos' }, 'a.idaluno', 'b.id')
      .innerJoin({ c: 'pessoas' }, 'b.idpessoa', 'c.id')
      .innerJoin({ d: 'tiposagendamentos' }, 'a.idtipoagendamento', 'd.id')
      .then((models) =>
        models.length ? this.castModelToResponse(models[0]) : null
      )
  }
}
