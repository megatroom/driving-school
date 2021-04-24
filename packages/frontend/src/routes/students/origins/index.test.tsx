import { render, screen, fireEvent } from 'test-utils'
import { useNavigate } from 'routes/navigate'
import Home from '.'

jest.mock('routes/navigate')

test('renders the title', () => {
  render(<Home />)
  expect(screen.getByText('Origens')).toBeInTheDocument()
})

test('navigate to new regiter', async () => {
  let navigate = jest.fn()
  useNavigate.mockImplementation(() => navigate)

  render(<Home />)

  expect(await screen.findByText('News')).toBeInTheDocument()

  fireEvent.click(screen.getByTitle('Novo registro'))

  expect(navigate).toHaveBeenCalledTimes(1)
  expect(navigate).toHaveBeenCalledWith('/students/origins/new')
})

test('renders the list', async () => {
  render(<Home />)
  expect(await screen.findByText('News')).toBeInTheDocument()
  expect(screen.getByText('Social Media')).toBeInTheDocument()
  expect(screen.getByText('Indication')).toBeInTheDocument()
})

test('change to form when click in description', async () => {
  let navigate = jest.fn()
  useNavigate.mockImplementation(() => navigate)

  render(<Home />)

  fireEvent.click(await screen.findByText('News'))

  expect(navigate).toHaveBeenCalledTimes(1)
  expect(navigate).toHaveBeenCalledWith('/students/origins/edit/1')
})
