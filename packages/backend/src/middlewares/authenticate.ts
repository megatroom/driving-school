import { NextFunction, Request, Response } from 'express'
import { validateToken } from '../app/security'

const authenticate = () => (
  req: Request,
  res: Response,
  next: NextFunction
) => {
  const authorization = req.get('authorization')
  let isAuthenticated = false

  if (authorization) {
    let data = null

    try {
      data = validateToken(authorization)
    } catch (e) {}

    if (data) {
      res.locals.user = data
      isAuthenticated = true
    }
  }

  if (isAuthenticated) {
    next()
  } else {
    res.status(401).json({})
  }
}

export default authenticate
