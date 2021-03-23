import { BrowserRouter as Router } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from 'react-query'
import { ThemeProvider } from '@material-ui/core/styles'
import CssBaseline from '@material-ui/core/CssBaseline'

import Routes from './Routes'
import { theme } from 'context/theme'
import { UserProvider } from 'context/user'

const queryClient = new QueryClient()

function App() {
  return (
    <>
      <CssBaseline />
      <QueryClientProvider client={queryClient}>
        <UserProvider>
          <ThemeProvider theme={theme}>
            <Router>
              <Routes />
            </Router>
          </ThemeProvider>
        </UserProvider>
      </QueryClientProvider>
    </>
  )
}

export default App
