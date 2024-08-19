'use client';

import { PlusButton } from '@/components/atoms/actions/PlusButton';
import { DataTable } from '@/components/molecules/DataTable';
import { PageHeading } from '@/components/molecules/PageHeading';
import { formatSimpleDate } from '@/helpers/dateTime';
import { Vouchers } from '@/models/vouchers';
import { getAllVouchers } from '@/services/vouchers';
import { createColumnHelper } from '@tanstack/react-table';
import { useEffect, useState } from 'react';

const columnHelper = createColumnHelper<Vouchers>();

const columns = [
  columnHelper.accessor('createdAt', {
    cell: (info) => formatSimpleDate(info.getValue()),
    header: 'Data',
  }),
  columnHelper.accessor('value', {
    cell: (info) => info.getValue(),
    header: 'Valor',
  }),
  columnHelper.accessor('employee', {
    cell: (info) => info.getValue().name,
    header: 'Funcionário',
  }),
];

export default function VouchersPage() {
  const [vouchers, setVouchers] = useState<Vouchers[]>([]);

  useEffect(() => {
    getAllVouchers()
      .then((data) => {
        setVouchers(data);
      })
      .catch(console.error);
  }, []);

  return (
    <>
      <PageHeading title="Vales">
        <PlusButton linkTo="/vouchers/form">Novo Vale</PlusButton>
      </PageHeading>
      <DataTable columns={columns} data={vouchers} />
    </>
  );
}
