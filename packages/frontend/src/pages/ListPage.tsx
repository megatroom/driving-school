import { useState } from 'react'
import { useQueryClient, useQuery } from 'react-query'
import Container from '@material-ui/core/Container'

import DataTable, { Column } from 'organisms/DataTable'
import { Pagination } from 'services/api/client'

interface ResLoadData {
  data: any[]
  total: number
}
interface Props {
  onNewClick: () => void
  onDeleteClick: (id: number) => Promise<any>
  loadData: (pagination: Pagination) => Promise<ResLoadData>
  id: string
  title: string
  primaryTextKey: string
  defaultOrder: string
  columns: Column[]
}

export default function ListPage({
  onNewClick,
  onDeleteClick,
  loadData,
  id,
  title,
  primaryTextKey,
  defaultOrder,
  columns,
}: Props) {
  const queryClient = useQueryClient()
  const [pagination, setPagination] = useState({
    page: 1,
    perPage: 10,
    order: defaultOrder,
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

  return (
    <Container maxWidth="md">
      <DataTable
        title={title}
        primaryTextKey={primaryTextKey}
        columns={columns}
        total={data?.total || 0}
        rows={data?.data || []}
        rowsPerPage={pagination.perPage}
        page={pagination.page - 1}
        order={pagination.order}
        isLoading={isLoading}
        error={error}
        onPageChange={(event, newPage) => {
          handlePaginationChange({
            page: newPage + 1,
          })
        }}
        onRowsPerPageChange={(event) => {
          handlePaginationChange({
            perPage: event.target.value,
          })
        }}
        onNewClick={onNewClick}
        onDeleteClick={(deleteId) => {
          onDeleteClick(deleteId).then(() => {
            queryClient.invalidateQueries(id)
          })
        }}
        onOrderChange={(key) => {
          if (key !== pagination.order) {
            handlePaginationChange({
              order: key,
            })
          }
        }}
      />
    </Container>
  )
}
