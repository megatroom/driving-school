import { Router } from 'express'
import joi from 'joi'
import 'joi-extract-type'

import validate from '../../middlewares/validate'
import User from '../../models/User'
import { generateToken } from '../../app/security'

const router = Router()

router.post(
  '/users/signin',
  validate({
    body: {
      login: joi.string().required(),
      password: joi.string().required(),
    },
  }),
  async (req, res) => {
    try {
      const { login, password } = req.body
      const model = new User()
      const userId = await model.findIdByLogin(login, password)

      if (userId) {
        const token = generateToken({ id: userId })
        res.json({ token })
      } else {
        res.status(401).json({})
      }
    } catch (error) {
      res.status(401).json({})
    }
  }
)

export default router
