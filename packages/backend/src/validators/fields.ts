import Joi from 'joi'

export const requiredForeignKey: Joi.CustomValidator = (value, helpers) => {
  if (!value || value === -1) {
    return helpers.error('string.empty')
  }

  return value
}

export const optionalForeignKey: Joi.CustomValidator = (value, helpers) => {
  if (!value || value === -1) {
    return null
  }

  return value
}
