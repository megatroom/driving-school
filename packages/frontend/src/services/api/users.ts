import client, { AsyncServiceResponse } from './client'

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

export interface UserProfile {
    user: User
    menu: Menu
}

export const getUserProfile = (): AsyncServiceResponse<UserProfile> =>
    client.get('/users/profile')
