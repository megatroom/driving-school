import { Router } from 'express'
import 'joi-extract-type'

import {
  formatRequestPagination,
  paginationQuerySchema,
} from '../../schemas/pagination'
import { idSchema } from '../../schemas/core'
import authenticate from '../../middlewares/authenticate'
import validate from '../../middlewares/validate'
import Student from '../../models/Student'

const router = Router()

router.get(
  '/students',
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

      const model = new Student()
      const total = await model.count()
      const data = await model.findAll(
        perPage,
        offset,
        order,
        orderDirection,
        search
      )

      res.json({ data, total })
    } catch (error) {
      next(error)
    }
  }
)

router.post(
  '/students',
  validate({
    labels: Student.labelsSchema(),
    body: Student.postSchema(),
  }),
  async (req, res, next) => {
    try {
      const model = new Student()
      const entity = await model.create(req.body)

      res.json(entity)
    } catch (error) {
      next(error)
    }
  }
)

router.get(
  '/students/:id',
  validate({ params: idSchema() }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Student()
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
  '/students/:id',
  validate({
    labels: Student.labelsSchema(),
    params: idSchema(),
    body: Student.postSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Student()
      const car = await model.update(id, req.body)

      res.json(car)
    } catch (error) {
      next(error)
    }
  }
)

router.delete(
  '/students/:id',
  validate({
    params: idSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Student()
      await model.delete(id)

      res.json({})
    } catch (error) {
      next(error)
    }
  }
)

export default router
