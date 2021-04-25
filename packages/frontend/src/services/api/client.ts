import axios, {
  AxiosRequestConfig,
  AxiosPromise,
  AxiosResponse,
  AxiosError,
} from 'axios'
import storage from 'local-storage-fallback'

const KEY_TOKEN = 'ds-token'

const instance = axios.create({
  baseURL: process.env.REACT_APP_API_BASE_URL,
  timeout: 6000,
})

const adaptValidationError = (error: AxiosError<any>) => {
  if (!error.response || error.response.status !== 400) {
    return undefined
  }

  const { data } = error.response

  if (!data || !data.errors || !data.errors.length) {
    return undefined
  }

  return data.errors.reduce((acc: any, error: any, index: number) => {
    return {
      ...acc,
      [error.key || `unknown-index-${index}`]: {
        label: error.label,
        message: error.message,
      },
    }
  }, {})
}

const request = (config: AxiosRequestConfig) => {
  const token = storage.getItem(KEY_TOKEN) || ''

  return new Promise<AxiosResponse>((resolve, reject) => {
    // Leave this for debug
    // console.log(`${config.method} ${config.url}`)

    instance
      .request({
        ...config,
        headers: { ...config.headers, Authorization: token },
      })
      .then(resolve)
      .catch((error) => {
        reject({
          ...error,
          validation: adaptValidationError(error),
        })
      })
  })
}

export interface ServiceResponse<T = any> extends AxiosResponse<T> {}

export interface AsyncServiceResponse<T = any> extends AxiosPromise<T> {}

export interface Pagination {
  page: number
  perPage: number
  order: string
  orderDir?: string
  search?: string
}

export interface ResponseListData<T> {
  data: T[]
  total: number
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

  clearToken: () => {
    storage.removeItem(KEY_TOKEN)
  },
}

export default client
