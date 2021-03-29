import axios, { AxiosRequestConfig, AxiosPromise, AxiosResponse } from 'axios'
import storage from 'local-storage-fallback'

const KEY_TOKEN = 'ds-token'

const instance = axios.create({
  baseURL: process.env.REACT_APP_API_BASE_URL,
  timeout: 6000,
})

const request = (config: AxiosRequestConfig) => {
  const token = storage.getItem(KEY_TOKEN) || ''

  return instance.request({
    ...config,
    headers: { ...config.headers, Authorization: token },
  })
}

export interface ServiceResponse<T = any> extends AxiosResponse<T> {}

export interface AsyncServiceResponse<T = any> extends AxiosPromise<T> {}

export interface Pagination {
  page: number
  perPage: number
  order: string
}

const client = {
  get: (url: string, config = {}) => request({ ...config, method: 'get', url }),

  delete: (url: string, config = {}) =>
    request({ ...config, method: 'delete', url }),

  post: (url: string, data = {}, config = {}) =>
    request({ ...config, method: 'post', url, data }),

  put: (url: string, data = {}, config = {}) =>
    request({ ...config, method: 'put', url, data }),

  patch: (url: string, data = {}, config = {}) =>
    request({ ...config, method: 'patch', url, data }),

  saveToken: (token: string) => {
    storage.setItem(KEY_TOKEN, token)
  },
}

export default client
