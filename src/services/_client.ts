import mysql, { QueryResult } from 'mysql2/promise';
import { getEnvVars } from '@/helpers/config';

interface MySQLClientProps {
  host?: string;
  user?: string;
  password?: string;
  database?: string;
}

class MySQLClient {
  pool: mysql.Pool;

  constructor({ host, user, password, database }: MySQLClientProps) {
    this.validateProp('host', host);
    this.validateProp('user', user);
    this.validateProp('password', database);

    this.pool = mysql.createPool({
      host,
      user,
      password,
      database,
      waitForConnections: true,
      connectionLimit: 10,
      maxIdle: 10,
      idleTimeout: 60000,
      queueLimit: 0,
      enableKeepAlive: true,
      keepAliveInitialDelay: 0,
    });
  }

  validateProp(key: string, value?: string) {
    if (!value) {
      throw new Error(`MySQL config prop not defined: ${key}`);
    }
  }

  async query<TResult extends QueryResult>(
    sql: string,
    values: any[],
  ): Promise<TResult> {
    const connection = await this.pool.getConnection();

    const [rows] = await connection.query<TResult>({ sql, values });

    connection.release();

    return rows;
  }

  async endConnection() {
    return this.pool.end();
  }
}

export const client = new MySQLClient(getEnvVars().database);
