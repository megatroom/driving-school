'use server';

import { Vouchers } from '@/models/vouchers';
import { client } from './_client';

type VoucherQueryResult = {
  id: number;
  idfuncionario: number;
  data: Date;
  valor: number;
  motivo: string;
};

export async function getAllVouchers(): Promise<Vouchers[]> {
  const sql = `
select a.id, a.idfuncionario, a.data, a.valor, a.motivo
from vales a
order by a.data
limit 5
`;

  const rows = (await client.query(sql, [])) as VoucherQueryResult[];

  return rows.map<Vouchers>((row) => ({
    id: row.id,
    value: row.valor,
    reason: row.motivo,
    createdAt: row.data,
    employee: {
      id: row.idfuncionario,
      name: '',
    },
  }));
}
