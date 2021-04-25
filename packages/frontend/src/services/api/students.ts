import client, { Pagination, ResponseListData } from './client'

export interface StudentOriginPayload {
  description: string
}

export interface StudentOrigin extends StudentOriginPayload {
  id: number
}

export interface StudentPayload {
  originId?: number
  enrollmentcfc?: string
  renach?: string
  observations?: string
  regcnh?: string
  currentCategory?: string
  processExpiration?: string
  accessCode?: number
  noEmail?: string
  name: string
  dateOfBirth?: string
  gender?: string
  rg?: string
  rgPrintDate?: string
  rgEmittingOrgan?: string
  cpf?: string
  workCard?: string
  address?: string
  cep?: string
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

export interface Student extends StudentPayload {
  id: number
  originDesc?: string
  enrollment?: string
}

export const getStudentOrigins = ({
  page,
  perPage,
  order,
  search,
}: Pagination): Promise<ResponseListData<StudentOrigin>> =>
  client
    .get(
      `/students/origins?page=${page}&perPage=${perPage}&order=${order}${
        search ? `&search=${search}` : ''
      }`
    )
    .then(({ data }) => data)

export const getStudentOrigin = (id: number): Promise<StudentOrigin> =>
  client.get(`/students/origins/${id}`).then(({ data }) => data)

export const postStudentOrigin = (
  payload: StudentOriginPayload
): Promise<StudentOrigin> =>
  client.post('/students/origins/', payload).then(({ data }) => data)

export const putStudentOrigin = (
  id: number,
  payload: StudentOriginPayload
): Promise<StudentOrigin> =>
  client.put(`/students/origins/${id}`, payload).then(({ data }) => data)

export const deleteStudentOrigin = (id: number): Promise<any> =>
  client.delete(`/students/origins/${id}`).then(() => {})

export const getStudents = ({
  page,
  perPage,
  order,
  orderDir,
  search,
}: Pagination): Promise<ResponseListData<Student>> =>
  client
    .get(
      `/students?page=${page}&perPage=${perPage}&order=${order}&orderDir=${orderDir}${
        search ? `&search=${search}` : ''
      }`
    )
    .then(({ data }) => data)

export const getStudent = (id: number): Promise<Student> =>
  client.get(`/students/${id}`).then(({ data }) => data)

export const postStudent = (payload: StudentPayload): Promise<Student> =>
  client.post('/students/', payload).then(({ data }) => data)

export const putStudent = (
  id: number,
  payload: StudentPayload
): Promise<Student> =>
  client.put(`/students/${id}`, payload).then(({ data }) => data)

export const deleteStudent = (id: number): Promise<any> =>
  client.delete(`/students/${id}`).then(() => {})
