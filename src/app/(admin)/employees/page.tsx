'use client';

import { PlusButton } from '@/components/atoms/actions/PlusButton';
import { DataTable } from '@/components/molecules/DataTable';
import { PageHeading } from '@/components/molecules/PageHeading';
import { useDataTable } from '@/hooks/useDataTable';
import {
  DataTableRows,
  getDataTableInitialRowsState,
} from '@/models/datatable';
import { Employee } from '@/models/employees';
import { getAllEmployees } from '@/services/employees';
import { createColumnHelper } from '@tanstack/react-table';
import { useEffect, useState } from 'react';

const columnHelper = createColumnHelper<Employee>();

const columns = [
  columnHelper.accessor('registration', {
    cell: (info) => info.getValue(),
    header: 'Matrícula',
    size: 100,
  }),
  columnHelper.accessor('person.name', {
    cell: (info) => info.getValue(),
    header: 'Nome',
  }),
  columnHelper.accessor('person.phone', {
    cell: (info) => info.getValue(),
    header: 'Telefone',
  }),
  columnHelper.accessor('person.cellphone', {
    cell: (info) => info.getValue(),
    header: 'Telefone',
  }),
];

export default function EmployeesPage() {
  const { tableHandlers, tableState } = useDataTable<Employee>();
  const [dataTableState, setDataTableState] = useState<DataTableRows<Employee>>(
    getDataTableInitialRowsState<Employee>(),
  );

  useEffect(() => {
    getAllEmployees(tableState)
      .then((dataTableState) => {
        setDataTableState(dataTableState);
      })
      .catch(console.error);
  }, [tableState]);

  console.log(tableState);

  return (
    <>
      <PageHeading title="Funcionários">
        <PlusButton linkTo="/employee/form">Novo Funcionário</PlusButton>
      </PageHeading>
      <DataTable
        {...tableHandlers}
        {...tableState}
        columns={columns}
        dataTableState={dataTableState}
      />
    </>
  );
}
