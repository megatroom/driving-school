import { useNavigate } from 'routes/navigate'
import { getStudentOrigins, deleteStudentOrigin } from 'services/api/students'
import ListPage from 'pages/ListPage'

export default function StudentOriginList() {
  const navigate = useNavigate()

  return (
    <ListPage
      id="student-origin"
      title="Origens"
      loadData={getStudentOrigins}
      onNewClick={() => {
        navigate(`/students/origins/new`)
      }}
      onDeleteClick={deleteStudentOrigin}
      primaryTextKey="description"
      defaultOrder="description"
      columns={[
        {
          key: 'description',
          label: 'Descrição',
          onClick: (id) => {
            navigate(`/students/origins/edit/${id}`)
          },
        },
      ]}
    />
  )
}
