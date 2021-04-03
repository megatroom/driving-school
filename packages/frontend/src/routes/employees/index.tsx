import { useNavigate } from 'react-router-dom'
import { getEmployees, deleteEmployee } from 'services/api/employees'
import ListPage from 'pages/ListPage'

export default function EmployeeList() {
  const navigate = useNavigate()

  return (
    <ListPage
      id="employee"
      title="Funcionários"
      loadData={getEmployees}
      onNewClick={() => {
        navigate(`/employees/new`)
      }}
      onDeleteClick={deleteEmployee}
      primaryTextKey="name"
      defaultOrder="name"
      columns={[
        {
          key: 'enrollment',
          label: 'Matrícula',
        },
        {
          key: 'name',
          label: 'Nome',
          onClick: (id) => {
            navigate(`/employees/edit/${id}`)
          },
        },
        {
          key: 'phone',
          label: 'Telefone',
        },
        {
          key: 'mobile',
          label: 'Celular',
        },
      ]}
    />
  )
}
