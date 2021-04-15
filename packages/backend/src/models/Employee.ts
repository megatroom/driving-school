import joi from 'joi'
import { pluralize } from '../formatters/string'
import BaseModel from './BaseModel'
import Car from './Car'
import People from './People'

export default class Employee extends BaseModel {
  constructor() {
    super('funcionarios')
  }

  static postSchema() {
    return {
      ...People.postSchema(),
      employeeRoleId: joi.number().required(),
      status: joi.string().valid('A', 'I').required(),
    }
  }

  static labelsSchema() {
    return {
      ...People.labelsSchema(),
      employeeRoleId: 'Função',
      enrollment: 'Matrícula',
      status: 'Status',
    }
  }

  async getEnrollment() {
    const models = await this.connection(this.tableName).max(
      'matricula as total'
    )
    const maxEnrollment = models.length ? parseInt(models[0].total, 10) : 0
    return `${maxEnrollment + 1}`.padStart(4, '0')
  }

  async create(payload: any) {
    const enrollment = await this.getEnrollment()

    const people = await new People().create(payload)

    const model = {
      ...this.castPayloadToModel(payload),
      matricula: enrollment,
      idpessoa: people.id,
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

    await this.connection(this.tableName)
      .where({ id })
      .update(this.castPayloadToModel(payload))

    return this.findById(id)
  }

  castPayloadToModel(payload: any) {
    return {
      status: payload.status,
      idfuncao: payload.employeeRoleId,
    }
  }

  async canDelete(id: number) {
    const carCount = await new Car().countByFixedEmployeeId(id)
    if (carCount > 0) {
      return `Funcionário usado em ${pluralize(carCount as number, 'carro')}.`
    }

    return null
  }

  findById(id: number) {
    return this.connection
      .select(
        'f.id',
        'f.idfuncao as employeeRoleId',
        'c.descricao as employeeRoleDesc',
        'f.matricula as enrollment',
        'f.status',
        'p.nome as name',
        'p.dtnascimento as dateOfBirth',
        'p.rg',
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
      .from({ f: this.tableName })
      .innerJoin({ p: 'pessoas' }, 'f.idpessoa', 'p.id')
      .innerJoin({ c: 'funcoes' }, 'f.idfuncao', 'c.id')
      .where({ 'f.id': id })
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
        case 'enrollment':
          return accumulator.concat(['f.matricula'])
        case 'name':
          return accumulator.concat(['p.nome'])
        case 'phone':
          return accumulator.concat(['p.telefone'])
        case 'mobile':
          return accumulator.concat(['p.celular'])
        default:
          return accumulator
      }
    }, [])

    const newConnection = this.connection
      .select(
        'f.id',
        'f.matricula as enrollment',
        'p.nome as name',
        'p.telefone as phone',
        'p.celular as mobile'
      )
      .from({ f: this.tableName })
      .innerJoin({ p: 'pessoas' }, 'f.idpessoa', 'p.id')
      .innerJoin({ c: 'funcoes' }, 'f.idfuncao', 'c.id')

    if (search) {
      newConnection
        .where('p.nome', 'like', `%${search}%`)
        .orWhere('p.telefone', 'like', `%${search}%`)
        .orWhere('p.celular', 'like', `%${search}%`)
    }

    return newConnection.orderBy(orderBy).limit(limit).offset(offset)
  }

  countByRoleId(employeeRoleId: number) {
    return this.connection(this.tableName)
      .count('id as total')
      .where({ idfuncao: employeeRoleId })
      .then((models) => models[0].total)
  }
}
