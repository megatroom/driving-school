import { Router } from "express";
import { createValidator } from "express-joi-validation";
import Joi from "joi";
import "joi-extract-type";

import {
  formatRequestPagination,
  paginationQuerySchema,
} from "../../schemas/pagination";
import CarType from "../../models/CarType";

const router = Router();
const validator = createValidator();

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
  "/cars/types",
  validator.query(Joi.object(paginationQuerySchema())),
  async (req, res, next) => {
    try {
      const { perPage, offset, order } = formatRequestPagination(req);

      const model = new CarType();
      const total = await model.count();
      const carTypes = await model.findAll(perPage, offset, order);

      res.json({ carTypes, total });
    } catch (error) {
      next(error);
    }
  }
);

export default router;
