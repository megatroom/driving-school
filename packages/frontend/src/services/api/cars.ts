import client, { Pagination } from './client'

export interface CarType {
  id: number
  description: string
  commission: number
}

export interface CarTypePayload {
  description: string
  commission: number
}

export const getCarTypes = ({
  page,
  perPage,
  order,
}: Pagination): Promise<CarType[]> =>
  client
    .get(`/cars/types?page=${page}&perPage=${perPage}&order=${order}`)
    .then(({ data }) => data.carTypes)

export const getCarType = (id: number): Promise<CarType> =>
  client.get(`/cars/types/${id}`).then(({ data }) => data)

export const postCarType = (payload: CarTypePayload): Promise<CarType> =>
  client.post('/cars/types/', payload).then(({ data }) => data)

export const putCarType = (
  id: number,
  payload: CarTypePayload
): Promise<CarType> =>
  client.put(`/cars/types/${id}`, payload).then(({ data }) => data)

export const deleteCarType = (id: number): Promise<any> =>
  client.delete(`/cars/types/${id}`).then(() => {})
