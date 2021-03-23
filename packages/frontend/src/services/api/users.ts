import client, { ServiceResponse, AsyncServiceResponse } from './client'

export interface SigninPayload {
  email: string
  password: string
}

export interface User {
  id: number
  login: string
  name?: string
}

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

export interface CurrentUser {
  user: User
  menu: Menu
}

export const getCurrentUser = (): AsyncServiceResponse<CurrentUser> =>
  client.get('/users/current')

export const signin = async (
  data: SigninPayload
): Promise<ServiceResponse<CurrentUser>> => {
  const res = await client.post('/users/signin', data)

  client.saveToken(res.data.token)

  return await getCurrentUser()
}
