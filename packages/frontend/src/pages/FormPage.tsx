import { FC } from 'react'
import { makeStyles } from '@material-ui/core/styles'
import Container from '@material-ui/core/Container'
import AppBar from '@material-ui/core/AppBar'
import Toolbar from '@material-ui/core/Toolbar'
import Typography from '@material-ui/core/Typography'
import Paper from '@material-ui/core/Paper'
import Button from '@material-ui/core/Button'
import LinearProgress from '@material-ui/core/LinearProgress'
import Alert from '@material-ui/lab/Alert'
import AlertTitle from '@material-ui/lab/AlertTitle'

const useStyles = makeStyles((theme) => ({
  root: {
    '& .Mui-error > input': {
      borderColor: 'red',
    },
  },
  paper: {
    width: '100%',
    marginBottom: theme.spacing(2),
  },
  header: {
    borderBottom: '1px solid rgba(0, 0, 0, 0.12)',
  },
  toolbar: {
    paddingLeft: theme.spacing(2),
    paddingRight: theme.spacing(2),
  },
  action: {
    marginLeft: theme.spacing(1),
  },
  title: {
    flex: '1 1 100%',
  },
  body: {
    padding: theme.spacing(2),
  },
}))

interface Props {
  onCancel: () => void
  onSubmit: () => void
  title: string
  isLoading?: boolean
  validationError?: any
  customError?: Error
}

const FormPage: FC<Props> = ({
  onCancel,
  onSubmit,
  children,
  title,
  isLoading,
  validationError,
  customError,
}) => {
  const classes = useStyles()

  return (
    <form onSubmit={onSubmit} className={classes.root}>
      <Container maxWidth="md">
        <Paper className={classes.paper}>
          <AppBar
            className={classes.header}
            position="static"
            color="default"
            elevation={0}
          >
            <Toolbar className={classes.toolbar}>
              <Typography
                className={classes.title}
                variant="h6"
                id="tableTitle"
                component="div"
              >
                {title}
              </Typography>
              <Button
                className={classes.action}
                onClick={onCancel}
                type="button"
                variant="contained"
                color="default"
                disableElevation
              >
                Cancelar
              </Button>
              <Button
                className={classes.action}
                type="submit"
                variant="contained"
                color="primary"
                disableElevation
              >
                Salvar
              </Button>
            </Toolbar>
            {isLoading && <LinearProgress />}
          </AppBar>
          {customError && (
            <Alert severity="error">
              <AlertTitle>Erro</AlertTitle>
              {customError.message}
            </Alert>
          )}
          {validationError && (
            <Alert severity="warning">
              <AlertTitle>Atenção</AlertTitle>
              <ul>
                {Object.entries(validationError).map(
                  ([key, error]: Array<any>) => (
                    <li key={`warn-validation-${key}`}>{error.message}</li>
                  )
                )}
              </ul>
            </Alert>
          )}
          <div className={classes.body}>{!isLoading && children}</div>
        </Paper>
      </Container>
    </form>
  )
}

export default FormPage
