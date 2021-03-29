import express from 'express'
import cors from 'cors'

import { NextFunction, Request, Response } from './types'
import { initDbConnection } from './database'
import routes from '../routes'
import logger from './logger'

initDbConnection()

const app = express()

app.use(express.json())
app.use(cors())

app.use('/api', routes)

app.use((req: Request, res: Response, next: NextFunction) => {
  res.status(404).json({ message: 'Route not found' })
})

app.use((err: any, req: Request, res: Response, next: NextFunction) => {
  logger.error(err)
  res.status(500).json({ message: 'Internal server error' })
})

export default app
