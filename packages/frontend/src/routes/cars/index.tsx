import { useNavigate } from 'react-router-dom'
import { getCars, deleteCar } from 'services/api/cars'
import ListPage from 'pages/ListPage'

export default function CarList() {
  const navigate = useNavigate()

  return (
    <ListPage
      id="cars"
      title="Carros"
      loadData={getCars}
      onNewClick={() => {
        navigate(`/cars/new`)
      }}
      onDeleteClick={deleteCar}
      primaryTextKey="description"
      defaultOrder="description"
      columns={[
        {
          key: 'carTypeDescription',
          label: 'Tipo',
        },
        {
          key: 'description',
          label: 'Descrição',
          onClick: (id) => {
            navigate(`/cars/edit/${id}`)
          },
        },
        {
          key: 'licensePlate',
          label: 'Placa',
          align: 'right',
        },
      ]}
    />
  )
}
