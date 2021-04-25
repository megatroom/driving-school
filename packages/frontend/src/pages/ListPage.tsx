import { useState } from 'react'
import { useQueryClient, useQuery } from 'react-query'
import { useSnackbar } from 'notistack'
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
  defaultOrderDir?: 'asc' | 'desc'
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
  defaultOrderDir,
  columns,
}: Props) {
  const { enqueueSnackbar } = useSnackbar()
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
        orderDir={pagination.orderDir}
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
          onDeleteClick(deleteId)
            .then(() => {
              queryClient.invalidateQueries(id)
              enqueueSnackbar(`Exclusão efetuada com sucesso.`, {
                variant: 'success',
              })
            })
            .catch((err) => {
              const message = err?.response?.data?.message || ''
              enqueueSnackbar(`Erro ao excluir. ${message}`, {
                variant: 'error',
              })
            })
        }}
        onOrderChange={(key) => {
          if (key === pagination.order) {
            handlePaginationChange({
              order: key,
              orderDir: pagination.orderDir === 'asc' ? 'desc' : 'asc',
            })
          } else {
            handlePaginationChange({
              order: key,
              orderDir: 'asc',
            })
          }
        }}
        onSearch={(text) => {
          handlePaginationChange({
            search: text,
          })
        }}
      />
    </Container>
  )
}
