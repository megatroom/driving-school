import client, { Pagination, ResponseListData } from './client'

export interface SchedulingTypePayload {
  description: string
}

export interface SchedulingType extends SchedulingTypePayload {
  id: number
}

export interface SchedulingPayload {
  description: string
  studentId: number
  schedulingTypeId: number
  date: string
  time: string
  approved: string
}

export interface Scheduling extends SchedulingPayload {
  id: number
  schedulingTypeDesc: string
  approvedDesc: string
  studentName: string
}

export const getSchedulingTypes = ({
  page,
  perPage,
  order,
  search,
}: Pagination): Promise<ResponseListData<SchedulingType>> =>
  client
    .get(
      `/schedules/types?page=${page}&perPage=${perPage}&order=${order}${
        search ? `&search=${search}` : ''
      }`
    )
    .then(({ data }) => data)

export const getSchedulingType = (id: number): Promise<SchedulingType> =>
  client.get(`/schedules/types/${id}`).then(({ data }) => data)

export const postSchedulingType = (
  payload: SchedulingTypePayload
): Promise<SchedulingType> =>
  client.post('/schedules/types/', payload).then(({ data }) => data)

export const putSchedulingTypes = (
  id: number,
  payload: SchedulingTypePayload
): Promise<SchedulingType> =>
  client.put(`/schedules/types/${id}`, payload).then(({ data }) => data)

export const deleteSchedulingType = (id: number): Promise<any> =>
  client.delete(`/schedules/types/${id}`).then(() => {})

export const getSchedules = ({
  page,
  perPage,
  order,
  orderDir,
  search,
}: Pagination): Promise<ResponseListData<Scheduling>> =>
  client
    .get(
      `/schedules?page=${page}&perPage=${perPage}&order=${order}&orderDir=${orderDir}${
        search ? `&search=${search}` : ''
      }`
    )
    .then(({ data }) => data)

export const getScheduling = (id: number): Promise<Scheduling> =>
  client.get(`/schedules/${id}`).then(({ data }) => data)

export const postScheduling = (
  payload: SchedulingPayload
): Promise<Scheduling> =>
  client.post('/schedules/', payload).then(({ data }) => data)

export const putScheduling = (
  id: number,
  payload: SchedulingPayload
): Promise<Scheduling> =>
  client.put(`/schedules/${id}`, payload).then(({ data }) => data)

export const deleteScheduling = (id: number): Promise<any> =>
  client.delete(`/schedules/${id}`).then(() => {})
