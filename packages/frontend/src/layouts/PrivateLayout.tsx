import { useEffect, useState, Suspense } from 'react'
import { Outlet, useNavigate } from 'react-router-dom'
import { makeStyles } from '@material-ui/core/styles'
import CircularProgress from '@material-ui/core/CircularProgress'
import clsx from 'clsx'

import AppBar from 'molecules/AppBar'
import AppDrawer, { DrawerHeader, drawerWidth } from 'molecules/AppDrawer'
import { AuthStatus, useUser } from 'context/user'

const useStyles = makeStyles((theme) => ({
  root: {
    display: 'flex',
  },
  content: {
    flexGrow: 1,
    padding: theme.spacing(3),
    transition: theme.transitions.create('margin', {
      easing: theme.transitions.easing.sharp,
      duration: theme.transitions.duration.leavingScreen,
    }),
    marginLeft: -drawerWidth,
  },
  contentShift: {
    transition: theme.transitions.create('margin', {
      easing: theme.transitions.easing.easeOut,
      duration: theme.transitions.duration.enteringScreen,
    }),
    marginLeft: 0,
  },
  loading: {
    textAlign: 'center',
  },
}))

export default function PrivateLayout() {
  const navigate = useNavigate()
  const classes = useStyles()
  const [open, setOpen] = useState(false)
  const { authStatus } = useUser()

  const handleDrawerOpen = () => {
    setOpen(true)
  }

  const handleDrawerClose = () => {
    setOpen(false)
  }

  useEffect(() => {
    if (authStatus === AuthStatus.unauthenticated) {
      navigate('/auth/login')
    }
  }, [authStatus, navigate])

  return (
    <div className={classes.root}>
      <AppBar menuOpen={open} onMenuClick={handleDrawerOpen} />
      <AppDrawer open={open} onClose={handleDrawerClose} />
      <main
        className={clsx(classes.content, {
          [classes.contentShift]: open,
        })}
      >
        <DrawerHeader />
        <Suspense
          fallback={
            <div className={classes.loading}>
              <CircularProgress color="secondary" />
            </div>
          }
        >
          <Outlet />
        </Suspense>
      </main>
    </div>
  )
}
