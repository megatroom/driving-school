import { useState } from 'react'
import { useQueryClient, useQuery } from 'react-query'
import { useNavigate } from 'react-router-dom'
import Container from '@material-ui/core/Container'

import DataTable, { Column } from 'organisms/DataTable'
import { Pagination } from 'services/api/client'

interface Props {
  id: string
  loadData: (pagination: Pagination) => Promise<any[]>
  columns: Column[]
}

export default function ListPage({ id, loadData, columns }: Props) {
  const navigate = useNavigate()
  const queryClient = useQueryClient()
  const [pagination, setPagination] = useState({
    page: 1,
    perPage: 10,
    order: 'description',
  })

  const { isLoading, error, data } = useQuery<any[], Error>(
    [id, pagination],
    () => {
      return loadData(pagination)
    }
  )

  return (
    <Container maxWidth="md">
      <DataTable
        title="Tipos de carro"
        columns={columns}
        rows={data || []}
        rowsPerPage={pagination.perPage}
        page={pagination.page - 1}
        isLoading={isLoading}
        error={error}
        onPageChange={() => {}}
        onRowsPerPageChange={(event) => {
          setPagination((prevState) => ({
            ...prevState,
            perPage: event.target.value,
          }))
          queryClient.invalidateQueries(id)
        }}
        onRowClick={(id) => {
          navigate(`/cars/types/edit/${id}`)
        }}
        onNewClick={() => {
          navigate(`/cars/types/new`)
        }}
      />
    </Container>
  )
}
