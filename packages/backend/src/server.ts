import config from './app/config'
import logger from './app/logger'
import app from './app'

const PORT = config.port

app.listen(PORT, () => {
  logger.info(`Server started on http://localhost:${PORT}`)
  logger.debug(`Health check: http://localhost:${PORT}/api/healthcheck`)
})
