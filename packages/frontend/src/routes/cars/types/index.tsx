import { useNavigate } from 'react-router-dom'
import { getCarTypes, deleteCarType } from 'services/api/cars'
import ListPage from 'pages/ListPage'

export default function Index() {
  const navigate = useNavigate()

  return (
    <ListPage
      id="car-types"
      loadData={getCarTypes}
      onNewClick={() => {
        navigate(`/cars/types/new`)
      }}
      onDeleteClick={deleteCarType}
      primaryTextKey="description"
      columns={[
        {
          key: 'description',
          label: 'Descrição',
          onClick: (id) => {
            navigate(`/cars/types/edit/${id}`)
          },
        },
        {
          key: 'commission',
          label: 'Comissão',
          align: 'right',
          type: 'currency',
        },
      ]}
    />
  )
}
