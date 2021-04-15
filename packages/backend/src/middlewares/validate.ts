import { NextFunction, Request, Response } from 'express'
import joi, { ValidationErrorItem } from 'joi'

const translateErrorMessage = (detail: ValidationErrorItem, label: string) => {
  switch (detail.type) {
    case 'any.required':
      return `"${label}" é obrigatório`
    case 'string.base':
    case 'string.empty':
    case 'number.base':
    case 'date.base':
      return `"${label}" não pode ficar em branco`
    case 'string.email':
      return `"${label}" deve ser um email válido`
    default:
      return detail.message
  }
}

const formatError = (error: joi.ValidationError, labels?: Labels) => {
  return {
    errors: error.details.map((detail) => {
      let key = detail.context && detail.context.key

      if (!key && detail.path && detail.path.length) {
        key = detail.path.join('.')
      }

      const label = (labels && labels[key || '']) || key || ''
      const message = translateErrorMessage(detail, label)

      return {
        key,
        label,
        message,
        detail,
      }
    }),
  }
}

type Labels = Record<string, string>

interface Schema {
  labels?: Labels
  query?: any
  params?: any
  body?: any
}

const validate = ({ labels, query, params, body }: Schema) => (
  req: Request,
  res: Response,
  next: NextFunction
) => {
  const options = {
    abortEarly: false,
  }

  if (params) {
    const paramsValidation = joi.object(params).validate(req.params, options)

    if (paramsValidation.error) {
      return res.status(400).json(formatError(paramsValidation.error, labels))
    }
  }

  if (query) {
    const queryValidation = joi.object(query).validate(req.query, options)

    if (queryValidation.error) {
      return res.status(400).json(formatError(queryValidation.error, labels))
    }
  }

  if (body) {
    const bodyValidation = joi.object(body).validate(req.body, options)

    if (bodyValidation.error) {
      return res.status(400).json(formatError(bodyValidation.error, labels))
    }
  }

  next()
}

export default validate
