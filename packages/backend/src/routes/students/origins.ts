import { Router } from 'express'
import 'joi-extract-type'

import {
  formatRequestPagination,
  paginationQuerySchema,
} from '../../schemas/pagination'
import { idSchema } from '../../schemas/core'
import authenticate from '../../middlewares/authenticate'
import validate from '../../middlewares/validate'
import Origin from '../../models/Origin'

const router = Router()

router.get(
  '/students/origins',
  authenticate(),
  validate({ query: paginationQuerySchema() }),
  async (req, res, next) => {
    try {
      const { perPage, offset, order, search } = formatRequestPagination(req)

      const model = new Origin()
      const total = await model.count()
      const data = await model.findAll(perPage, offset, order, search)

      res.json({ data, total })
    } catch (error) {
      next(error)
    }
  }
)

router.post(
  '/students/origins',
  validate({
    labels: Origin.labelsSchema(),
    body: Origin.postSchema(),
  }),
  async (req, res, next) => {
    try {
      const model = new Origin()
      const origins = await model.create(req.body)

      res.json(origins)
    } catch (error) {
      next(error)
    }
  }
)

router.get(
  '/students/origins/:id',
  validate({ params: idSchema() }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Origin()
      const origins = await model.findById(id)

      if (origins) {
        res.json(origins)
      } else {
        res.status(404).json({})
      }
    } catch (error) {
      next(error)
    }
  }
)

router.put(
  '/students/origins/:id',
  validate({
    labels: Origin.labelsSchema(),
    params: idSchema(),
    body: Origin.postSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Origin()
      const origins = await model.update(id, req.body)

      res.json(origins)
    } catch (error) {
      next(error)
    }
  }
)

router.delete(
  '/students/origins/:id',
  validate({
    params: idSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new Origin()
      await model.delete(id)

      res.json({})
    } catch (error) {
      next(error)
    }
  }
)

export default router
