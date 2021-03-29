import { useState } from 'react'
import { useQueryClient, useQuery } from 'react-query'

import DataTable, { Column } from 'organisms/DataTable'
import { Pagination } from 'services/api/client'

interface Props {
  id: string
  loadData: (pagination: Pagination) => Promise<any[]>
  columns: Column[]
}

export default function ListPage({ id, loadData, columns }: Props) {
  const queryClient = useQueryClient()
  const [pagination, setPagination] = useState({
    page: 1,
    perPage: 10,
    order: 'description',
  })

  const { isLoading, error, data } = useQuery<any[], Error>(
    [id, pagination],
    () => {
      console.log('useQuery was called!!!!!', pagination)
      return loadData(pagination)
    }
  )

  if (isLoading) return <p>'Loading...'</p>

  if (error) return <p>'An error has occurred: ' + error.message</p>

  return (
    <DataTable
      columns={columns}
      rows={data || []}
      rowsPerPage={pagination.perPage}
      page={pagination.page - 1}
      onPageChange={() => {}}
      onRowsPerPageChange={(event) => {
        setPagination((prevState) => ({
          ...prevState,
          perPage: event.target.value,
        }))
        queryClient.invalidateQueries(id)
      }}
    />
  )
}
