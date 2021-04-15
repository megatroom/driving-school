import joi from 'joi'
import BaseModel from './BaseModel'
import { dateStringToObject } from '../formatters/date'

export default class People extends BaseModel {
  constructor() {
    super('pessoas')
  }

  static labelsSchema() {
    return {
      name: 'Nome',
      dateOfBirth: 'Data de nascimento',
      gender: 'Sexo',
      rg: 'RG',
      rgPrintDate: 'RG Orgão emissor',
      rgEmittingOrgan: 'RG Data emissão',
      cpf: 'CPF',
      workCard: 'Cateira de Trabalho',
      address: 'Endereço',
      cep: 'CEP',
      neighborhood: 'Bairro',
      city: 'Cidade',
      state: 'Estado',
      phone: 'Telefone',
      phoneContact: 'Telefone Contato',
      phone2: 'Telefone 2',
      phone2Contact: 'Telefone 2 Contato',
      mobile: 'Celular',
      mobile2: 'Celular 2',
      mobile3: 'Celular 3',
      email: 'Email',
      mother: 'Mãe',
      father: 'Pai',
    }
  }

  static postSchema() {
    return {
      name: joi.string().required(),
      dateOfBirth: joi.date().allow(null),
      gender: joi.string().valid('M', 'F').allow(null),
      rg: joi.string().allow(null),
      rgPrintDate: joi.date().allow(null),
      rgEmittingOrgan: joi.string().allow(null),
      cpf: joi.string().allow(null),
      workCard: joi.string().allow(null),
      address: joi.string().allow(null),
      cep: joi.string().allow(null),
      neighborhood: joi.string().allow(null),
      city: joi.string().allow(null),
      state: joi.string().allow(null),
      phone: joi.string().allow(null),
      phoneContact: joi.string().allow(null),
      phone2: joi.string().allow(null),
      phone2Contact: joi.string().allow(null),
      mobile: joi.string().allow(null),
      mobile2: joi.string().allow(null),
      mobile3: joi.string().allow(null),
      email: joi
        .string()
        .email({ tlds: { allow: false } })
        .allow(null),
      mother: joi.string().allow(null),
      father: joi.string().allow(null),
    }
  }

  castPayloadToModel(payload: any) {
    return {
      nome: payload.name,
      dtnascimento: dateStringToObject(payload.dateOfBirth),
      sexo: payload.gender,
      rg: payload.rg,
      orgaoemissor: payload.rgEmittingOrgan,
      rgdataemissao: payload.rgPrintDate,
      cpf: payload.cpf,
      carteiradetrabalho: payload.workCard,
      endereco: payload.address,
      cep: payload.cep,
      bairro: payload.neighborhood,
      cidade: payload.city,
      estado: payload.state,
      telefone: payload.phone,
      celular: payload.mobile,
      email: payload.email,
      pai: payload.father,
      mae: payload.mother,
      telcontato: payload.phoneContact,
      telefone2: payload.phone2,
      tel2contato: payload.phone2Contact,
      celular2: payload.mobile2,
      celular3: payload.mobile3,
    }
  }

  findById(id: number) {
    return this.connection
      .select(
        'id',
        'nome as name',
        'dtnascimento as dateOfBirth',
        'sexo as gender',
        'rg',
        'cpf',
        'endereco as address',
        'cep',
        'bairro as neighborhood',
        'cidade as city',
        'estado as state',
        'pai as father',
        'mae as mother',
        'telefone as phone',
        'telcontato as phoneContact',
        'telefone2 as phone2',
        'tel2contato as phone2Contact',
        'celular as mobile',
        'celular2 as mobile2',
        'celular3 as mobile3',
        'email'
      )
      .from(this.tableName)
      .where({ id })
      .then((models) => (models.length ? models[0] : null))
  }
}
