import { useNavigate } from 'react-router-dom'
import { getSchedules, deleteScheduling } from 'services/api/schedules'
import ListPage from 'pages/ListPage'

export default function SchedulingList() {
  const navigate = useNavigate()

  return (
    <ListPage
      id="schedules"
      title="Agendamentos"
      loadData={getSchedules}
      onNewClick={() => {
        navigate(`/schedules/new`)
      }}
      onDeleteClick={deleteScheduling}
      primaryTextKey="description"
      defaultOrder="date"
      columns={[
        {
          key: 'studentName',
          label: 'Aluno',
          onClick: (id) => {
            navigate(`/schedules/edit/${id}`)
          },
        },
        {
          key: 'description',
          label: 'Descrição',
          onClick: (id) => {
            navigate(`/schedules/edit/${id}`)
          },
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
  )
}
