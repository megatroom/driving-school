import joi from 'joi'
import BaseModel from './BaseModel'
import { requiredForeignKey, optionalForeignKey } from '../validators/fields'
import { dateStringToObject } from '../formatters/date'

export default class Car extends BaseModel {
  constructor() {
    super('carros')
  }

  static postSchema() {
    return {
      carTypeId: joi.number().custom(requiredForeignKey).required(),
      fixedEmployeeId: joi.number().custom(optionalForeignKey).allow(null),
      description: joi.string().required(),
      licensePlate: joi.string().required(),
      year: joi.number().allow(null),
      modelYear: joi.number().allow(null),
      purchaseDate: joi.date().iso().allow(null),
      saleDate: joi.date().iso().allow(null),
    }
  }

  static labelsSchema() {
    return {
      description: 'Descrição',
      licensePlate: 'Placa',
      year: 'Ano Fabricação',
      modelYear: 'Ano Modelo',
      purchaseDate: 'Data de compra',
      saleDate: 'Data de venda',
      carTypeId: 'Tipo',
      fixedEmployeeId: 'Instrutor Fixo',
    }
  }

  castPayloadToModel(payload: any) {
    return {
      idtipocarro: payload.carTypeId,
      idfunfixo: payload.fixedEmployeeId,
      descricao: payload.description,
      placa: payload.licensePlate,
      ano: payload.year,
      anomodelo: payload.modelYear,
      datacompra: dateStringToObject(payload.purchaseDate),
      datavenda: dateStringToObject(payload.saleDate),
    }
  }

  findById(id: number) {
    return this.connection
      .select(
        'c.id',
        'c.idtipocarro as carTypeId',
        't.descricao as carTypeDescription',
        'c.idfunfixo as fixedEmployeeId',
        'p.nome as fixedEmployeeName',
        'c.descricao as description',
        'c.placa as licensePlate',
        'c.ano as year',
        'c.anomodelo as modelYear',
        'c.datacompra as purchaseDate',
        'c.datavenda as saleDate'
      )
      .from({ c: this.tableName })
      .where({ 'c.id': id })
      .innerJoin({ t: 'tipocarros' }, 'c.idtipocarro', 't.id')
      .leftJoin({ f: 'funcionarios' }, 'c.idfunfixo', 'f.id')
      .leftJoin({ p: 'pessoas' }, 'f.idpessoa', 'p.id')
      .then((models) => {
        if (models.length) {
          return models[0]
        }

        return null
      })
  }

  findAll(
    limit: number,
    offset: number,
    order: string[],
    search: string | undefined
  ) {
    const orderBy = order.reduce((accumulator: string[], field: string) => {
      switch (field) {
        case 'carTypeDescription':
          return accumulator.concat(['t.descricao', 'c.descricao'])
        case 'description':
          return accumulator.concat(['c.descricao'])
        case 'fixedEmployeeName':
          return accumulator.concat(['p.nome', 'c.descricao'])
        case 'licensePlate':
          return accumulator.concat(['c.placa'])
        default:
          return accumulator
      }
    }, [])

    const newConnection = this.connection
      .select(
        'c.id',
        'c.idtipocarro as carTypeId',
        't.descricao as carTypeDescription',
        'c.idfunfixo as fixedEmployeeId',
        'p.nome as fixedEmployeeName',
        'c.descricao as description',
        'c.placa as licensePlate'
      )
      .from({ c: this.tableName })

    if (search) {
      newConnection
        .where('c.descricao', 'like', `%${search}%`)
        .orWhere('c.placa', 'like', `%${search}%`)
    }

    return newConnection
      .innerJoin({ t: 'tipocarros' }, 'c.idtipocarro', 't.id')
      .leftJoin({ f: 'funcionarios' }, 'c.idfunfixo', 'f.id')
      .leftJoin({ p: 'pessoas' }, 'f.idpessoa', 'p.id')
      .orderBy(orderBy)
      .limit(limit)
      .offset(offset)
  }
}
