import { config } from 'dotenv'
import path from 'path'
import fs from 'fs'

const getEnvPath = () => {
  let envPath = path.resolve(process.cwd(), `.env.${process.env.NODE_ENV}`)

  if (fs.existsSync(envPath)) {
    return envPath
  }

  return path.resolve(process.cwd(), '.env')
}

const result = config({ path: getEnvPath() })

if (result.error) {
  throw result.error
}

export default {
  env: process.env.NODE_ENV,
  port: process.env.PORT,
  jwtSecret: process.env.JWT_SECRET || '',
  database: {
    client: process.env.DB_CLIENT,
    database: process.env.DB_DATABASE || '',
    host: process.env.DB_HOST,
    port: parseInt(process.env.DB_PORT || '0', 10),
    user: process.env.DB_USER,
    password: process.env.DB_PWD,
  },
}
