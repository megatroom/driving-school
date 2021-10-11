import client, { Pagination, ResponseListData } from './client'

export interface NotificationPayload {
  recipientId: number
  message: string
  date: string
  priority: string
  status: string
}

export interface Notification extends NotificationPayload {
  id: number
  recipientName: string
  senderId: number
  senderName: string
  statusDesc?: string
  priorityDesc?: string
}

export const getNotifications = ({
  page,
  perPage,
  order,
  orderDir,
  search,
}: Pagination): Promise<ResponseListData<Notification>> =>
  client
    .get('/notifications', {
      params: {
        page,
        perPage,
        order,
        orderDir,
        search: search || undefined,
      },
    })
    .then(({ data }) => data)

export const getNotification = (id: number): Promise<Notification> =>
  client.get(`/notifications/${id}`).then(({ data }) => data)

export const postNotification = (
  payload: NotificationPayload
): Promise<Notification> =>
  client.post('/notifications/', payload).then(({ data }) => data)

export const putNotification = (
  id: number,
  payload: NotificationPayload
): Promise<Notification> =>
  client.put(`/notifications/${id}`, payload).then(({ data }) => data)

export const deleteNotification = (id: number): Promise<any> =>
  client.delete(`/notifications/${id}`).then(() => {})
