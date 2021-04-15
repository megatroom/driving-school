import client, { Pagination, ResponseListData } from './client'

export interface CarTypePayload {
  description: string
  commission: number
}

export interface CarType extends CarTypePayload {
  id: number
}

export interface CarPayload {
  carTypeId: number
  fixedEmployeeId?: number
  description: string
  licensePlate: string
  year: number
  modelYear: number
  purchaseDate: string
  saleDate?: string
}

export interface Car extends CarPayload {
  id: number
  carTypeDescription: string
  fixedEmployeeName: string
}

export const getCarTypes = ({
  page,
  perPage,
  order,
  search,
}: Pagination): Promise<ResponseListData<CarType>> =>
  client
    .get(
      `/cars/types?page=${page}&perPage=${perPage}&order=${order}${
        search ? `&search=${search}` : ''
      }`
    )
    .then(({ data }) => data)

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

export const getCars = ({
  page,
  perPage,
  order,
  search,
}: Pagination): Promise<ResponseListData<Car>> =>
  client
    .get(
      `/cars?page=${page}&perPage=${perPage}&order=${order}${
        search ? `&search=${search}` : ''
      }`
    )
    .then(({ data }) => data)

export const getCar = (id: number): Promise<Car> =>
  client.get(`/cars/${id}`).then(({ data }) => data)

export const postCar = (payload: CarPayload): Promise<Car> =>
  client.post('/cars/', payload).then(({ data }) => data)

export const putCar = (id: number, payload: CarPayload): Promise<Car> =>
  client.put(`/cars/${id}`, payload).then(({ data }) => data)

export const deleteCar = (id: number): Promise<any> =>
  client.delete(`/cars/${id}`).then(() => {})
