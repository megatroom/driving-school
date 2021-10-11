import { render, screen } from 'test-utils'
import Home from '.'

test('renders successfully', () => {
  render(<Home />)
  expect(screen.getByText('Agendamentos')).toBeInTheDocument()
})
