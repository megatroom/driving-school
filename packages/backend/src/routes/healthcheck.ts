import { Router } from 'express'
import { getDbConnection } from '../app/database'

const router = Router()

router.get('/healthcheck', async (req, res) => {
  let database

  try {
    const dbConnect = getDbConnection()
    await dbConnect.raw('select 1')
    database = true
  } catch (err) {
    database = false
  }

  res.status(200).json({
    database,
  })
})

export default router
