import {
  ThemeProvider as MuiThemeProvider,
  createMuiTheme,
} from '@material-ui/core/styles'
import { ptBR } from '@material-ui/core/locale'
import blue from '@material-ui/core/colors/blue'
import purple from '@material-ui/core/colors/purple'

const theme = createMuiTheme(
  {
    palette: {
      primary: {
        main: blue[500],
      },
      secondary: {
        main: purple['A400'],
      },
    },
  },
  ptBR
)

export const ThemeProvider: React.FC = ({ children }) => (
  <MuiThemeProvider theme={theme}>{children}</MuiThemeProvider>
)
