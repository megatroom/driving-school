import client, {
  Pagination,
  AsyncServiceResponse,
  ResponseListData,
} from './client'

export interface User {
  id: number
  login: string
  name?: string
  employeeId?: number
  employeeName?: string
  observations?: string
}

export interface UserPayload extends User {}

interface Page {
  id: number
  code: number
  name: string
  path: string
}

export interface Menu {
  id: number
  code: number
  name: string
  pages: Page[]
}

export interface UserProfile {
  user: User
  menu: Menu
}

export const getUserProfile = (): AsyncServiceResponse<UserProfile> =>
  client.get('/users/profile')

export const getUsers = ({
  page,
  perPage,
  order,
  orderDir,
  search,
}: Pagination): Promise<ResponseListData<User>> =>
  client
    .get('/users', {
      params: {
        page,
        perPage,
        order,
        orderDir,
        search: search || undefined,
      },
    })
    .then(({ data }) => data)

export const getUser = (id: number): Promise<User> =>
  client.get(`/users/${id}`).then(({ data }) => data)

export const postUser = (payload: UserPayload): Promise<User> =>
  client.post('/users/', payload).then(({ data }) => data)

export const putUser = (id: number, payload: UserPayload): Promise<User> =>
  client.put(`/users/${id}`, payload).then(({ data }) => data)

export const deleteUser = (id: number): Promise<any> =>
  client.delete(`/users/${id}`).then(() => {})
