import { Router } from 'express'
import 'joi-extract-type'

import authenticate from '../../middlewares/authenticate'
import User from '../../models/User'

const router = Router()

router.get('/users/current', authenticate(), async (req, res) => {
  const model = new User()
  const user = await model.findById(res.locals.user.id)
  const menu = await model.findAllPages()

  res.json({ user, menu })
})

export default router
