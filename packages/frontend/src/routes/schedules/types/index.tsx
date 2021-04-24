import { useNavigate } from 'react-router-dom'
import {
  getSchedulingTypes,
  deleteSchedulingType,
} from 'services/api/schedules'
import ListPage from 'pages/ListPage'

export default function EmployeeRoleList() {
  const navigate = useNavigate()

  return (
    <ListPage
      id="schedule-types"
      title="Tipo de agendamentos"
      loadData={getSchedulingTypes}
      onNewClick={() => {
        navigate(`/schedules/types/new`)
      }}
      onDeleteClick={deleteSchedulingType}
      primaryTextKey="description"
      defaultOrder="description"
      columns={[
        {
          key: 'description',
          label: 'Descrição',
          onClick: (id) => {
            navigate(`/schedules/types/edit/${id}`)
          },
        },
      ]}
    />
  )
}
