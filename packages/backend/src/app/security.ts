import crypto from 'crypto'
import jwt from 'jsonwebtoken'

import config from './config'

export function encryptPassword(pwd: string) {
  return crypto.createHash('md5').update(pwd).digest('hex')
}

export function generateToken(data: object) {
  return jwt.sign(data, config.jwtSecret)
}

export function validateToken(token: string) {
  return jwt.verify(token, config.jwtSecret)
}
