import { Router } from 'express'
import 'joi-extract-type'

import {
  formatRequestPagination,
  paginationQuerySchema,
} from '../../schemas/pagination'
import { idSchema } from '../../schemas/core'
import authenticate from '../../middlewares/authenticate'
import validate from '../../middlewares/validate'
import User from '../../models/User'

const router = Router()

router.get(
  '/users',
  authenticate(),
  validate({ query: paginationQuerySchema() }),
  async (req, res, next) => {
    try {
      const {
        perPage,
        offset,
        order,
        orderDirection,
        search,
      } = formatRequestPagination(req)

      const model = new User()

      res.json(
        await model.findAll(perPage, offset, order, orderDirection, search)
      )
    } catch (error) {
      next(error)
    }
  }
)

router.post(
  '/users',
  validate({
    labels: User.labelsSchema(),
    body: User.postSchema(),
  }),
  async (req, res, next) => {
    try {
      console.log('Opa', req.body)
      const model = new User()
      const entity = await model.create(req.body)

      res.json(entity)
    } catch (error) {
      next(error)
    }
  }
)

router.get(
  '/users/:id',
  validate({ params: idSchema() }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new User()
      const entity = await model.findById(id)

      if (entity) {
        res.json(entity)
      } else {
        res.status(404).json({})
      }
    } catch (error) {
      next(error)
    }
  }
)

router.put(
  '/users/:id',
  validate({
    labels: User.labelsSchema(),
    params: idSchema(),
    body: User.postSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new User()
      const user = await model.update(id, req.body)

      res.json(user)
    } catch (error) {
      next(error)
    }
  }
)

router.delete(
  '/users/:id',
  validate({
    params: idSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new User()
      await model.delete(id)

      res.json({})
    } catch (error) {
      next(error)
    }
  }
)

export default router
