import { Router } from 'express'
import 'joi-extract-type'

import {
    formatRequestPagination,
    paginationQuerySchema,
} from '../../schemas/pagination'
import { idSchema } from '../../schemas/core'
import authenticate from '../../middlewares/authenticate'
import validate from '../../middlewares/validate'
import CarType from '../../models/CarType'

const router = Router()

router.get(
    '/cars/types',
    authenticate(),
    validate({ query: paginationQuerySchema() }),
    async (req, res, next) => {
        try {
            const { perPage, offset, order } = formatRequestPagination(req)

            const model = new CarType()
            const total = await model.count()
            const carTypes = await model.findAll(perPage, offset, order)

            res.json({ carTypes, total })
        } catch (error) {
            next(error)
        }
    }
)

router.post(
    '/cars/types',
    validate({
        labels: CarType.labelsSchema(),
        body: CarType.postSchema(),
    }),
    async (req, res, next) => {
        try {
            const model = new CarType()
            const carType = await model.create(req.body)

            res.json(carType)
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

            const model = new CarType()
            const carType = await model.findById(id)

            if (carType) {
                res.json(carType)
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
        labels: CarType.labelsSchema(),
        params: idSchema(),
        body: CarType.postSchema(),
    }),
    async (req, res, next) => {
        try {
            const id = parseInt(req.params.id, 10)

            const model = new CarType()
            const carType = await model.update(id, req.body)

            res.json(carType)
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

            const model = new CarType()
            await model.delete(id)

            res.json({})
        } catch (error) {
            next(error)
        }
    }
)

export default router
