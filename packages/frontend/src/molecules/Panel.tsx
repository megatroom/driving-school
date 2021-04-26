import { FC } from 'react'
import { makeStyles } from '@material-ui/core/styles'
import Toolbar from '@material-ui/core/Toolbar'
import Typography from '@material-ui/core/Typography'
import Paper from '@material-ui/core/Paper'
import AppBar from '@material-ui/core/AppBar'
import LinearProgress from '@material-ui/core/LinearProgress'
import Button, { ButtonProps } from '@material-ui/core/Button'

const usePanelStyles = makeStyles((theme) => ({
  paper: {
    width: '100%',
    marginBottom: theme.spacing(2),
    overflow: 'hidden',
  },
  header: {
    borderBottom: '1px solid rgba(0, 0, 0, 0.12)',
  },
  toolbar: {
    paddingLeft: theme.spacing(2),
    paddingRight: theme.spacing(1),
  },
  title: {
    flex: '1 1 100%',
  },
}))

interface PanelProps {
  renderActions?: () => void
  title?: string
  isLoading?: boolean
}

const Panel: FC<PanelProps> = ({
  renderActions,
  title,
  isLoading,
  children,
}) => {
  const classes = usePanelStyles()

  return (
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
          {renderActions && renderActions()}
        </Toolbar>
        {isLoading && <LinearProgress />}
      </AppBar>
      {children}
    </Paper>
  )
}

const useBodyStyles = makeStyles((theme) => ({
  root: {
    padding: theme.spacing(2),
  },
}))

export const PanelBody: FC = ({ children }) => {
  const classes = useBodyStyles()

  return <div className={classes.root}>{children}</div>
}

const useButtonStyles = makeStyles((theme) => ({
  root: {
    marginLeft: theme.spacing(1),
    whiteSpace: 'nowrap',
  },
}))

export const PanelButton: FC<ButtonProps> = (props) => {
  const classes = useButtonStyles()

  return (
    <Button
      className={classes.root}
      variant="contained"
      disableElevation
      {...props}
    />
  )
}

export default Panel
