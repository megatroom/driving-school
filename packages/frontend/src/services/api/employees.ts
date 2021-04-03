import client, { Pagination, ResponseListData } from './client'

export interface EmployeeRolePayload {
  description: string
}

export interface EmployeeRole extends EmployeeRolePayload {
  id: number
}

export interface EmployeePayload {
  enrollment: string
  status: string
  employeeRoleId: number
  name: string
  dateOfBirth?: string
  gender?: string
  rg?: string
  rgPrintDate?: string
  rgEmittingOrgan?: string
  cpf?: string
  workCard?: string
  address?: string
  postalCode?: string
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

export interface Employee extends EmployeePayload {
  id: number
  employeeRoleDesc: string
}

export const getEmployeeRoles = ({
  page,
  perPage,
  order,
  search,
}: Pagination): Promise<ResponseListData<EmployeeRole>> =>
  client
    .get(
      `/employees/roles?page=${page}&perPage=${perPage}&order=${order}${
        search ? `&search=${search}` : ''
      }`
    )
    .then(({ data }) => data)

export const getEmployeeRole = (id: number): Promise<EmployeeRole> =>
  client.get(`/employees/roles/${id}`).then(({ data }) => data)

export const postEmployeeRole = (
  payload: EmployeeRolePayload
): Promise<EmployeeRole> =>
  client.post('/employees/roles/', payload).then(({ data }) => data)

export const putEmployeeRole = (
  id: number,
  payload: EmployeeRolePayload
): Promise<EmployeeRole> =>
  client.put(`/employees/roles/${id}`, payload).then(({ data }) => data)

export const deleteEmployeeRole = (id: number): Promise<any> =>
  client.delete(`/employees/roles/${id}`).then(() => {})

export const getEmployees = ({
  page,
  perPage,
  order,
  search,
}: Pagination): Promise<ResponseListData<Employee>> =>
  client
    .get(
      `/employees?page=${page}&perPage=${perPage}&order=${order}${
        search ? `&search=${search}` : ''
      }`
    )
    .then(({ data }) => data)

export const getEmployee = (id: number): Promise<Employee> =>
  client.get(`/employees/${id}`).then(({ data }) => data)

export const postEmployee = (payload: EmployeePayload): Promise<Employee> =>
  client.post('/employees/', payload).then(({ data }) => data)

export const putEmployee = (
  id: number,
  payload: EmployeePayload
): Promise<Employee> =>
  client.put(`/employees/${id}`, payload).then(({ data }) => data)

export const deleteEmployee = (id: number): Promise<any> =>
  client.delete(`/employees/${id}`).then(() => {})
