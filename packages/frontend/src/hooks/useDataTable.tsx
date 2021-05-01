import { useState } from 'react'
import { useQueryClient, useQuery } from 'react-query'

import { Pagination } from 'services/api/client'

interface ResLoadData {
  data: any[]
  total: number
}

interface Props {
  loadData: (pagination: Pagination) => Promise<ResLoadData>
  id: string
  defaultOrder: string
  defaultOrderDir?: 'asc' | 'desc'
}

export default function useDataTable({
  loadData,
  id,
  defaultOrder,
  defaultOrderDir,
}: Props) {
  const queryClient = useQueryClient()
  const [pagination, setPagination] = useState({
    page: 1,
    perPage: 10,
    order: defaultOrder,
    orderDir: defaultOrderDir || 'asc',
  })

  const { isLoading, error, data } = useQuery<ResLoadData, Error>(
    [id, pagination],
    () => loadData(pagination)
  )

  const handlePaginationChange = (newState: any) => {
    setPagination((prevState) => ({
      ...prevState,
      ...newState,
    }))
    queryClient.invalidateQueries(id)
  }

  return {
    total: data?.total || 0,
    rows: data?.data || [],
    rowsPerPage: pagination.perPage,
    page: pagination.page - 1,
    order: pagination.order,
    orderDir: pagination.orderDir,
    isLoading,
    error,
    onPageChange: (event: any, newPage: number) => {
      handlePaginationChange({
        page: newPage + 1,
      })
    },
    onRowsPerPageChange: (event: any) => {
      handlePaginationChange({
        perPage: event.target.value,
      })
    },
  }
}
