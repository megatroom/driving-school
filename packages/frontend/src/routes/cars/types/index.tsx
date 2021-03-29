import { getCarTypes } from 'services/api/cars'
import ListPage from 'pages/ListPage'

export default function Index() {
  return (
    <ListPage
      id="car-types"
      loadData={getCarTypes}
      columns={[
        { key: 'id', label: 'ID' },
        { key: 'description', label: 'Descrição' },
        { key: 'commission', label: 'Comissão', align: 'right' },
      ]}
    />
  )
}
