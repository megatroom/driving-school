import { render, screen, fireEvent } from 'test-utils'
import { useNavigate } from 'routes/navigate'
import Home from '.'

jest.mock('routes/navigate')

test('renders the title', () => {
  render(<Home />)
  expect(screen.getByText('Tipos de carro')).toBeInTheDocument()
})

test('navigate to new regiter', async () => {
  let navigate = jest.fn()
  useNavigate.mockImplementation(() => navigate)

  render(<Home />)

  expect(await screen.findByText('Car')).toBeInTheDocument()

  fireEvent.click(screen.getByTitle('Novo registro'))

  expect(navigate).toHaveBeenCalledTimes(1)
  expect(navigate).toHaveBeenCalledWith('/cars/types/new')
})

test('renders the list', async () => {
  render(<Home />)
  expect(await screen.findByText('Car')).toBeInTheDocument()
  expect(screen.getByText('Motorcycle')).toBeInTheDocument()
  expect(screen.getByText('Truck')).toBeInTheDocument()
})

test('change to form when click in description', async () => {
  let navigate = jest.fn()
  useNavigate.mockImplementation(() => navigate)

  render(<Home />)

  fireEvent.click(await screen.findByText('Car'))

  expect(navigate).toHaveBeenCalledTimes(1)
  expect(navigate).toHaveBeenCalledWith('/cars/types/edit/1')
})
