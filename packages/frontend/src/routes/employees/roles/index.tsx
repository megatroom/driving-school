import { useNavigate } from 'react-router-dom'
import { getEmployeeRoles, deleteEmployeeRole } from 'services/api/employees'
import ListPage from 'pages/ListPage'

export default function EmployeeRoleList() {
  const navigate = useNavigate()

  return (
    <ListPage
      id="employee-role"
      title="Funções"
      loadData={getEmployeeRoles}
      onNewClick={() => {
        navigate(`/employees/roles/new`)
      }}
      onDeleteClick={deleteEmployeeRole}
      primaryTextKey="description"
      defaultOrder="description"
      columns={[
        {
          key: 'description',
          label: 'Descrição',
          onClick: (id) => {
            navigate(`/employees/roles/edit/${id}`)
          },
        },
      ]}
    />
  )
}
