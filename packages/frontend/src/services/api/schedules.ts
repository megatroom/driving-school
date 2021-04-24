import client, { Pagination, ResponseListData } from './client'

export interface SchedulingTypePayload {
  description: string
}

export interface SchedulingType extends SchedulingTypePayload {
  id: number
}

export interface SchedulesPayload {
  enrollment: string
  status: string
  employeeTypeId: number
  name: string
  dateOfBirth?: string
  gender?: string
  rg?: string
  rgPrintDate?: string
  rgEmittingOrgan?: string
  cpf?: string
  workCard?: string
  address?: string
  cep?: string
  neighborhood?: string
  city?: string
  state?: string
  phone?: string
  phoneContact?: string
  phone2?: string
  phone2Contact?: string
  mobile?: string
  mobile2?: string
  mobile3?: string
  email?: string
  mother?: string
  father?: string
}

export interface Schedules extends SchedulesPayload {
  id: number
  schedulingTypeDesc: string
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
  search,
}: Pagination): Promise<ResponseListData<Schedules>> =>
  client
    .get(
      `/schedules?page=${page}&perPage=${perPage}&order=${order}${
        search ? `&search=${search}` : ''
      }`
    )
    .then(({ data }) => data)

export const getScheduling = (id: number): Promise<Schedules> =>
  client.get(`/schedules/${id}`).then(({ data }) => data)

export const postSchedules = (payload: SchedulesPayload): Promise<Schedules> =>
  client.post('/schedules/', payload).then(({ data }) => data)

export const putSchedules = (
  id: number,
  payload: SchedulesPayload
): Promise<Schedules> =>
  client.put(`/schedules/${id}`, payload).then(({ data }) => data)

export const deleteSchedules = (id: number): Promise<any> =>
  client.delete(`/schedules/${id}`).then(() => {})
