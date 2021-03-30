import { NextFunction, Request, Response } from 'express'
import joi from 'joi'

const formatError = (error: joi.ValidationError, labels?: Labels) => {
    return {
        errors: error.details.map((detail) => {
            let key = detail.context && detail.context.key
            let message = detail.message

            if (!key && detail.path && detail.path.length) {
                key = detail.path.join('.')
            }

            const label = (labels && labels[key || '']) || key || ''

            if (detail.type === 'any.required') {
                message = `"${label}" é obrigatório`
            }

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
    if (params) {
        const paramsValidation = joi.object(params).validate(req.params)

        if (paramsValidation.error) {
            return res
                .status(400)
                .json(formatError(paramsValidation.error, labels))
        }
    }

    if (query) {
        const queryValidation = joi.object(query).validate(req.query)

        if (queryValidation.error) {
            return res
                .status(400)
                .json(formatError(queryValidation.error, labels))
        }
    }

    if (body) {
        const bodyValidation = joi.object(body).validate(req.body)

        if (bodyValidation.error) {
            return res
                .status(400)
                .json(formatError(bodyValidation.error, labels))
        }
    }

    next()
}

export default validate
