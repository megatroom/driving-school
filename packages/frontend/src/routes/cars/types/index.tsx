import { useNavigate } from 'routes/navigate'
import { getCarTypes, deleteCarType } from 'services/api/cars'
import ListPage from 'pages/ListPage'

export default function CarTypeList() {
  const navigate = useNavigate()

  return (
    <ListPage
      id="car-types"
      title="Tipos de carro"
      loadData={getCarTypes}
      onNewClick={() => {
        navigate('/cars/types/new')
      }}
      onDeleteClick={deleteCarType}
      primaryTextKey="description"
      defaultOrder="description"
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
