import { Routes, Route } from 'react-router-dom'

import PrivateLayout from 'templates/PrivateLayout'
import AuthLayout from 'templates/AuthLayout'

import Login from 'routes/auth/login'
import Home from 'routes/home'

export default function AppRoutes() {
  return (
    <Routes>
      <Route path="/" element={<PrivateLayout />}>
        <Route path="/" element={<Home />} />
      </Route>
      <Route path="/" element={<AuthLayout />}>
        <Route path="/login" element={<Login />} />
      </Route>
    </Routes>
  )
}
