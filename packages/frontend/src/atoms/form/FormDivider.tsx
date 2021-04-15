import { makeStyles } from '@material-ui/core/styles'
import Divider from '@material-ui/core/Divider'

const useStyles = makeStyles((theme) => ({
  root: {
    marginBottom: theme.spacing(3),
  },
}))

export default function FormDivider() {
  const classes = useStyles()

  return (
    <div className={classes.root}>
      <Divider />
    </div>
  )
}
