import { useNavigate } from 'react-router-dom'
import {
  getNotifications,
  deleteNotification,
} from 'services/api/notifications'
import ListPage from 'pages/ListPage'

export default function NotificationList() {
  const navigate = useNavigate()

  return (
    <ListPage
      id="notifications"
      title="Avisos"
      loadData={getNotifications}
      onNewClick={() => {
        navigate(`/notifications/new`)
      }}
      onDeleteClick={deleteNotification}
      primaryTextKey="recipientName"
      defaultOrder="date"
      columns={[
        {
          key: 'senderName',
          label: 'Remetente',
        },
        {
          key: 'recipientName',
          label: 'Destinatário',
          onClick: (id) => {
            navigate(`/notifications/edit/${id}`)
          },
        },
        {
          key: 'date',
          label: 'Data',
          type: 'date',
        },
        {
          key: 'statusDesc',
          label: 'Status',
        },
        {
          key: 'priorityDesc',
          label: 'Prioridade',
        },
      ]}
    />
  )
}
