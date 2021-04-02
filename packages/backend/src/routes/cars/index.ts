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
      const { perPage, offset, order } = formatRequestPagination(req)

      const model = new Car()
      const total = await model.count()
      const cars = await model.findAll(perPage, offset, order)

      res.json({ cars, total })
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
      const car = await model.create(req.body)

      res.json(car)
    } catch (error) {
      next(error)
    }
  }
)

router.get(
  '/cars/types/:id',
  validate({ params: idSchema() }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Car()
      const car = await model.findById(id)

      if (car) {
        res.json(car)
      } else {
        res.status(404).json({})
      }
    } catch (error) {
      next(error)
    }
  }
)

router.put(
  '/cars/types/:id',
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
  '/cars/types/:id',
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
