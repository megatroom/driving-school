import { useNavigate } from 'routes/navigate'
import { getUsers, deleteUser } from 'services/api/users'
import ListPage from 'pages/ListPage'

export default function StudentList() {
  const navigate = useNavigate()

  return (
    <ListPage
      id="users"
      title="Usuários"
      loadData={getUsers}
      onNewClick={() => {
        navigate('/users/new')
      }}
      onDeleteClick={deleteUser}
      primaryTextKey="name"
      defaultOrder="name"
      columns={[
        {
          key: 'login',
          label: 'Login',
          onClick: (id) => {
            navigate(`/users/edit/${id}`)
          },
        },
        {
          key: 'name',
          label: 'Nome',
          onClick: (id) => {
            navigate(`/users/edit/${id}`)
          },
        },
      ]}
    />
  )
}
