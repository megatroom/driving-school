import { Router } from 'express'
import 'joi-extract-type'

import {
  formatRequestPagination,
  paginationQuerySchema,
} from '../../schemas/pagination'
import { idSchema } from '../../schemas/core'
import authenticate from '../../middlewares/authenticate'
import validate from '../../middlewares/validate'
import Scheduling from '../../models/Scheduling'

const router = Router()

router.get(
  '/schedules',
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

      const model = new Scheduling()
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
  '/schedules',
  validate({
    labels: Scheduling.labelsSchema(),
    body: Scheduling.postSchema(),
  }),
  async (req, res, next) => {
    try {
      const model = new Scheduling()
      const schedules = await model.create(req.body)

      res.json(schedules)
    } catch (error) {
      next(error)
    }
  }
)

router.get(
  '/schedules/:id',
  validate({ params: idSchema() }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Scheduling()
      const schedules = await model.findById(id)

      if (schedules) {
        res.json(schedules)
      } else {
        res.status(404).json({})
      }
    } catch (error) {
      next(error)
    }
  }
)

router.put(
  '/schedules/:id',
  validate({
    labels: Scheduling.labelsSchema(),
    params: idSchema(),
    body: Scheduling.postSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Scheduling()
      const schedules = await model.update(id, req.body)

      res.json(schedules)
    } catch (error) {
      next(error)
    }
  }
)

router.delete(
  '/schedules/:id',
  validate({
    params: idSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Scheduling()
      await model.delete(id)

      res.json({})
    } catch (error) {
      next(error)
    }
  }
)

export default router
