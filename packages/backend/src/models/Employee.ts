import joi from 'joi'
import BaseModel from './BaseModel'
import People from './People'

export default class Employee extends BaseModel {
  constructor() {
    super('funcionarios')
  }

  static postSchema() {
    return {
      ...People.postSchema(),
      employeeRoleId: joi.number().required(),
      status: joi.string().valid('A', 'I'),
    }
  }

  static labelsSchema() {
    return {
      ...People.labelsSchema(),
      enrollment: 'Matrícula',
      status: 'Status',
    }
  }

  castPayloadToModel(payload: any) {
    return {
      ...new People().castPayloadToModel(payload),
      status: payload.status,
    }
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
        'p.cep as postalCode',
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
}
