import { Router } from 'express'
import joi from 'joi'
import 'joi-extract-type'

import {
  formatRequestPagination,
  paginationQuerySchema,
} from '../../schemas/pagination'
import authenticate from '../../middlewares/authenticate'
import validate from '../../middlewares/validate'
import CarType from '../../models/CarType'

const router = Router()

// router.get(
//   "/cars/types/:id",
//   validator.params(Joi.object({ id: Joi.number().required() })),
//   async (req, res, next) => {
//     try {
//       const model = new CarType();
//       const carType = await model.findById(req.params.id);

//       if (carType) {
//         res.json({ carType });
//       } else {
//         res.status(404).json({});
//       }
//     } catch (error) {
//       next(error);
//     }
//   }
// );

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

export default router
