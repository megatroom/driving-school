import { Router } from 'express'
import 'joi-extract-type'

import {
  formatRequestPagination,
  paginationQuerySchema,
} from '../../schemas/pagination'
import { idSchema } from '../../schemas/core'
import authenticate from '../../middlewares/authenticate'
import validate from '../../middlewares/validate'
import Car from '../../models/Car'

const router = Router()

router.get(
  '/cars',
  authenticate(),
  validate({ query: paginationQuerySchema() }),
  async (req, res, next) => {
    try {
      const { perPage, offset, order, search } = formatRequestPagination(req)

      const model = new Car()
      const total = await model.count()
      const data = await model.findAll(perPage, offset, order, search)

      res.json({ data, total })
    } catch (error) {
      next(error)
    }
  }
)

router.post(
  '/cars',
  validate({
    labels: Car.labelsSchema(),
    body: Car.postSchema(),
  }),
  async (req, res, next) => {
    try {
      const model = new Car()
      const entity = await model.create(req.body)

      res.json(entity)
    } catch (error) {
      next(error)
    }
  }
)

router.get(
  '/cars/:id',
  validate({ params: idSchema() }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Car()
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
  '/cars/:id',
  validate({
    labels: Car.labelsSchema(),
    params: idSchema(),
    body: Car.postSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Car()
      const car = await model.update(id, req.body)

      res.json(car)
    } catch (error) {
      next(error)
    }
  }
)

router.delete(
  '/cars/:id',
  validate({
    params: idSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Car()
      await model.delete(id)

      res.json({})
    } catch (error) {
      next(error)
    }
  }
)

export default router
