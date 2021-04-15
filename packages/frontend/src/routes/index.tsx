import { lazy } from 'react'
import { Routes, Route } from 'react-router-dom'

import PrivateLayout from 'layouts/PrivateLayout'
import AuthLayout from 'layouts/AuthLayout'

import NotFound from './NotFound'
import Login from './auth/login'
import Home from './home'

const CarsTypesForm = lazy(() => import('./cars/types/form'))
const CarsTypes = lazy(() => import('./cars/types'))
const CarsForm = lazy(() => import('./cars/form'))
const Cars = lazy(() => import('./cars'))
const EmployeesRoleForm = lazy(() => import('./employees/roles/form'))
const EmployeesRoles = lazy(() => import('./employees/roles'))
const EmployeesForm = lazy(() => import('./employees/form'))
const Employees = lazy(() => import('./employees'))

type PageRecord = Record<number, string>
const PageGroupRecord: Record<number, PageRecord> = {
  2: {
    8: '/employees/roles',
    9: '/employees',
    11: '/cars',
    18: '/cars/types',
  },
}

export function getRoutePath(groupCode: number, pageCode: number) {
  const path = PageGroupRecord?.[groupCode]?.[pageCode]

  if (!path) {
    console.error(`Page not found for group ${groupCode} page ${pageCode}`)
    return '/404'
  }

  return path
}

export default function AppRoutes() {
  return (
    <Routes>
      <Route path="/auth" element={<AuthLayout />}>
        <Route path="/login" element={<Login />} />
      </Route>
      <Route path="/" element={<PrivateLayout />}>
        <Route path="/" element={<Home />} />
        <Route path="/cars/types/new" element={<CarsTypesForm />} />
        <Route path="/cars/types/edit/:id" element={<CarsTypesForm />} />
        <Route path="/cars/types" element={<CarsTypes />} />
        <Route path="/cars/new" element={<CarsForm />} />
        <Route path="/cars/edit/:id" element={<CarsForm />} />
        <Route path="/cars" element={<Cars />} />
        <Route path="/employees/roles/new" element={<EmployeesRoleForm />} />
        <Route
          path="/employees/roles/edit/:id"
          element={<EmployeesRoleForm />}
        />
        <Route path="/employees/roles" element={<EmployeesRoles />} />
        <Route path="/employees/new" element={<EmployeesForm />} />
        <Route path="/employees/edit/:id" element={<EmployeesForm />} />
        <Route path="/employees" element={<Employees />} />
      </Route>
      <Route path="*" element={<NotFound />} />
    </Routes>
  )
}
