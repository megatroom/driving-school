import winston from 'winston'
import config from '../app/config'

const logger = winston.createLogger({
  level: config.log.level,
  format: winston.format.json(),
  defaultMeta: {},
  transports: [
    new winston.transports.Console({
      format: winston.format.simple(),
    }),
  ],
})

export default logger
