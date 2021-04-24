import { useNavigate } from 'routes/navigate'
import { getStudents, deleteStudent } from 'services/api/students'
import ListPage from 'pages/ListPage'

export default function StudentList() {
  const navigate = useNavigate()

  return (
    <ListPage
      id="students"
      title="Alunos"
      loadData={getStudents}
      onNewClick={() => {
        navigate('/students/new')
      }}
      onDeleteClick={deleteStudent}
      primaryTextKey="name"
      defaultOrder="name"
      columns={[
        {
          key: 'enrollment',
          label: 'Matrícula',
          onClick: (id) => {
            navigate(`/students/edit/${id}`)
          },
        },
        {
          key: 'matriculacfc',
          label: 'Matrícula CFC',
          onClick: (id) => {
            navigate(`/students/edit/${id}`)
          },
        },
        {
          key: 'name',
          label: 'Nome',
          onClick: (id) => {
            navigate(`/students/edit/${id}`)
          },
        },
        {
          key: 'cpf',
          label: 'CPF',
        },
        {
          key: 'dtcreate',
          label: 'Data Cadastro',
          type: 'datetime',
        },
      ]}
    />
  )
}
