import { Router } from 'express'
import 'joi-extract-type'

import {
  formatRequestPagination,
  paginationQuerySchema,
} from '../../schemas/pagination'
import { idSchema } from '../../schemas/core'
import authenticate from '../../middlewares/authenticate'
import validate from '../../middlewares/validate'
import Notification from '../../models/Notification'

const router = Router()

router.get(
  '/notifications',
  authenticate(),
  validate({
    query: {
      ...paginationQuerySchema(),
    },
  }),
  async (req, res, next) => {
    try {
      const {
        perPage,
        offset,
        order,
        orderDirection,
        search,
      } = formatRequestPagination(req)

      const model = new Notification()
      const all = await model.findAll(
        perPage,
        offset,
        order,
        orderDirection,
        search
      )
      // console.log(all)
      res.json(all)
    } catch (error) {
      next(error)
    }
  }
)

router.post(
  '/notifications',
  validate({
    labels: Notification.labelsSchema(),
    body: Notification.postSchema(),
  }),
  async (req, res, next) => {
    try {
      const model = new Notification()
      const schedules = await model.create(req.body)

      res.json(schedules)
    } catch (error) {
      next(error)
    }
  }
)

router.get(
  '/notifications/:id',
  validate({ params: idSchema() }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Notification()
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
  '/notifications/:id',
  validate({
    labels: Notification.labelsSchema(),
    params: idSchema(),
    body: Notification.postSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Notification()
      const schedules = await model.update(id, req.body)

      res.json(schedules)
    } catch (error) {
      next(error)
    }
  }
)

router.delete(
  '/notifications/:id',
  validate({
    params: idSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Notification()
      await model.delete(id)

      res.json({})
    } catch (error) {
      next(error)
    }
  }
)

export default router
