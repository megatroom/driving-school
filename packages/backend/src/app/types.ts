import {
  Request as ExpressRequest,
  Response as ExpressResponse,
  NextFunction as ExpressNextFunction,
} from 'express'

/**
 * For security reasons, the token must have only the id,
 * since the token can be decrypted.
 * To retrieve user data use the profile route.
 */
export interface SessionUser {
  id: number
}

export interface Request extends ExpressRequest {
  user?: SessionUser
}

export interface Response extends ExpressResponse {}

export interface NextFunction extends ExpressNextFunction {}
