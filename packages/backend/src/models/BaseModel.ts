import { Knex } from 'knex'
import { getDbConnection } from '../app/database'
import NotFoundError from '../errors/NotFoundError'
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
  abstract canDelete(id: number): Promise<string | null>

  async create(payload: any) {
    const ids = await this.connection
      .insert(this.castPayloadToModel(payload))
      .into(this.tableName)

    return this.findById(ids[0])
  }

  async update(id: number, payload: any) {
    const affectedCount = await this.connection(this.tableName)
      .where({ id })
      .update(this.castPayloadToModel(payload))

    if (affectedCount === 0) {
      throw new NotFoundError('Tipo de carro não encontrado')
    }

    return this.findById(id)
  }

  async delete(id: number) {
    const error = await this.canDelete(id)
    if (error) {
      throw new BadRequestError(error)
    }

    return this.connection(this.tableName).where({ id }).del()
  }
}
