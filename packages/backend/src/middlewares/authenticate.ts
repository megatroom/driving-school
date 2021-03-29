import { Request, Response, NextFunction, SessionUser } from '../app/types'
import { validateToken } from '../app/security'

const authenticate = () => (
  req: Request,
  res: Response,
  next: NextFunction
) => {
  const authorization = req.get('authorization')
  let isAuthenticated = false

  if (authorization) {
    let decoded = null

    try {
      decoded = validateToken(authorization)
    } catch (e) {}

    if (decoded) {
      req.user = decoded as SessionUser
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
