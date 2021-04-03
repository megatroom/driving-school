import { Knex } from 'knex'
import { getDbConnection } from '../app/database'
import BadRequestError from '../errors/BadRequestError'

export default abstract class BaseModel {
  protected connection: Knex
  protected tableName: string

  constructor(tableName: string) {
    this.connection = getDbConnection()
    this.tableName = tableName
  }

  abstract castPayloadToModel(payload: any): any
  abstract findById(id: number): any

  async create(model: any) {
    const ids = await this.connection
      .insert(this.castPayloadToModel(model))
      .into(this.tableName)

    return this.findById(ids[0])
  }

  async update(id: number, payload: any) {
    const affectedCount = await this.connection(this.tableName)
      .where({ id })
      .update(this.castPayloadToModel(payload))

    if (affectedCount === 0) {
      throw new BadRequestError('Tipo de carro não encontrado')
    }

    return this.findById(id)
  }

  delete(id: number) {
    return this.connection(this.tableName).where({ id }).del()
  }

  count() {
    return this.connection(this.tableName)
      .count('id as total')
      .then((models) => models[0].total)
  }
}
