import joi from "joi";
import { Knex } from "knex";
// import validation from "../helpers/validation";
import connection from "./connection";

class CarType {
  connection: Knex;
  tableName: string;

  constructor() {
    this.connection = connection();
    this.tableName = "tipocarros";
  }

  // static postSchema() {
  //   return {
  //     description: joi.string().required().error(validation.validateError()),
  //     commission: joi.number().required().error(validation.validateError()),
  //   };
  // }

  // castModel(model) {
  //   return {
  //     descricao: model.description,
  //     comissao: model.commission,
  //   };
  // }

  // save(model) {
  //   return this.connection
  //     .insert(this.castModel(model))
  //     .into(this.tableName)
  //     .then((ids) => ids[0]);
  // }

  count() {
    return this.connection(this.tableName)
      .count("id as total")
      .then((models) => models[0].total);
  }

  findAll(limit: number, offset: number, order: string[]) {
    const orderBy = order.reduce((accumulator: string[], field: string) => {
      switch (field) {
        case "description":
          return accumulator.concat(["descricao"]);
        case "commission":
          return accumulator.concat(["comissao"]);
        default:
          return accumulator;
      }
    }, []);

    return this.connection
      .select("id", "descricao as description", "comissao as commission")
      .from(this.tableName)
      .orderBy(orderBy)
      .limit(limit)
      .offset(offset);
  }

  // findById(id) {
  //   return this.connection
  //     .select("id", "descricao as description", "comissao as commission")
  //     .from(this.tableName)
  //     .where({ id })
  //     .then((models) => (models.length ? models[0] : null));
  // }

  // delete(id) {
  //   return this.connection(this.tableName).where({ id }).del();
  // }

  // update(id, model) {
  //   return this.connection(this.tableName)
  //     .where({ id })
  //     .update(this.castModel(model));
  // }
}

export default CarType;
