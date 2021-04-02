import { Routes, Route } from 'react-router-dom'

import PrivateLayout from 'layouts/PrivateLayout'
import AuthLayout from 'layouts/AuthLayout'

import NotFound from './NotFound'
import Login from './auth/login'
import Home from './home'
import CarsTypes from './cars/types'
import CarsTypesForm from './cars/types/form'

type PageRecord = Record<number, string>
const PageGroupRecord: Record<number, PageRecord> = {
  2: {
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
        <Route path="/cars/types" element={<CarsTypes />} />
        <Route path="/cars/types/new" element={<CarsTypesForm />} />
        <Route path="/cars/types/edit/:id" element={<CarsTypesForm />} />
      </Route>
      <Route path="*" element={<NotFound />} />
    </Routes>
  )
}
