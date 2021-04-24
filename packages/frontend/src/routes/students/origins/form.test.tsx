import userEvent from '@testing-library/user-event'
import { render, renderOnRouteId, screen, fireEvent, waitFor } from 'test-utils'
import { useNavigate } from 'routes/navigate'
import Form from './form'

jest.mock('routes/navigate')

test('renders the title', () => {
  render(<Form />)
  expect(screen.getByText('Origem')).toBeInTheDocument()
})

test('renders edit mode', async () => {
  renderOnRouteId(1, <Form />)

  await waitFor(() =>
    expect(screen.getByLabelText('Descrição *')).toHaveValue('News')
  )
})

test('returns to list on cancel button click', () => {
  let navigate = jest.fn()
  useNavigate.mockImplementation(() => navigate)

  render(<Form />)

  fireEvent.click(screen.getByText('Cancelar'))

  expect(navigate).toHaveBeenCalledTimes(1)
  expect(navigate).toHaveBeenCalledWith('/students/origins')
})

test('form submit', async () => {
  let navigate = jest.fn()
  useNavigate.mockImplementation(() => navigate)

  render(<Form />)

  userEvent.type(screen.getByLabelText('Descrição *'), 'Airplane')

  fireEvent.click(screen.getByText('Salvar'))

  expect(
    await screen.findByText('Origem cadastrado com sucesso')
  ).toBeInTheDocument()

  expect(navigate).toHaveBeenCalledTimes(1)
  expect(navigate).toHaveBeenCalledWith('/students/origins')
})
