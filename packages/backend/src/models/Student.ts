import joi from 'joi'
import { dateStringToObject } from '../formatters/date'
import BaseModel from './BaseModel'
import People from './People'

export default class Student extends BaseModel {
  constructor() {
    super('alunos')
  }

  static postSchema() {
    return {
      ...People.postSchema(),
      originId: joi.number().required(),
      enrollmentcfc: joi.string().allow(null),
      renach: joi.string().allow(null),
      observations: joi.string().allow(null),
      regcnh: joi.string().allow(null),
      currentCategory: joi.string().allow(null),
      processExpiration: joi.date().iso().allow(null),
      accessCode: joi.number().allow(null),
      noEmail: joi.string().valid('S', 'N').allow(null),
      gender: joi.string().valid('M', 'F').allow(null),
    }
  }

  static labelsSchema() {
    return {
      ...People.labelsSchema(),
      peopleId: 'Pessoa',
      originId: 'Origem',
      enrollment: 'Matrícula',
      enrollmentcfc: 'Matrícula CFC',
      renach: 'Renach',
      observations: 'Observações',
      regcnh: 'Nº Registro CNH',
      currentCategory: 'Categoria Atual',
      processExpiration: 'Validade Processo',
      accessCode: 'Código de acesso',
      noEmail: 'Não possui e-mail',
      dtcreate: 'Data Cadastro',
      dtupdate: 'Data Alteração',
      gender: 'Sexo',
    }
  }

  castPayloadToModel(payload: any) {
    return {
      idpessoa: payload.peopleId,
      idorigem: payload.originId,
      matriculacfc: payload.enrollmentcfc,
      renach: payload.renach,
      observacoes: payload.observations,
      regcnh: payload.regcnh,
      categoriaatual: payload.currentCategory,
      validadeprocesso: dateStringToObject(payload.processExpiration),
      codacess: payload.accessCode,
      noemail: payload.noEmail,
    }
  }

  async getEnrollment() {
    const models = await this.connection(this.tableName).max(
      'matricula as total'
    )
    return models.length ? parseInt(models[0].total, 10) + 1 : 1
  }

  async create(payload: any) {
    const enrollment = await this.getEnrollment()

    const people = await new People().create(payload)

    const currentDate = new Date()
    const model = {
      ...this.castPayloadToModel(payload),
      matricula: enrollment,
      idpessoa: people.id,
      dtcreate: currentDate,
      dtupdate: currentDate,
    }

    const ids = await this.connection.insert(model).into(this.tableName)

    return this.findById(ids[0])
  }

  async update(id: number, payload: any) {
    const { peopleId } = await this.connection
      .first('idpessoa as peopleId')
      .from(this.tableName)
      .where({ id })

    await new People().update(peopleId, payload)

    const model = {
      ...this.castPayloadToModel(payload),
      dtupdate: new Date(),
    }

    await this.connection(this.tableName).where({ id }).update(model)

    return this.findById(id)
  }

  async canDelete(id: number) {
    return null
  }

  findById(id: number) {
    return this.connection
      .select(
        'a.id',
        'a.idpessoa as peopleId',
        'a.idorigem as originId',
        'o.descricao as originDesc',
        'a.matricula as enrollment',
        'a.matriculacfc as enrollmentcfc',
        'a.renach',
        'a.observacoes as observations',
        'a.regcnh',
        'a.categoriaatual as currentCategory',
        'a.validadeprocesso as processExpiration',
        'a.codacess as accessCode',
        'a.noemail as noEmail',
        'p.nome as name',
        'p.dtnascimento as dateOfBirth',
        'p.sexo as gender',
        'p.rg',
        'p.orgaoemissor as rgEmittingOrgan',
        'p.rgdataemissao as rgPrintDate',
        'p.carteiradetrabalho as workCard',
        'p.pai as father',
        'p.mae as mother',
        'p.cpf',
        'p.endereco as address',
        'p.cep',
        'p.bairro as neighborhood',
        'p.cidade as city',
        'p.estado as state',
        'p.telefone as phone',
        'p.telcontato as phoneContact',
        'p.telefone2 as phone2',
        'p.tel2contato as phone2Contact',
        'p.celular as mobile',
        'p.celular2 as mobile2',
        'p.celular3 as mobile3',
        'p.email'
      )
      .from({ a: this.tableName })
      .innerJoin({ p: 'pessoas' }, 'a.idpessoa', 'p.id')
      .leftJoin({ o: 'origens' }, 'a.idorigem', 'o.id')
      .where({ 'a.id': id })
      .then((models) => (models.length ? models[0] : null))
  }

  findAll(
    limit: number,
    offset: number,
    order: string[],
    orderDirection: string,
    search: string | undefined
  ) {
    const orderBy = order.reduce((accumulator: any[], field: string) => {
      switch (field) {
        case 'enrollment':
          return accumulator.concat([
            { column: 'a.matricula', order: orderDirection },
          ])
        case 'enrollmentcfc':
          return accumulator.concat([
            { column: 'a.matriculacfc', order: orderDirection },
          ])
        case 'dtcreate':
          return accumulator.concat([
            { column: 'a.dtcreate', order: orderDirection },
          ])
        case 'name':
          return accumulator.concat([
            { column: 'p.nome', order: orderDirection },
          ])
        case 'cpf':
          return accumulator.concat([
            { column: 'p.cpf', order: orderDirection },
          ])
        default:
          return accumulator
      }
    }, [])

    const newConnection = this.connection
      .select(
        'a.id',
        'a.matricula as enrollment',
        'a.matriculacfc as enrollmentcfc',
        'a.dtcreate',
        'p.nome as name',
        'p.cpf'
      )
      .from({ a: this.tableName })
      .innerJoin({ p: 'pessoas' }, 'a.idpessoa', 'p.id')

    if (search) {
      newConnection
        .where('p.nome', 'like', `%${search}%`)
        .orWhere('p.cpf', 'like', `%${search}%`)
        .orWhere('p.telefone', 'like', `%${search}%`)
        .orWhere('p.celular', 'like', `%${search}%`)
    }

    return newConnection.orderBy(orderBy).limit(limit).offset(offset)
  }

  countByOriginId(originId: number) {
    return this.connection(this.tableName)
      .count('id as total')
      .where({ idorigem: originId })
      .then((models) => models[0].total)
  }
}
