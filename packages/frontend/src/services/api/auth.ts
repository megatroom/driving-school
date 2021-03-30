import client, { ServiceResponse } from './client'
import { UserProfile, getUserProfile } from './users'

export interface LoginPayload {
    email: string
    password: string
}

export const login = async (
    data: LoginPayload
): Promise<ServiceResponse<UserProfile>> => {
    const res = await client.post('/auth/login', data)

    client.saveToken(res.data.token)

    return await getUserProfile()
}
