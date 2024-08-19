import mysql, {
  QueryResult,
  ResultSetHeader,
  RowDataPacket,
} from 'mysql2/promise';
import { getEnvVars } from '@/helpers/config';

interface MySQLClientProps {
  host?: string;
  user?: string;
  password?: string;
  database?: string;
}

type ExecuteResult = Pick<ResultSetHeader, 'affectedRows' | 'insertId'>;

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

  async query<TResult>(sql: string, values: any[]): Promise<TResult> {
    const connection = await this.pool.getConnection();

    const [rows] = await connection.query({ sql, values });

    connection.release();

    return rows as TResult;
  }

  async querySingleValue<TResult>(
    sql: string,
    values: any[],
  ): Promise<TResult> {
    const connection = await this.pool.getConnection();

    const [rows] = await connection.query({ sql, values });
    const typedRows = rows as unknown as any[];

    connection.release();

    if (typedRows.length !== 1) {
      throw new Error('The query returned a different record than one.');
    }

    return typedRows[0]['singleValue'] as TResult;
  }

  async execute(sql: string, values: any[]): Promise<ExecuteResult> {
    const connection = await this.pool.getConnection();

    const [result] = await connection.execute<ResultSetHeader>(sql, values);

    connection.release();

    return {
      insertId: result.insertId,
      affectedRows: result.affectedRows,
    };
  }

  async endConnection() {
    return this.pool.end();
  }
}

export const client = new MySQLClient(getEnvVars().database);
