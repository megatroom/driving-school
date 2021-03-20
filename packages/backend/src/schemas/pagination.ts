import { Request } from "express";
import joi from "joi";

export function formatRequestPagination(req: Request) {
  const page = parseInt(req.query.page as string, 10);
  const perPage = parseInt(req.query.perPage as string, 10);
  const offset = (page - 1) * perPage;
  const order = (req.query.order as string).split(",");

  return { page, perPage, offset, order };
}

export function paginationQuerySchema() {
  return {
    page: joi.number().min(1).required(),
    perPage: joi.number().min(1).required(),
    order: joi.string().required(),
  };
}
