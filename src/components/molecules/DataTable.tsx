'use client';

import {
  Thead,
  Tbody,
  Tr,
  Th,
  Td,
  chakra,
  Card,
  Stack,
  Text,
  NumberInput,
  NumberInputField,
  NumberInputStepper,
  NumberIncrementStepper,
  NumberDecrementStepper,
} from '@chakra-ui/react';
import {
  ArrowBackIcon,
  ArrowForwardIcon,
  ArrowLeftIcon,
  ArrowRightIcon,
  TriangleDownIcon,
  TriangleUpIcon,
} from '@chakra-ui/icons';
import {
  useReactTable,
  flexRender,
  getCoreRowModel,
  ColumnDef,
  SortingState,
  PaginationState,
} from '@tanstack/react-table';
import { Table } from '../atoms/display/Table';
import { PAGE_BODY_SPACING } from '../constants';
import { Button, ButtonProps } from '../atoms/actions/Button';
import { DataTableRows } from '@/models/datatable';

export type DataTableProps<Data> = {
  onPaginationChange: (state: PaginationState) => void;
  onSortingChange: (state: SortingState) => void;
  columns: ColumnDef<Data, any>[];
  sorting: SortingState;
  pagination: PaginationState;
  dataTableState: DataTableRows<Data>;
};

function PaginationButton(props: ButtonProps) {
  return <Button colorScheme="gray" variant="outline" {...props} />;
}

export function DataTable<Data>({
  onPaginationChange,
  onSortingChange,
  columns,
  sorting,
  pagination,
  dataTableState,
}: DataTableProps<Data>) {
  const table = useReactTable({
    columns,
    data: dataTableState.rows,
    rowCount: dataTableState.rowCount,
    getCoreRowModel: getCoreRowModel(),
    onSortingChange: (updaterOrValue) => {
      onSortingChange(updaterOrValue as SortingState);
    },
    onPaginationChange: (updaterOrValue) => {
      onPaginationChange(updaterOrValue as PaginationState);
    },
    manualPagination: true,
    manualSorting: true,
    state: {
      sorting,
      pagination,
    },
  });

  return (
    <Card mb={PAGE_BODY_SPACING}>
      <Table>
        <Thead>
          {table.getHeaderGroups().map((headerGroup) => (
            <Tr key={headerGroup.id}>
              {headerGroup.headers.map((header) => {
                // see https://tanstack.com/table/v8/docs/api/core/column-def#meta to type this correctly
                const meta: any = header.column.columnDef.meta;
                return (
                  <Th
                    key={header.id}
                    onClick={header.column.getToggleSortingHandler()}
                    isNumeric={meta?.isNumeric}
                    width={`${header.getSize()}px`}
                  >
                    {flexRender(
                      header.column.columnDef.header,
                      header.getContext(),
                    )}

                    <chakra.span pl="4">
                      {header.column.getIsSorted() ? (
                        header.column.getIsSorted() === 'desc' ? (
                          <TriangleDownIcon aria-label="sorted descending" />
                        ) : (
                          <TriangleUpIcon aria-label="sorted ascending" />
                        )
                      ) : null}
                    </chakra.span>
                  </Th>
                );
              })}
            </Tr>
          ))}
        </Thead>
        <Tbody>
          {table.getRowModel().rows.map((row) => (
            <Tr key={row.id}>
              {row.getVisibleCells().map((cell) => {
                // see https://tanstack.com/table/v8/docs/api/core/column-def#meta to type this correctly
                const meta: any = cell.column.columnDef.meta;
                return (
                  <Td
                    key={cell.id}
                    isNumeric={meta?.isNumeric}
                    width={`${cell.column.getSize()}px`}
                  >
                    {flexRender(cell.column.columnDef.cell, cell.getContext())}
                  </Td>
                );
              })}
            </Tr>
          ))}
        </Tbody>
      </Table>
      <Stack direction="row" spacing={4} m={4}>
        <PaginationButton
          leftIcon={<ArrowLeftIcon />}
          onClick={() => table.firstPage()}
          disabled={!table.getCanPreviousPage()}
        >
          Início
        </PaginationButton>
        <PaginationButton
          leftIcon={<ArrowBackIcon />}
          onClick={() => table.previousPage()}
          disabled={!table.getCanPreviousPage()}
        >
          Voltar
        </PaginationButton>
        <Stack direction="row" alignItems="center">
          <Text>Página</Text>
          <NumberInput
            maxW="80px"
            min={1}
            max={table.getPageCount()}
            value={table.getState().pagination.pageIndex + 1}
            onChange={(valueAsString: string, valueAsNumber: number) => {
              const page = valueAsString ? valueAsNumber - 1 : 0;
              table.setPageIndex(page);
            }}
          >
            <NumberInputField />
            <NumberInputStepper>
              <NumberIncrementStepper />
              <NumberDecrementStepper />
            </NumberInputStepper>
          </NumberInput>
          <NumberInput value={table.getState().pagination.pageIndex + 1} />
          <Text>de</Text>
          <Text>{table.getPageCount().toLocaleString()}</Text>
        </Stack>
        <PaginationButton
          rightIcon={<ArrowForwardIcon />}
          onClick={() => table.nextPage()}
          disabled={!table.getCanNextPage()}
        >
          Avançar
        </PaginationButton>
        <PaginationButton
          rightIcon={<ArrowRightIcon />}
          onClick={() => table.lastPage()}
          disabled={!table.getCanNextPage()}
        >
          Fim
        </PaginationButton>
      </Stack>
    </Card>
  );
}
