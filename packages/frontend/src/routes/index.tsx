import { lazy } from 'react'
import { Routes, Route } from 'react-router-dom'

import { Menu } from 'services/api/users'
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
const StudentsOriginsForm = lazy(() => import('./students/origins/form'))
const StudentsOrigins = lazy(() => import('./students/origins'))
const StudentsForm = lazy(() => import('./students/form'))
const Students = lazy(() => import('./students'))
const SchedulesTypesForm = lazy(() => import('./schedules/types/form'))
const SchedulesTypes = lazy(() => import('./schedules/types'))
const SchedulesForm = lazy(() => import('./schedules/form'))
const Schedules = lazy(() => import('./schedules'))

type PageRecord = Record<number, string>
const PageGroupRecord: Record<number, PageRecord> = {
  1: {
    7: '/schedules',
  },
  2: {
    8: '/employees/roles',
    9: '/employees',
    10: '/students',
    11: '/cars',
    16: '/schedules/types',
    18: '/cars/types',
    30: '/students/origins',
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

export function filterMenuWithRoutes(menu: Menu[]) {
  const newMenu = menu.filter((group) => {
    return !!PageGroupRecord?.[group.code]
  })

  return newMenu.map((group) => {
    return {
      ...group,
      pages: group.pages.filter(
        (page) => !!PageGroupRecord?.[group.code]?.[page.code]
      ),
    }
  })
}

export default function AppRoutes() {
  return (
    <Routes>
      <Route path="/auth" element={<AuthLayout />}>
        <Route path="/login" element={<Login />} />
      </Route>
      <Route path="/" element={<PrivateLayout />}>
        <Route path="/" element={<Home />} />
        <Route path="/cars/types/edit/:id" element={<CarsTypesForm />} />
        <Route path="/cars/types/new" element={<CarsTypesForm />} />
        <Route path="/cars/types" element={<CarsTypes />} />
        <Route path="/cars/edit/:id" element={<CarsForm />} />
        <Route path="/cars/new" element={<CarsForm />} />
        <Route path="/cars" element={<Cars />} />
        <Route
          path="/employees/roles/edit/:id"
          element={<EmployeesRoleForm />}
        />
        <Route path="/employees/roles/new" element={<EmployeesRoleForm />} />
        <Route path="/employees/roles" element={<EmployeesRoles />} />
        <Route path="/employees/edit/:id" element={<EmployeesForm />} />
        <Route path="/employees/new" element={<EmployeesForm />} />
        <Route path="/employees" element={<Employees />} />
        <Route
          path="/students/origins/edit/:id"
          element={<StudentsOriginsForm />}
        />
        <Route path="/students/origins/new" element={<StudentsOriginsForm />} />
        <Route path="/students/origins" element={<StudentsOrigins />} />
        <Route path="/students/edit/:id" element={<StudentsForm />} />
        <Route path="/students/new" element={<StudentsForm />} />
        <Route path="/students" element={<Students />} />
        <Route
          path="/schedules/types/edit/:id"
          element={<SchedulesTypesForm />}
        />
        <Route path="/schedules/types/new" element={<SchedulesTypesForm />} />
        <Route path="/schedules/types" element={<SchedulesTypes />} />
        <Route path="/schedules/edit/:id" element={<SchedulesForm />} />
        <Route path="/schedules/new" element={<SchedulesForm />} />
        <Route path="/schedules" element={<Schedules />} />
      </Route>
      <Route path="*" element={<NotFound />} />
    </Routes>
  )
}
