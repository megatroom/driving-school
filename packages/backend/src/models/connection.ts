import Knex from "knex";
import config from "../config";

const connection = () => {
  const db = config.database;
  let connection;

  if (db.client === "sqlite") {
    connection = Knex({
      client: "sqlite3",
      connection: {
        filename: db.database,
      },
    });
  } else {
    connection = Knex({
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

  return connection;
};

export default connection;
