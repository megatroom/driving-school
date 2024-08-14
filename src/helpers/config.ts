export const getEnvVars = () => ({
  auth: {
    secretKey: process.env.SESSION_SECRET,
  },
  unplash: {
    accessKey: process.env.UNPLASH_ACCESS_KEY,
  },
  database: {
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASS,
    database: process.env.DB_DATABASE,
  },
});
