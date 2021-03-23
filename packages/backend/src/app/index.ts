import express, { NextFunction, Request, Response } from 'express'
import cors from 'cors'

import routes from '../routes'
import logger from './logger'
import { initDbConnection } from './database'

initDbConnection()

const app = express()

app.use(express.json())
app.use(cors())

app.use('/api', routes)

app.use((req: Request, res: Response, next: NextFunction) => {
  res.status(404).send("Sorry can't find that!")
})

app.use((err: any, req: Request, res: Response, next: NextFunction) => {
  logger.error(err)
  res.status(500).end('internal server error')
})

export default app
