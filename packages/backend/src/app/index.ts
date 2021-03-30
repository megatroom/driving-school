import express from 'express'
import cors from 'cors'
import morgan from 'morgan'

import { NextFunction, Request, Response } from './types'
import { initDbConnection } from './database'
import routes from '../routes'
import errors from './errors'

const isProduction = process.env.NODE_ENV === 'production'

initDbConnection()

const app = express()

app.use(express.json())
app.use(cors())
app.use(morgan(isProduction ? 'combined' : 'dev'))

app.use('/api', routes)

app.use((req: Request, res: Response, next: NextFunction) => {
  res.status(404).json({ message: 'Route not found' })
})

app.use(errors)

export default app
