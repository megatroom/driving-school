import { config } from "dotenv";

config();

export default {
  port: process.env.PORT,
  database: {
    client: process.env.DB_CLIENT,
    database: process.env.DB_DATABASE || "",
    host: process.env.DB_HOST,
    port: parseInt(process.env.DB_PORT || "0", 10),
    user: process.env.DB_USER,
    password: process.env.DB_PWD,
  },
};
