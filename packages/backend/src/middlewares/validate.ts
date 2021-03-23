import { NextFunction, Request, Response } from 'express'
import joi from 'joi'

const formatError = (error: joi.ValidationError) => {
  return {
    errors: error.details.map((detail) => {
      return {
        key: detail.context && detail.context.key,
        message: detail.message,
        detail,
      }
    }),
  }
}

interface Schema {
  query?: any
  params?: any
  body?: any
}

const validate = ({ query, params, body }: Schema) => (
  req: Request,
  res: Response,
  next: NextFunction
) => {
  if (params) {
    const paramsValidation = joi.object(params).validate(req.params)

    if (paramsValidation.error) {
      return res.status(400).json(formatError(paramsValidation.error))
    }
  }

  if (query) {
    const queryValidation = joi.object(query).validate(req.query)

    if (queryValidation.error) {
      return res.status(400).json(formatError(queryValidation.error))
    }
  }

  if (body) {
    const bodyValidation = joi.object(body).validate(req.body)

    if (bodyValidation.error) {
      return res.status(400).json(formatError(bodyValidation.error))
    }
  }

  next()
}

export default validate
