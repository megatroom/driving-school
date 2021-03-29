import client, { Pagination } from './client'

export interface CarTypes {
  id: number
  description: string
  commission: number
}

export const getCarTypes = ({
  page,
  perPage,
  order,
}: Pagination): Promise<CarTypes[]> =>
  client
    .get(`/cars/types?page=${page}&perPage=${perPage}&order=${order}`)
    .then(({ data }) => data.carTypes)
