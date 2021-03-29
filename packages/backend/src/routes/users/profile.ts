import { Router } from 'express'
import 'joi-extract-type'

import { Request, Response } from '../../app/types'
import authenticate from '../../middlewares/authenticate'
import User from '../../models/User'

const router = Router()

router.get(
  '/users/profile',
  authenticate(),
  async (req: Request, res: Response) => {
    if (!req.user) {
      return res.status(401).send()
    }

    const model = new User()
    const user = await model.findById(req.user.id)
    const menu = await model.findAllPages()

    res.json({ user, menu })
  }
)

export default router
