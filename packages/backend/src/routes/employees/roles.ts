import { Router } from 'express'
import 'joi-extract-type'

import {
  formatRequestPagination,
  paginationQuerySchema,
} from '../../schemas/pagination'
import { idSchema } from '../../schemas/core'
import authenticate from '../../middlewares/authenticate'
import validate from '../../middlewares/validate'
import EmployeeRole from '../../models/EmployeeRole'

const router = Router()

router.get(
  '/employees/roles',
  authenticate(),
  validate({ query: paginationQuerySchema() }),
  async (req, res, next) => {
    try {
      const { perPage, offset, order, search } = formatRequestPagination(req)

      const model = new EmployeeRole()

      res.json(await model.findAll(perPage, offset, order, search))
    } catch (error) {
      next(error)
    }
  }
)

router.post(
  '/employees/roles',
  validate({
    labels: EmployeeRole.labelsSchema(),
    body: EmployeeRole.postSchema(),
  }),
  async (req, res, next) => {
    try {
      const model = new EmployeeRole()
      const roles = await model.create(req.body)

      res.json(roles)
    } catch (error) {
      next(error)
    }
  }
)

router.get(
  '/employees/roles/:id',
  validate({ params: idSchema() }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new EmployeeRole()
      const roles = await model.findById(id)

      if (roles) {
        res.json(roles)
      } else {
        res.status(404).json({})
      }
    } catch (error) {
      next(error)
    }
  }
)

router.put(
  '/employees/roles/:id',
  validate({
    labels: EmployeeRole.labelsSchema(),
    params: idSchema(),
    body: EmployeeRole.postSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new EmployeeRole()
      const roles = await model.update(id, req.body)

      res.json(roles)
    } catch (error) {
      next(error)
    }
  }
)

router.delete(
  '/employees/roles/:id',
  validate({
    params: idSchema(),
  }),
  async (req, res, next) => {
    try {
      const id = parseInt(req.params.id, 10)

      const model = new EmployeeRole()
      await model.delete(id)

      res.json({})
    } catch (error) {
      next(error)
    }
  }
)

export default router
