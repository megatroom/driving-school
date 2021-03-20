import { Request, Response } from "express";
import express from "express";
import cors from "cors";
import bodyParser from "body-parser";

import carsTypesRouter from "./routes/cars/types";

const app = express();

app.use(bodyParser.json());
app.use(cors());

app.use("/api", carsTypesRouter);

app.get("/", function (req: Request, res: Response) {
  res.send("hello world");
});

export default app;
