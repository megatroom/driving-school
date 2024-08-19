import { PaginationState, SortingState } from '@tanstack/react-table';

export interface DataTableRows<Model> {
  rows: Model[];
  rowCount: number;
}

export interface DataTableQueryParam {
  pagination: PaginationState;
  sorting: SortingState;
}

export function getDataTableInitialRowsState<Model>(): DataTableRows<Model> {
  return {
    rows: [],
    rowCount: 0,
  };
}

export function buildLimit({ pageIndex, pageSize }: PaginationState) {
  return `LIMIT ${pageSize} OFFSET ${pageIndex * pageSize}`;
}

export function buildOrderBy(
  sorting: SortingState,
  castIdToField: (id: string) => string,
): string {
  if (!sorting || sorting.length === 0) {
    return '';
  }

  const result = sorting.map(({ id, desc }) => {
    return `${castIdToField(id)} ${desc ? 'DESC' : 'ASC'}`;
  });

  return `ORDER BY ${result.join(', ')}`;
}
