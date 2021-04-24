import healthcheck from './healthcheck'
import usersLogin from './auth/login'
import usersProfile from './users/profile'
import carsTypes from './cars/types'
import cars from './cars'
import employeesRoles from './employees/roles'
import employees from './employees'
import studentOrigins from './students/origins'
import students from './students'

export default [
  healthcheck,
  usersLogin,
  usersProfile,
  carsTypes,
  cars,
  employeesRoles,
  employees,
  studentOrigins,
  students,
]
