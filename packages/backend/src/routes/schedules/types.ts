import { Router } from 'express'
import 'joi-extract-type'

import {
  formatRequestPagination,
  paginationQuerySchema,
} from '../../schemas/pagination'
import { idSchema } from '../../schemas/core'
import authenticate from '../../middlewares/authenticate'
import validate from '../../middlewares/validate'
import SchedulingType from '../../models/SchedulingType'

const router = Router()

router.get(
  '/schedules/types',
  authenticate(),
  validate({ query: paginationQuerySchema() }),
  async (req, res, next) => {
    try {
      const { perPage, offset, order, search } = formatRequestPagination(req)

      const model = new SchedulingType()
      const total = await model.count()
      const data = await model.findAll(perPage, offset, order, search)

      res.json({ data, total })
    } catch (error) {
      next(error)
    }
  }
)

router.post(
  '/schedules/types',
  validate({
    labels: SchedulingType.labelsSchema(),
    body: SchedulingType.postSchema(),
  }),
  async (req, res, next) => {
    try {
      const model = new SchedulingType()
      const types = await model.create(req.body)

      res.json(types)
    } catch (error) {
      next(error)
    }
  }
)

router.get(
  '/schedules/types/:id',
  validate({ params: idSchema() }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new SchedulingType()
      const types = await model.findById(id)

      if (types) {
        res.json(types)
      } else {
        res.status(404).json({})
      }
    } catch (error) {
      next(error)
    }
  }
)

router.put(
  '/schedules/types/:id',
  validate({
    labels: SchedulingType.labelsSchema(),
    params: idSchema(),
    body: SchedulingType.postSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new SchedulingType()
      const types = await model.update(id, req.body)

      res.json(types)
    } catch (error) {
      next(error)
    }
  }
)

router.delete(
  '/schedules/types/:id',
  validate({
    params: idSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new SchedulingType()
      await model.delete(id)

      res.json({})
    } catch (error) {
      next(error)
    }
  }
)

export default router
