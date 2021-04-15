import joi from 'joi'

export function idSchema() {
  return {
    id: joi.number().required(),
  }
}
