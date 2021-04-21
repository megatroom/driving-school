import { FC, ReactElement } from 'react'
import { MemoryRouter, Routes, Route } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from 'react-query'
import { render, RenderOptions } from '@testing-library/react'
import { SnackbarProvider } from 'notistack'

import { ThemeProvider } from './context/theme'
import { UserProvider } from './context/user'

const queryClient = new QueryClient()

interface Props {
  initialEntries?: string[]
}

const AllTheProviders: FC<Props> = ({ initialEntries, children }) => {
  return (
    <QueryClientProvider client={queryClient}>
      <UserProvider>
        <ThemeProvider>
          <SnackbarProvider maxSnack={5}>
            <MemoryRouter initialEntries={initialEntries || ['/']}>
              {children}
            </MemoryRouter>
          </SnackbarProvider>
        </ThemeProvider>
      </UserProvider>
    </QueryClientProvider>
  )
}

const customRender = (
  ui: ReactElement,
  options?: Omit<RenderOptions, 'queries'>
) => render(ui, { wrapper: AllTheProviders, ...options })

export * from '@testing-library/react'

export const renderOnRouteId = (id: number, ui: ReactElement) =>
  render(
    <AllTheProviders initialEntries={[`/form/${id}`]}>
      <Routes>
        <Route path="/form/:id" element={ui} />
      </Routes>
    </AllTheProviders>
  )

export { customRender as render }
