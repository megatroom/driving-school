import { NextFunction, Request, Response } from './types'
import HttpError from '../errors/HttpError'
import logger from './logger'

const ErrorHandler = (
  err: any,
  req: Request,
  res: Response,
  next: NextFunction
) => {
  if (err instanceof HttpError) {
    return res.status(err.statusCode).json({ message: err.message })
  }

  logger.error(err)

  res.status(500).json({ message: 'Internal server error' })
}

export default ErrorHandler
