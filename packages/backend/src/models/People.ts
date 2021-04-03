import joi from 'joi'
import BaseModel from './BaseModel'

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
      postalCode: 'CEP',
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
      dateOfBirth: joi.date(),
      gender: joi.string().valid('M', 'F'),
      rg: joi.string(),
      rgPrintDate: joi.date(),
      rgEmittingOrgan: joi.string(),
      cpf: joi.string(),
      workCard: joi.string(),
      address: joi.string(),
      postalCode: joi.string(),
      neighborhood: joi.string(),
      city: joi.string(),
      state: joi.string(),
      phone: joi.string(),
      phoneContact: joi.string(),
      phone2: joi.string(),
      phone2Contact: joi.string(),
      mobile: joi.string(),
      mobile2: joi.string(),
      mobile3: joi.string(),
      email: joi.string(),
      mother: joi.string(),
      father: joi.string(),
    }
  }

  castPayloadToModel(payload: any) {
    return {
      nome: payload.name,
      dtnascimento: payload.dateOfBirth,
      sexo: payload.gender,
      rg: payload.rg,
      orgaoemissor: payload.rgEmittingOrgan,
      rgdataemissao: payload.rgPrintDate,
      cpf: payload.cpf,
      carteiradetrabalho: payload.workCard,
      endereco: payload.address,
      cep: payload.postalCode,
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
    throw new Error('Method not implemented.')
  }
}
