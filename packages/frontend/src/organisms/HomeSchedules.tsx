import { useNavigate } from 'react-router-dom'
import { getSchedulesAboveNow } from 'services/api/schedules'
import useDataTable from 'hooks/useDataTable'
import Panel, { PanelButton } from 'molecules/Panel'
import DataTable from 'organisms/DataTable'

export default function HomeSchedules() {
  const navigate = useNavigate()
  const {
    total,
    rows,
    rowsPerPage,
    page,
    order,
    orderDir,
    isLoading,
    error,
    onPageChange,
    onRowsPerPageChange,
  } = useDataTable({
    loadData: getSchedulesAboveNow,
    id: 'home-schedules',
    defaultOrder: 'description',
  })

  return (
    <Panel
      title="Agendamentos"
      renderActions={() => (
        <PanelButton
          color="default"
          type="button"
          onClick={() => {
            navigate('/schedules')
          }}
        >
          Ver mais
        </PanelButton>
      )}
    >
      <DataTable
        total={total}
        rows={rows}
        rowsPerPage={rowsPerPage}
        order={order}
        orderDir={orderDir}
        page={page}
        isLoading={isLoading}
        error={error}
        onPageChange={onPageChange}
        onRowsPerPageChange={onRowsPerPageChange}
        title="Agendamentos"
        primaryTextKey="description"
        columns={[
          {
            key: 'studentName',
            label: 'Aluno',
          },
          {
            key: 'description',
            label: 'Descrição',
          },
          {
            key: 'date',
            label: 'Data',
            type: 'date',
          },
          {
            key: 'time',
            label: 'Hora',
          },
        ]}
      />
    </Panel>
  )
}
