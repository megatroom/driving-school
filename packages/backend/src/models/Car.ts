import joi from 'joi'
import BaseModel from './BaseModel'
import { requiredForeignKey, optionalForeignKey } from '../validators/fields'

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
      year: joi.number().required(),
      modelYear: joi.number().required(),
      purchaseDate: joi.date().iso().required(),
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

  castPayloadToModel(model: any) {
    return {
      idtipocarro: model.carTypeId,
      idfunfixo: model.fixedEmployeeId,
      descricao: model.description,
      placa: model.licensePlate,
      ano: model.year,
      anomodelo: model.modelYear,
      datacompra: model.purchaseDate,
      datavenda: model.saleDate,
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

  findAll(limit: number, offset: number, order: string[]) {
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

    return this.connection
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
      .innerJoin({ t: 'tipocarros' }, 'c.idtipocarro', 't.id')
      .leftJoin({ f: 'funcionarios' }, 'c.idfunfixo', 'f.id')
      .leftJoin({ p: 'pessoas' }, 'f.idpessoa', 'p.id')
      .orderBy(orderBy)
      .limit(limit)
      .offset(offset)
  }
}
