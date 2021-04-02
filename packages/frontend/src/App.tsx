import { BrowserRouter as Router } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from 'react-query'
import { Helmet } from 'react-helmet'
import { SnackbarProvider } from 'notistack'
import { ThemeProvider } from '@material-ui/core/styles'
import CssBaseline from '@material-ui/core/CssBaseline'

import Routes from './routes'
import config from './config'
import { theme } from 'context/theme'
import { UserProvider } from 'context/user'

const queryClient = new QueryClient()

function App() {
  return (
    <>
      <Helmet>
        <title>{config.title}</title>
      </Helmet>
      <CssBaseline />
      <QueryClientProvider client={queryClient}>
        <UserProvider>
          <ThemeProvider theme={theme}>
            <SnackbarProvider maxSnack={5}>
              <Router>
                <Routes />
              </Router>
            </SnackbarProvider>
          </ThemeProvider>
        </UserProvider>
      </QueryClientProvider>
    </>
  )
}

export default App
