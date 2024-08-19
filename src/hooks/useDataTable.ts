import { DataTableProps } from '@/components/molecules/DataTable';
import { PaginationState, SortingState } from '@tanstack/react-table';
import { useMemo, useState } from 'react';

type DataTableResult<Model> = {
  tableHandlers: Pick<
    DataTableProps<Model>,
    'onPaginationChange' | 'onSortingChange'
  >;
  tableState: Pick<DataTableProps<Model>, 'pagination' | 'sorting'>;
};

export function useDataTable<Model>(): DataTableResult<Model> {
  const [pagination, onPaginationChange] = useState<PaginationState>({
    pageIndex: 0,
    pageSize: 10,
  });
  const [sorting, onSortingChange] = useState<SortingState>([]);

  return useMemo(
    () => ({
      tableHandlers: {
        onPaginationChange,
        onSortingChange,
      },
      tableState: {
        pagination,
        sorting,
      },
    }),
    [onPaginationChange, onSortingChange, pagination, sorting],
  );
}
