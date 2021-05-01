import { Router } from 'express'
import 'joi-extract-type'

import {
  formatRequestPagination,
  paginationQuerySchema,
} from '../../schemas/pagination'
import { idSchema } from '../../schemas/core'
import authenticate from '../../middlewares/authenticate'
import validate from '../../middlewares/validate'
import Employee from '../../models/Employee'

const router = Router()

router.get(
  '/employees',
  authenticate(),
  validate({ query: paginationQuerySchema() }),
  async (req, res, next) => {
    try {
      const { perPage, offset, order, search } = formatRequestPagination(req)

      const model = new Employee()

      res.json(await model.findAll(perPage, offset, order, search))
    } catch (error) {
      next(error)
    }
  }
)

router.post(
  '/employees',
  validate({
    labels: Employee.labelsSchema(),
    body: Employee.postSchema(),
  }),
  async (req, res, next) => {
    try {
      const model = new Employee()
      const entity = await model.create(req.body)

      res.json(entity)
    } catch (error) {
      next(error)
    }
  }
)

router.get(
  '/employees/:id',
  validate({ params: idSchema() }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Employee()
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
  '/employees/:id',
  validate({
    labels: Employee.labelsSchema(),
    params: idSchema(),
    body: Employee.postSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Employee()
      const car = await model.update(id, req.body)

      res.json(car)
    } catch (error) {
      next(error)
    }
  }
)

router.delete(
  '/employees/:id',
  validate({
    params: idSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Employee()
      await model.delete(id)

      res.json({})
    } catch (error) {
      next(error)
    }
  }
)

export default router
