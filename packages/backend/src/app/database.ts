import knex, { Knex } from "knex";
import config from "./config";
import logger from "./logger";

let singletonConnection: Knex;

export const initDbConnection = () => {
  const db = config.database;

  if (db.client === "sqlite") {
    singletonConnection = knex({
      client: "sqlite3",
      connection: {
        filename: db.database,
      },
    });
  } else {
    singletonConnection = knex({
      client: "mysql",
      connection: {
        host: db.host,
        port: db.port,
        user: db.user,
        password: db.password,
        database: db.database,
      },
    });
  }

  logger.info(`Database connected in ${db.client}`);
};

export const getDbConnection = () => {
  return singletonConnection;
};
