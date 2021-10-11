import joi from 'joi'

import BaseModel from './BaseModel'

function castStatusCodeToString(status: string) {
  switch (status) {
    case 'C':
      return 'Concluído'
    case 'A':
      return 'Ativo'
    default:
      return status
  }
}

function castPriorityCodeToString(priority: string) {
  switch (priority) {
    case '0':
      return 'Alta'
    case '1':
      return 'Normal'
    case '2':
      return 'Baixa'
    default:
      return priority
  }
}

export default class Notification extends BaseModel {
  constructor() {
    super('avisos')
  }

  static postSchema() {
    return {
      recipient: joi.number().required(),
      message: joi.string().required(),
      date: joi.date().iso().required(),
      priority: joi.string().required(),
      status: joi.string().required(),
    }
  }

  static labelsSchema() {
    return {
      recipientId: 'Destinatário',
      senderId: 'Remetente',
      message: 'Mensagem',
      date: 'Data',
      priority: 'Prioridade',
      status: 'Status',
    }
  }

  castPayloadToModel(payload: any) {
    return {
      iddestinatario: payload.recipientId,
      mensagem: payload.message,
      data: payload.date,
      prioridade: payload.priority,
      status: payload.status,
    }
  }

  castModelToResponse(model: any) {
    return {
      ...model,
      statusDesc: castStatusCodeToString(model.status),
      priorityDesc: castPriorityCodeToString(model.priority),
    }
  }

  async canDelete(id: number) {
    return null
  }

  async findAll(
    limit: number,
    offset: number,
    order: string[],
    orderDirection: string,
    search: string | undefined
  ) {
    const orderBy = order.reduce((accumulator: any[], field: string) => {
      switch (field) {
        case 'recipientName':
          return accumulator.concat({
            column: 'recipientName',
            order: orderDirection,
          })
        case 'senderName':
          return accumulator.concat({
            column: 'senderName',
            order: orderDirection,
          })
        case 'date':
          return accumulator.concat({ column: 'a.data', order: orderDirection })
        case 'status':
          return accumulator.concat({
            column: 'a.status',
            order: orderDirection,
          })
        case 'priority':
          return accumulator.concat({
            column: 'a.prioridade',
            order: orderDirection,
          })
        default:
          return accumulator
      }
    }, [])

    const selectConnection = this.connection
      .select(
        'a.id',
        'a.iddestinatario as recipientId',
        this.connection.raw('coalesce(dp.nome, du.nome) as recipientName'),
        'a.idremetente as senderId',
        this.connection.raw('coalesce(rp.nome, du.nome) as senderName'),
        'a.mensagem as message',
        'a.data as date',
        'a.prioridade as priority',
        'a.status'
      )
      .from({ a: this.tableName })
      .innerJoin({ du: 'usuarios' }, 'a.iddestinatario', 'du.id')
      .leftJoin({ df: 'funcionarios' }, 'du.idfuncionario', 'df.id')
      .leftJoin({ dp: 'pessoas' }, 'df.idpessoa', 'dp.id')
      .innerJoin({ ru: 'usuarios' }, 'a.idremetente', 'ru.id')
      .leftJoin({ rf: 'funcionarios' }, 'ru.idfuncionario', 'rf.id')
      .leftJoin({ rp: 'pessoas' }, 'rf.idpessoa', 'rp.id')

    const countConnection = this.connection(this.tableName).count('id as total')

    if (search) {
      selectConnection.where('a.mensagem', 'like', `%${search}%`)
      countConnection.where('a.mensagem', 'like', `%${search}%`)
    }

    selectConnection.orderBy(orderBy).limit(limit).offset(offset)

    const [countRes, data] = await Promise.all([
      countConnection,
      selectConnection,
    ])

    return {
      total: countRes[0].total,
      data: data.map(this.castModelToResponse),
    }
  }

  findById(id: number) {
    return this.connection
      .select(
        'a.id',
        'a.iddestinatario as recipientId',
        'coalesce(dp.nome, du.nome) as recipientName',
        'a.idremetente as senderId',
        'coalesce(rp.nome, du.nome) as senderName',
        'a.mensagem as message',
        'a.data as date',
        'a.prioridade as priority',
        'a.status'
      )
      .from({ a: this.tableName })
      .leftJoin({ du: 'usuarios' }, 'a.iddestinatario', 'du.id')
      .leftJoin({ df: 'funcionarios' }, 'du.idfuncionario', 'df.id')
      .leftJoin({ dp: 'pessoas' }, 'df.idpessoa', 'dp.id')
      .leftJoin({ ru: 'usuarios' }, 'a.idremetente', 'ru.id')
      .leftJoin({ rf: 'funcionarios' }, 'ru.idfuncionario', 'rf.id')
      .leftJoin({ rp: 'pessoas' }, 'rf.idpessoa', 'rp.id')
      .where({ 'a.id': id })
      .then((models) => (models.length ? models[0] : null))
  }
}
